<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Employee;
use App\Models\Payroll;
use App\Models\MedicalCertificate;
use Illuminate\Support\Facades\Auth;

class CommandPalette extends Component
{
    public $search = '';
    public $isOpen = false;

    public function render()
    {
        $results = [];

        if (strlen($this->search) > 0) {
            $user = Auth::user();
            $isAdmin = $user && $user->isAdmin();

            // Busca em funcionários (admin)
            if ($isAdmin) {
                $employees = Employee::with('user')
                    ->whereHas('user', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    })
                    ->orWhere('position', 'like', '%' . $this->search . '%')
                    ->take(5)
                    ->get();
                
                foreach ($employees as $emp) {
                    $results[] = [
                        'type' => 'Funcionário',
                        'title' => $emp->user->name ?? 'Sem Nome',
                        'subtitle' => $emp->position,
                        'url' => route('employees.index'),
                        'icon' => 'user',
                    ];
                }

                // Busca em holerites (admin)
                $payrolls = Payroll::with('employee.user')
                    ->where('reference_month', 'like', '%' . $this->search . '%')
                    ->take(5)
                    ->get();

                foreach ($payrolls as $payroll) {
                    $results[] = [
                        'type' => 'Holerite',
                        'title' => 'Folha ' . $payroll->reference_month,
                        'subtitle' => $payroll->employee?->user?->name ?? 'Funcionário',
                        'url' => route('payroll.show', $payroll),
                        'icon' => 'money',
                    ];
                }

                // Busca em atestados (admin)
                $certificates = MedicalCertificate::with('employee.user')
                    ->where(function ($q) {
                        $q->whereHas('employee.user', function ($sub) {
                            $sub->where('name', 'like', '%' . $this->search . '%');
                        })
                        ->orWhere('type', 'like', '%' . $this->search . '%')
                        ->orWhere('observations', 'like', '%' . $this->search . '%');
                    })
                    ->take(5)
                    ->get();

                foreach ($certificates as $cert) {
                    $typeLabel = match($cert->type) {
                        'medical' => 'Médico',
                        'dental' => 'Odontológico',
                        'attendance' => 'Comparecimento',
                        'work_accident' => 'Acidente de Trabalho',
                        default => $cert->type,
                    };
                    $results[] = [
                        'type' => 'Atestado',
                        'title' => $typeLabel . ' — ' . ($cert->employee?->user?->name ?? ''),
                        'subtitle' => $cert->start_date->format('d/m/Y') . ' a ' . $cert->end_date->format('d/m/Y'),
                        'url' => route('certificates.index'),
                        'icon' => 'document',
                    ];
                }
            }

            // Busca em holerites próprios (funcionário)
            if (!$isAdmin && $user?->employee) {
                $myPayrolls = Payroll::where('employee_id', $user->employee->id)
                    ->where('reference_month', 'like', '%' . $this->search . '%')
                    ->take(5)
                    ->get();

                foreach ($myPayrolls as $payroll) {
                    $results[] = [
                        'type' => 'Meu Contracheque',
                        'title' => 'Contracheque ' . $payroll->reference_month,
                        'subtitle' => 'R$ ' . number_format($payroll->net_total / 100, 2, ',', '.'),
                        'url' => route('employee.payroll.show', $payroll),
                        'icon' => 'money',
                    ];
                }
            }

            // Ações rápidas — montar com base no papel do usuário
            $actions = [
                ['title' => 'Dashboard Inicial', 'url' => route('dashboard'), 'type' => 'Ação Rápida', 'icon' => 'home'],
            ];

            if ($isAdmin) {
                $actions = array_merge($actions, [
                    ['title' => 'Gestão de Funcionários', 'url' => route('employees.index'), 'type' => 'Ação Rápida', 'icon' => 'users'],
                    ['title' => 'Folha de Pagamento', 'url' => route('payroll.index'), 'type' => 'Ação Rápida', 'icon' => 'money'],
                    ['title' => 'Atestados Médicos', 'url' => route('certificates.index'), 'type' => 'Ação Rápida', 'icon' => 'document'],
                    ['title' => 'Configurações do RH', 'url' => route('hr-config.index'), 'type' => 'Ação Rápida', 'icon' => 'settings'],
                    ['title' => 'Relatórios', 'url' => route('reports.index'), 'type' => 'Ação Rápida', 'icon' => 'chart'],
                ]);
            } else {
                $actions = array_merge($actions, [
                    ['title' => 'Previsão Salarial', 'url' => route('employee.dashboard'), 'type' => 'Ação Rápida', 'icon' => 'chart'],
                    ['title' => 'Bater Ponto', 'url' => route('work-log.index'), 'type' => 'Ação Rápida', 'icon' => 'clock'],
                    ['title' => 'Meus Contracheques', 'url' => route('employee.payroll.index'), 'type' => 'Ação Rápida', 'icon' => 'money'],
                    ['title' => 'Minhas Férias', 'url' => route('employee.vacations.index'), 'type' => 'Ação Rápida', 'icon' => 'calendar'],
                    ['title' => 'Meus Atestados', 'url' => route('employee.certificates.index'), 'type' => 'Ação Rápida', 'icon' => 'document'],
                    ['title' => 'Meu Perfil', 'url' => route('profile.edit'), 'type' => 'Ação Rápida', 'icon' => 'user'],
                ]);
            }

            foreach ($actions as $action) {
                if (stripos($action['title'], $this->search) !== false) {
                    $results[] = $action;
                }
            }
        }

        return view('livewire.command-palette', [
            'results' => $results
        ]);
    }
}
