<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\WorkLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WorkLogController extends Controller
{
    public function index()
    {
        $user     = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->route('dashboard')
                ->with('error', 'Perfil de funcionário não encontrado. Fale com o RH.');
        }

        $todayLog = $employee->todayLog;

        return view('work-log.index', compact('todayLog', 'employee'));
    }

    public function punch(Request $request)
    {
        $user     = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return response()->json(['error' => 'Perfil de funcionário não encontrado.'], 404);
        }

        $result = DB::transaction(function () use ($employee) {
            // Lock na linha do employee para serializar batidas concorrentes
            Employee::where('id', $employee->id)->lockForUpdate()->first();

            $log = WorkLog::firstOrCreate(
                [
                    'employee_id' => $employee->id,
                    'work_date'   => today()->toDateString(),
                ]
            );

            $action = $log->getNextAction();

            if (!$action) {
                return ['error' => 'Você já finalizou sua jornada por hoje. Bom descanso!'];
            }

            // Trava: mínimo 60 segundos entre batidas pra evitar double-click
            $lastPunch = match ($action) {
                'clock_in'  => $log->created_at, // se acabou de criar, usa created_at
                'lunch_out' => $log->clock_in,
                'lunch_in'  => $log->lunch_out,
                'clock_out' => $log->lunch_in ?? $log->clock_in,
                default     => null,
            };

            if ($lastPunch && $lastPunch->diffInSeconds(now()) < 60) {
                return ['error' => 'Aguarde pelo menos 1 minuto entre os registros.'];
            }

            $log->$action = now();

            if ($action === 'clock_out') {
                $log->total_minutes = $log->calculateTotalMinutes();
                $log->save();
                $employee->recalculateHourBank($log);
            } else {
                $log->save();
            }

            return [
                'success' => true,
                'action'  => $action,
                'time'    => now()->format('H:i:s'),
                'next'    => $log->fresh()->getNextAction(),
            ];
        });

        if (isset($result['error'])) {
            return response()->json($result, 422);
        }

        return response()->json($result);
    }

    public function history()
    {
        $user     = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->route('dashboard');
        }

        $logs = $employee->workLogs()
            ->orderBy('work_date', 'desc')
            ->paginate(15);

        $totalBalance = $employee->total_hour_bank_minutes;

        return view('work-log.history', compact('logs', 'totalBalance'));
    }
}