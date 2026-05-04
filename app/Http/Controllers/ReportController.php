<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\HrConfig;
use App\Models\WorkLog;
use App\Services\CalendarService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));
        [$year, $monthNum] = explode('-', $month);
        $year = (int) $year;
        $monthNum = (int) $monthNum;

        $workingDaysGlobal = CalendarService::getWorkingDaysCount($year, $monthNum);

        $employees = Employee::with(['user', 'workSchedule', 'workLogs' => function($q) use ($year, $monthNum) {
            $q->whereYear('work_date', $year)->whereMonth('work_date', $monthNum);
        }])->get();

        $report = $employees->map(function ($employee) use ($workingDaysGlobal, $year, $monthNum) {
            $logs = $employee->workLogs;

            $totalMinutes    = $logs->whereNotNull('clock_out')->sum('total_minutes');
            $daysWorked      = $logs->whereNotNull('clock_out')->count();
            $daysIncomplete  = $logs->whereNotNull('clock_in')->whereNull('clock_out')->count();

            // Usa o calendário específico do funcionário (para ler a flag de sábado corretamente)
            $workingDays = CalendarService::getWorkingDaysCount($year, $monthNum, $employee->id);
            $referenceDate = \Carbon\Carbon::createFromDate($year, $monthNum, 1);

            // Horas esperadas: jornada individual ou config global por vigência
            if ($employee->workSchedule) {
                $expectedPerDay = $employee->workSchedule->work_hours_per_day * 60;
            } else {
                $monthlyHours = (int) $employee->getConfig('monthly_hours', $referenceDate);
                $expectedPerDay = $workingDays > 0 ? ($monthlyHours * 60 / $workingDays) : 0;
            }

            $expectedMinutes = $expectedPerDay * $workingDays;
            $balance = $totalMinutes - $expectedMinutes;

            return [
                'employee'        => $employee,
                'days_worked'     => $daysWorked,
                'days_incomplete' => $daysIncomplete,
                'total_minutes'   => $totalMinutes,
                'expected_minutes'=> (int) $expectedMinutes,
                'balance_minutes' => (int) $balance,
            ];
        });

        $totals = [
            'employees'       => $employees->count(),
            'total_days'      => $report->sum('days_worked'),
            'total_hours'     => $report->sum('total_minutes'),
            'positive_balance'=> $report->filter(fn($r) => $r['balance_minutes'] > 0)->count(),
            'negative_balance'=> $report->filter(fn($r) => $r['balance_minutes'] < 0)->count(),
        ];

        return view('reports.index', compact('report', 'totals', 'month'));
    }
}
