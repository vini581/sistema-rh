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
    public function calculate(Employee $employee, int $year, int $month, bool $persist = true, string $type = 'monthly'): Payroll
    {
        // Limpa caches estáticos para garantir dados frescos
        CalendarService::clearCache();
        HrConfig::clearCache();

        $logic = function () use ($employee, $year, $month, $persist, $type) {
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

                $isMonthly = ($paymentType === 'monthly');

                $overtimeWkPct      = (int) ($config->overtime_weekday_pct ?? 50);
                $overtimeSatPct     = (int) ($config->overtime_saturday_pct ?? 50);
                $overtimeSunPct     = (int) ($config->overtime_sunday_pct ?? 100);
                $overtimeHolPct     = (int) ($config->overtime_holiday_pct ?? 100);
                $saturdayIsOvertime = (bool) ($config->saturday_is_overtime ?? true);

                if ($type === 'advance') {
                    $advancePct = (int) ($config->biweekly_first_pct ?? 40);
                    $grossTotalCents = (int) round($expectedMinutes / 60 * $hourlyRate * ($advancePct / 100));
                    
                    $data = [
                        'worked_hours' => 0,
                        'gross_total'  => $grossTotalCents,
                        'status'       => $persist ? 'calculated' : 'draft',
                    ];
                    $totalDeductions = 0;
                    $deductionNotes = null;
                    $periodType = 'advance';
                    $normalMinutes = 0;
                    $nightMinutes = 0;
                } else {
                    $startOfMonth = $referenceMonth->copy();
                    $endOfMonth   = $referenceMonth->copy()->endOfMonth();

                    $normalMinutes     = 0;
                    $overtimeCents     = 0;
                    $totalAbsenceCents = 0;
                    $nonWorkingDays    = 0;

                    $vacations = \App\Models\VacationRequest::where('employee_id', $employee->id)
                        ->where('status', 'approved')->get();

                    $certificates = MedicalCertificate::where('employee_id', $employee->id)
                        ->where('status', 'approved')->where('excused', true)->get();

                    // Cache de feriados do mês inteiro (Bug 1: elimina N+1)
                    $holidayDates = \App\Models\Holiday::whereYear('date', $year)
                        ->whereMonth('date', $month)
                        ->pluck('date')
                        ->map(fn($d) => \Carbon\Carbon::parse($d)->format('Y-m-d'))
                        ->toArray();

                    $schedule = $employee->workSchedule;
                    $dailyExpected = $schedule ? (int)($schedule->work_hours_per_day * 60) : (int)$expectedPerDay;

                    for ($date = clone $startOfMonth; $date->lte($endOfMonth); $date->addDay()) {
                        $isWorkingDay = CalendarService::isWorkingDay($date, $employee->id);
                        $isHoliday    = in_array($date->format('Y-m-d'), $holidayDates);

                        if (!$isWorkingDay) {
                            $nonWorkingDays++;
                        }

                        $log = $logs->first(fn($l) => $l->work_date->isSameDay($date));
                        $worked = $log ? (int) $log->total_minutes : 0;

                        if (!$isWorkingDay) {
                            // Dia não útil: todo o trabalho é hora extra com taxa especial
                            if ($worked > 0) {
                                $pct = $overtimeWkPct;
                                if ($isHoliday) {
                                    $pct = $overtimeHolPct;
                                } elseif ($date->isSunday()) {
                                    $pct = $overtimeSunPct;
                                } elseif ($date->isSaturday() && $saturdayIsOvertime) {
                                    $pct = $overtimeSatPct;
                                }

                                $overtimeCents += (int) round(($worked / 60) * $hourlyRate * (1 + $pct / 100));
                            }
                        } else {
                            // Dia útil
                            $hasVacation = $vacations->contains(fn($v) => $v->coversDate($date));

                            $hasCertificate = $certificates->contains(function ($c) use ($date) {
                                return $date->between(
                                    \Carbon\Carbon::parse($c->start_date),
                                    \Carbon\Carbon::parse($c->end_date)
                                );
                            });

                            if ($hasVacation || $hasCertificate) {
                                // Férias e atestados contam como dia trabalhado cheio
                                $normalMinutes += $dailyExpected;
                            } else {
                                if ($worked > $dailyExpected) {
                                    $normalMinutes += $dailyExpected;
                                    $extraToday = $worked - $dailyExpected;

                                    if ($extraToday >= $minMinutes) {
                                        $overtimeCents += (int) round(($extraToday / 60) * $hourlyRate * (1 + $overtimeWkPct / 100));
                                    }
                                } else {
                                    $normalMinutes += $worked;
                                    // Faltou horas ou dia todo
                                    $missingToday = $dailyExpected - $worked;
                                    if ($missingToday > 0) {
                                        $totalAbsenceCents += (int) round(($missingToday / 60) * $hourlyRate);
                                    }
                                }
                            }
                        }
                    }

                    // Adicional noturno (22h-5h): estimativa baseada nos logs
                    $nightMinutes = $this->calculateNightMinutes($logs);
                    $nightCents = $nightPct > 0
                        ? (int) round(($nightMinutes / 60) * $hourlyRate * ($nightPct / 100))
                        : 0;

                    // DSR: mensalista = sobre horas extras; horista = sobre tudo
                    if ($isMonthly) {
                        $normalCents = (int) round($monthlyHours * $hourlyRate);
                        $dsrCents = $workingDays > 0 ? (int) round(($overtimeCents / $workingDays) * $nonWorkingDays) : 0;
                    } else {
                        $normalCents = (int) round(($normalMinutes / 60) * $hourlyRate);
                        $dsrCents = $workingDays > 0 ? (int) round((($normalCents + $overtimeCents) / $workingDays) * $nonWorkingDays) : 0;
                    }

                    $grossTotalCents = $normalCents + $overtimeCents + $nightCents + $dsrCents;

                    // Checa se já teve adiantamento fechado no mês
                    $advancePaid = Payroll::where('employee_id', $employee->id)
                        ->where('reference_month', $referenceMonth->format('Y-m-d'))
                        ->where('period_type', 'advance')
                        ->whereIn('status', ['closed', 'paid'])
                        ->first();
                    $advanceDeduction = $advancePaid ? $advancePaid->net_total : 0;

                    $data = [
                        'worked_hours' => $normalMinutes + $nightMinutes,
                        'gross_total'  => $grossTotalCents,
                        'status'       => $persist ? 'calculated' : 'draft',
                    ];

                    // Aplica dedução fixa configurada manualmente (Imposto Fixo)
                    $fixedDiscountPct = (int) ($config->fixed_discount_pct ?? 0);
                    $fixedDiscountCents = (int) round($grossTotalCents * ($fixedDiscountPct / 100));

                    $totalDeductions = $totalAbsenceCents + $fixedDiscountCents + $advanceDeduction;
                    $deductionNotes = "Faltas: R$ " . number_format($totalAbsenceCents/100, 2, ',', '.');
                    if ($fixedDiscountCents > 0) {
                        $deductionNotes .= " | Impostos: R$ " . number_format($fixedDiscountCents/100, 2, ',', '.');
                    }
                    if ($advanceDeduction > 0) {
                        $deductionNotes .= " | Adiantamento: R$ " . number_format($advanceDeduction/100, 2, ',', '.');
                    }

                    $periodType = $paymentType === 'biweekly' ? 'biweekly' : 'monthly';
                } // End of else ($type === 'monthly')

                if (!$persist) {
                    $payroll = new Payroll($data);
                    $payroll->employee_id = $employee->id;
                    $payroll->reference_month = $referenceMonth->format('Y-m-d');
                    $payroll->deductions = $totalDeductions;
                    $payroll->deduction_notes = $deductionNotes;
                    $payroll->net_total = max(0, $grossTotalCents - $totalDeductions);
                    return $payroll;
                }

                $payroll = Payroll::firstOrCreate(
                    [
                        'employee_id'     => $employee->id,
                        'reference_month' => $referenceMonth->format('Y-m-d'),
                        'period_type'     => $periodType,
                    ],
                    array_merge($data, [
                        'deductions' => $totalDeductions, 
                        'deduction_notes' => $deductionNotes,
                        'net_total' => max(0, $grossTotalCents - $totalDeductions)
                    ])
                );

                if ($payroll->wasRecentlyCreated) {
                    return $payroll;
                }

                // Nunca sobrescrever folha fechada ou paga
                if (in_array($payroll->status, ['closed', 'paid'])) {
                    return $payroll;
                }

                $payroll->worked_hours = $normalMinutes + $nightMinutes;
                $payroll->gross_total = $grossTotalCents;
                $payroll->deductions = $totalDeductions;
                $payroll->deduction_notes = $deductionNotes;
                $payroll->net_total = max(0, $grossTotalCents - $totalDeductions);
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

            $start = clone $log->clock_in;
            $end   = clone $log->clock_out;

            // Se o turno começou antes das 05:00 da manhã, ele pertence à noite do dia anterior
            if ($start->hour < 5) {
                $nightStart = $start->copy()->subDay()->setTime(22, 0, 0);
                $nightEnd   = $start->copy()->setTime(5, 0, 0);
            } else {
                $nightStart = $start->copy()->setTime(22, 0, 0);
                $nightEnd   = $start->copy()->addDay()->setTime(5, 0, 0);
            }

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
