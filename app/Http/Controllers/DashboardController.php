<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\WorkLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $totalEmployees = Employee::count();
            $todayLogs = WorkLog::whereDate('work_date', today())
                ->with('employee.user')
                ->get();

            return view('dashboard.admin', compact('totalEmployees', 'todayLogs'));
        }

        $employee = $user->employee;
        $todayLog = null;
        $recentLogs = collect();

        if ($employee) {
            $todayLog = $employee->todayLog;
            $recentLogs = $employee->workLogs()
                ->orderBy('work_date', 'desc')
                ->limit(7)
                ->get();
        }

        return view('dashboard.employee', compact('todayLog', 'recentLogs'));
    }
}