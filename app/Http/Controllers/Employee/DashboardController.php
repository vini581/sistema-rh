<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\WorkLog;
use App\Models\MedicalCertificate;
use App\Models\VacationRequest;
use App\Services\PayrollCalculator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $employee = Auth::user()->employee;

        if (!$employee) {
            return redirect('/dashboard')->with('error', 'Perfil de funcionário não encontrado. Fale com o RH.');
        }

        $now = Carbon::now();
        $calculator = new PayrollCalculator();

        $payroll = $calculator->calculate($employee, $now->year, $now->month, false);

        $daysWorked = WorkLog::where('employee_id', $employee->id)
            ->whereYear('work_date', $now->year)
            ->whereMonth('work_date', $now->month)
            ->whereNotNull('clock_out')
            ->count();

        $certificateDays = MedicalCertificate::where('employee_id', $employee->id)
            ->where('status', 'approved')
            ->whereMonth('start_date', $now->month)
            ->whereYear('start_date', $now->year)
            ->sum('days');

        // Proteção contra admission_date nulo
        $vacationBalance = 0;
        if ($employee->admission_date) {
            $monthsSinceAdmission = $employee->admission_date->diffInMonths($now);
            $vacationTaken = VacationRequest::where('employee_id', $employee->id)
                ->where('status', 'approved')
                ->sum('days');
            $vacationBalance = max(0, round(($monthsSinceAdmission * 2.5) - $vacationTaken, 1));
        }

        return view('employee.dashboard', compact('employee', 'payroll', 'daysWorked', 'certificateDays', 'vacationBalance'));
    }
}
