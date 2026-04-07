<?php

namespace App\Http\Controllers;

use App\Models\WorkLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkLogController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->route('dashboard')
                ->with('error', 'Perfil de funcionario nao encontrado.');
        }

        $todayLog = $employee->todayLog;

        return view('work-log.index', compact('todayLog', 'employee'));
    }

    public function punch(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return response()->json(['error' => 'Funcionario nao encontrado.'], 404);
        }

        $log = WorkLog::firstOrCreate(
            ['employee_id' => $employee->id, 'work_date' => today()],
        );

        $action = $log->getNextAction();

        if (!$action) {
            return response()->json(['error' => 'Jornada ja finalizada hoje.'], 422);
        }

        $log->$action = now();

        if ($action === 'clock_out') {
            $log->total_minutes = $log->calculateTotalMinutes();
        }

        $log->save();

        return response()->json([
            'success' => true,
            'action'  => $action,
            'time'    => now()->format('H:i:s'),
            'next'    => $log->getNextAction(),
        ]);
    }

    public function history()
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->route('dashboard');
        }

        $logs = $employee->workLogs()
            ->orderBy('work_date', 'desc')
            ->paginate(15);

        return view('work-log.history', compact('logs'));
    }
}