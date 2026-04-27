<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::where('name', 'like', '%euclaudio%')->first();
if (!$user) {
    echo "User not found\n";
    exit;
}

$employee = $user->employee;
if (!$employee) {
    echo "Employee not found\n";
    exit;
}

$year = 2026;
$month = 4;

$payroll = App\Models\Payroll::where('employee_id', $employee->id)
    ->whereYear('reference_month', $year)
    ->whereMonth('reference_month', $month)
    ->first();

if (!$payroll) {
    echo "Payroll not found for April 2026. Recalculating...\n";
    $calculator = new App\Services\PayrollCalculator();
    $payroll = $calculator->calculate($employee, $year, $month, false);
}

echo "--- Resumo da Folha (Abril/2026) ---\n";
echo "Nome: " . $user->name . "\n";
echo "Horas Trabalhadas: " . round($payroll->worked_hours / 60, 2) . "h (" . $payroll->worked_hours . " min)\n";
echo "Bruto Total: R$ " . number_format($payroll->gross_total / 100, 2, ',', '.') . "\n";
echo "Descontos: R$ " . number_format(($payroll->deductions ?? 0) / 100, 2, ',', '.') . "\n";
echo "Líquido: R$ " . number_format($payroll->net_total / 100, 2, ',', '.') . "\n";

// Detalhando o cálculo (usando o calculador novamente para ver os componentes)
$calculator = new App\Services\PayrollCalculator();
// Vou usar reflexão ou apenas ver o código do PayrollCalculator para entender o que ele faz.
// Na verdade, vou rodar uma versão customizada do cálculo aqui para ver os valores.

$referenceMonth = Illuminate\Support\Carbon::createFromDate($year, $month, 1)->startOfMonth();
$config = App\Models\HrConfig::forDate($referenceMonth, $employee->id);

$hourlyRate     = (float) $employee->getConfig('hourly_rate', $referenceMonth);
$monthlyHours   = (int) $employee->getConfig('monthly_hours', $referenceMonth);
$overtimeWkPct  = (int) ($config->overtime_weekday_pct ?? 50);

$logs = App\Models\WorkLog::where('employee_id', $employee->id)
    ->whereYear('work_date', $year)
    ->whereMonth('work_date', $month)
    ->whereNotNull('clock_out')
    ->get();

$totalMinutes = $logs->sum('total_minutes');
$workingDays = App\Services\CalendarService::getWorkingDaysCount($year, $month);
$expectedMinutes = $monthlyHours * 60;

$normalMinutes   = min($totalMinutes, $expectedMinutes);
$extraMinutes    = max(0, $totalMinutes - $expectedMinutes);

$normalCents = (int) round(($normalMinutes / 60) * $hourlyRate);
$overtimeCents = (int) round(($extraMinutes / 60) * $hourlyRate * (1 + $overtimeWkPct / 100));

echo "\n--- Detalhamento Estimado ---\n";
echo "Valor Hora: R$ " . number_format($hourlyRate / 100, 2, ',', '.') . "\n";
echo "Horas Normais: " . round($normalMinutes / 60, 2) . "h -> R$ " . number_format($normalCents / 100, 2, ',', '.') . "\n";
if ($extraMinutes > 0) {
    echo "Horas Extras (" . $overtimeWkPct . "%): " . round($extraMinutes / 60, 2) . "h -> R$ " . number_format($overtimeCents / 100, 2, ',', '.') . "\n";
}
echo "Total Bruto Estimado: R$ " . number_format(($normalCents + $overtimeCents) / 100, 2, ',', '.') . "\n";
