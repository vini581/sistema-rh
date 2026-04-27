<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\HrConfig;
use App\Models\MedicalCertificate;
use App\Models\Payroll;
use App\Models\WorkLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PayrollCalculator
{
    /**
     * Calcula a folha de pagamento.
     *
     * Bruto = (Horas normais × Valor Hora)
     *       + (HE acima do mínimo × Valor Hora × % HE)
     *       + (Horas noturnas × Valor Hora × % Noturno)
     *       + (Dias de atestado abonados × Valor Dia)
     * Líquido = Bruto − Descontos manuais
     */
    public function calculate(Employee $employee, int $year, int $month, bool $persist = true): Payroll
    {
        $logic = function () use ($employee, $year, $month, $persist) {
            try {
                if ($persist) {
                    $employee = Employee::where('id', $employee->id)->lockForUpdate()->first();
                }

                $referenceMonth = Carbon::createFromDate($year, $month, 1)->startOfMonth();
                $config = HrConfig::forDate($referenceMonth, $employee->id);

                $hourlyRate     = (float) $employee->getConfig('hourly_rate', $referenceMonth);
                $monthlyHours   = (int) $employee->getConfig('monthly_hours', $referenceMonth);
                $minMinutes     = (int) ($config->overtime_min_minutes ?? 0);
                $nightPct       = (int) ($config->night_shift_pct ?? 0);
                $overtimeWkPct  = (int) ($config->overtime_weekday_pct ?? 50);
                $paymentType    = $employee->getConfig('payment_type', $referenceMonth);

                // Busca logs do mês
                $logs = WorkLog::where('employee_id', $employee->id)
                    ->whereYear('work_date', $year)
                    ->whereMonth('work_date', $month)
                    ->whereNotNull('clock_out')
                    ->whereNotNull('total_minutes')
                    ->get();

                $totalMinutes = $logs->sum('total_minutes');

                // Dias úteis e expectativa de minutos
                $workingDays = CalendarService::getWorkingDaysCount($year, $month);
                $expectedMinutes = $monthlyHours * 60;
                $expectedPerDay  = $workingDays > 0 ? ($expectedMinutes / $workingDays) : 0;

                // Separa horas normais vs extras
                $normalMinutes   = min($totalMinutes, $expectedMinutes);
                $rawExtraMinutes = max(0, $totalMinutes - $expectedMinutes);

                // Tolerância: só conta HE se exceder o mínimo configurado
                $extraMinutes = $rawExtraMinutes >= $minMinutes ? $rawExtraMinutes : 0;

                // Cálculo base em centavos
                $normalCents = (int) round(($normalMinutes / 60) * $hourlyRate);
                $overtimeCents = (int) round(($extraMinutes / 60) * $hourlyRate * (1 + $overtimeWkPct / 100));

                // Adicional noturno (22h-5h): estimativa baseada nos logs
                $nightMinutes = $this->calculateNightMinutes($logs);
                $nightCents = $nightPct > 0
                    ? (int) round(($nightMinutes / 60) * $hourlyRate * ($nightPct / 100))
                    : 0;

                // Atestados aprovados contam como trabalhados?
                $certificateCents = 0;
                if ($config->certificate_counts_as_worked) {
                    $certDays = MedicalCertificate::where('employee_id', $employee->id)
                        ->where('status', 'approved')
                        ->where('excused', true)
                        ->whereMonth('start_date', $month)
                        ->whereYear('start_date', $year)
                        ->sum('days');
                    $certificateCents = (int) round($certDays * $expectedPerDay / 60 * $hourlyRate);
                }

                $grossTotalCents = $normalCents + $overtimeCents + $nightCents + $certificateCents;

                $data = [
                    'worked_hours' => $totalMinutes,
                    'gross_total'  => $grossTotalCents,
                    'status'       => $persist ? 'calculated' : 'draft',
                ];

                if (!$persist) {
                    $payroll = new Payroll($data);
                    $payroll->employee_id = $employee->id;
                    $payroll->reference_month = $referenceMonth->format('Y-m-d');
                    $payroll->deductions = 0;
                    $payroll->net_total = $grossTotalCents;
                    return $payroll;
                }

                $periodType = $paymentType === 'biweekly' ? 'biweekly' : 'monthly';

                $payroll = Payroll::firstOrCreate(
                    [
                        'employee_id'     => $employee->id,
                        'reference_month' => $referenceMonth->format('Y-m-d'),
                        'period_type'     => $periodType,
                    ],
                    array_merge($data, ['deductions' => 0, 'net_total' => $grossTotalCents])
                );

                if ($payroll->wasRecentlyCreated) {
                    return $payroll;
                }

                // Nunca sobrescrever folha fechada ou paga
                if (in_array($payroll->status, ['closed', 'paid'])) {
                    return $payroll;
                }

                $payroll->worked_hours = $totalMinutes;
                $payroll->gross_total = $grossTotalCents;
                $payroll->net_total = $grossTotalCents - (int) $payroll->deductions;
                $payroll->period_type = $periodType;
                $payroll->save();

                return $payroll;

            } catch (\Exception $e) {
                Log::error("Erro ao calcular folha para funcionário {$employee->id}: " . $e->getMessage());
                throw $e;
            }
        };

        return $persist ? DB::transaction($logic) : $logic();
    }

    /**
     * Estima minutos noturnos (22h-5h) a partir dos logs.
     */
    protected function calculateNightMinutes($logs): int
    {
        $total = 0;

        foreach ($logs as $log) {
            if (!$log->clock_in || !$log->clock_out) continue;

            $start = $log->clock_in;
            $end   = $log->clock_out;

            // Período noturno: 22:00 do dia até 05:00 do dia seguinte
            $nightStart = $start->copy()->setTime(22, 0, 0);
            $nightEnd   = $start->copy()->addDay()->setTime(5, 0, 0);

            // Interseção entre jornada e período noturno
            $overlapStart = $start->max($nightStart);
            $overlapEnd   = $end->min($nightEnd);

            if ($overlapStart->lt($overlapEnd)) {
                $total += $overlapStart->diffInMinutes($overlapEnd);
            }
        }

        return $total;
    }
}
