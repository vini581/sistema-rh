<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::where('name', 'like', '%euclaudio%')->first();
$employee = $user->employee;
$year = 2026;
$month = 4;

$calculator = new App\Services\PayrollCalculator();
$payroll = $calculator->calculate($employee, $year, $month, false);

$referenceMonth = Illuminate\Support\Carbon::createFromDate($year, $month, 1)->startOfMonth();
$config = App\Models\HrConfig::forDate($referenceMonth, $employee->id);
$hourlyRate = (float) $employee->getConfig('hourly_rate', $referenceMonth);
$overtimeWkPct = (int) ($config->overtime_weekday_pct ?? 50);

$logs = App\Models\WorkLog::where('employee_id', $employee->id)
    ->whereYear('work_date', $year)
    ->whereMonth('work_date', $month)
    ->whereNotNull('clock_out')
    ->get();

$totalMinutes = $logs->sum('total_minutes');

// Atestados
$certDays = App\Models\MedicalCertificate::where('employee_id', $employee->id)
    ->where('status', 'approved')
    ->where('excused', true)
    ->whereMonth('start_date', $month)
    ->whereYear('start_date', $year)
    ->sum('days');

// Cálculo do valor do atestado
$workingDays = App\Services\CalendarService::getWorkingDaysCount($year, $month);
$monthlyHours = (int) $employee->getConfig('monthly_hours', $referenceMonth);
$expectedMinutes = $monthlyHours * 60;
$expectedPerDay = $workingDays > 0 ? ($expectedMinutes / $workingDays) : 0;
$certCents = (int) round($certDays * $expectedPerDay / 60 * $hourlyRate);

echo "Valor Hora: R$ " . number_format($hourlyRate / 100, 2, ',', '.') . "\n";
echo "Minutos Trabalhados (Logs): " . $totalMinutes . " (" . round($totalMinutes/60, 2) . "h)\n";
echo "Dias de Atestado Aprovados: " . $certDays . " dias\n";
echo "Valor p/ Dia (estimado): R$ " . number_format(($expectedPerDay / 60 * $hourlyRate) / 100, 2, ',', '.') . "\n";
echo "Total Atestado: R$ " . number_format($certCents / 100, 2, ',', '.') . "\n";

// Adicional Noturno
$nightMinutes = 0;
foreach ($logs as $log) {
    if (!$log->clock_in || !$log->clock_out) continue;
    $start = $log->clock_in;
    $end   = $log->clock_out;
    $nightStart = $start->copy()->setTime(22, 0, 0);
    $nightEnd   = $start->copy()->addDay()->setTime(5, 0, 0);
    $overlapStart = $start->max($nightStart);
    $overlapEnd   = $end->min($nightEnd);
    if ($overlapStart->lt($overlapEnd)) {
        $nightMinutes += $overlapStart->diffInMinutes($overlapEnd);
    }
}
$nightPct = (int) ($config->night_shift_pct ?? 0);
$nightCents = (int) round(($nightMinutes / 60) * $hourlyRate * ($nightPct / 100));
echo "Minutos Noturnos: " . $nightMinutes . " (" . round($nightMinutes/60, 2) . "h)\n";
echo "Adicional Noturno (" . $nightPct . "%): R$ " . number_format($nightCents / 100, 2, ',', '.') . "\n";

echo "Total Bruto: R$ " . number_format($payroll->gross_total / 100, 2, ',', '.') . "\n";
