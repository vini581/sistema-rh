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

            // Dados para o Gráfico (Últimos 7 dias) em apenas UMA query
            $startDate = today()->subDays(6);
            $counts = WorkLog::whereDate('work_date', '>=', $startDate)
                ->selectRaw('DATE(work_date) as date, count(*) as count')
                ->groupBy('date')
                ->pluck('count', 'date');

            $chartData = collect();
            for ($i = 6; $i >= 0; $i--) {
                $date = today()->subDays($i);
                $dateString = $date->toDateString();
                $count = $counts->get($dateString, 0);
                $chartData->push([
                    'date' => $date->format('d/m'),
                    'presentes' => $count,
                    'ausentes' => $totalEmployees - $count
                ]);
            }

            return view('dashboard.admin', compact('totalEmployees', 'todayLogs', 'chartData'));
        }

        $employee = $user->employee;
        $todayLog = null;
        $recentLogs = collect();
        $chartData = collect();
        $profileCompletion = [];

        if ($employee) {
            $todayLog = $employee->todayLog;
            $recentLogs = $employee->workLogs()
                ->orderBy('work_date', 'desc')
                ->limit(7)
                ->get();

            // Otimização: buscar os logs dos últimos 7 dias em apenas UMA query
            $startDate = today()->subDays(6);
            $weekLogs = $employee->workLogs()
                ->whereDate('work_date', '>=', $startDate)
                ->get()
                ->keyBy(fn($l) => $l->work_date->toDateString());

            for ($i = 6; $i >= 0; $i--) {
                $date = today()->subDays($i);
                $log = $weekLogs->get($date->toDateString());
                $chartData->push([
                    'date' => $date->format('d/m'),
                    'hours' => $log && $log->total_minutes ? round($log->total_minutes / 60, 2) : 0
                ]);
            }

            // Cálculo de completude do perfil
            $fields = [
                ['field' => 'name',    'label' => 'Nome',     'value' => $user->name],
                ['field' => 'email',   'label' => 'E-mail',   'value' => $user->email],
                ['field' => 'avatar',  'label' => 'Foto',     'value' => $user->avatar],
                ['field' => 'cpf',     'label' => 'CPF',      'value' => $employee->cpf],
                ['field' => 'rg',      'label' => 'RG',       'value' => $employee->rg],
                ['field' => 'birth_date','label'=> 'Nascimento', 'value' => $employee->birth_date],
                ['field' => 'gender',  'label' => 'Gênero',   'value' => $employee->gender !== 'not_specified' ? $employee->gender : null],
                ['field' => 'marital_status', 'label' => 'Estado Civil', 'value' => $employee->marital_status],
                ['field' => 'phone',   'label' => 'Telefone', 'value' => $employee->phone],
                ['field' => 'address', 'label' => 'Endereço', 'value' => $employee->address],
                ['field' => 'emergency_contact_name',  'label' => 'Contato Emerg.', 'value' => $employee->emergency_contact_name],
                ['field' => 'emergency_contact_phone', 'label' => 'Tel. Emerg.', 'value' => $employee->emergency_contact_phone],
            ];

            $total = count($fields);
            $filled = collect($fields)->filter(fn($f) => !empty($f['value']))->count();
            $percentage = $total > 0 ? round(($filled / $total) * 100) : 0;

            $profileCompletion = [
                'fields' => $fields,
                'filled' => $filled,
                'total' => $total,
                'percentage' => $percentage,
            ];
        }

        return view('dashboard.employee', compact('todayLog', 'recentLogs', 'chartData', 'profileCompletion'));
    }
}