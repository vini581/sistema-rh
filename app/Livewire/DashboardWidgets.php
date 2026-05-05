<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Employee;
use App\Models\VacationRequest;
use App\Models\MedicalCertificate;
use App\Models\Holiday;

class DashboardWidgets extends Component
{
    public function render()
    {
        $stats = [
            'employees_count' => Employee::count(),
            'pending_vacations' => VacationRequest::where('status', 'pending')->count(),
            'pending_certificates' => MedicalCertificate::where('status', 'pending')->count(),
            'upcoming_holidays' => Holiday::where('date', '>=', now())->orderBy('date', 'asc')->take(3)->get()
        ];

        return view('livewire.dashboard-widgets', [
            'stats' => $stats
        ]);
    }
}
