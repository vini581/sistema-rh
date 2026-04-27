<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\VacationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VacationController extends Controller
{
    public function index()
    {
        $employee = Auth::user()->employee;

        if (!$employee) {
            return redirect('/dashboard')->with('error', 'Perfil de funcionário não encontrado.');
        }

        $requests = VacationRequest::where('employee_id', $employee->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('employee.vacations.index', compact('requests', 'employee'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date|after:today',
            'days'       => 'required|integer|min:1|max:30',
        ]);

        $employee = Auth::user()->employee;

        if (!$employee) {
            return redirect('/dashboard')->with('error', 'Perfil de funcionário não encontrado.');
        }

        // Evitar duplicidade ou acúmulo de requisições pendentes
        $hasPending = VacationRequest::where('employee_id', $employee->id)
            ->where('status', 'pending')
            ->exists();

        if ($hasPending) {
            return redirect()->route('employee.vacations.index')
                ->with('error', 'Você já tem um pedido de férias em análise. Aguarde o RH responder.');
        }

        $startDate = \Carbon\Carbon::parse($validated['start_date']);
        $endDate = $startDate->copy()->addDays($validated['days'] - 1);

        // Evitar sobreposição com férias já aprovadas
        $hasConflict = VacationRequest::where('employee_id', $employee->id)
            ->where('status', 'approved')
            ->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('start_date', [$startDate, $endDate])
                  ->orWhereRaw('DATE_ADD(start_date, INTERVAL days - 1 DAY) BETWEEN ? AND ?', [$startDate, $endDate])
                  ->orWhere(function ($q2) use ($startDate, $endDate) {
                      $q2->where('start_date', '<=', $startDate)
                         ->whereRaw('DATE_ADD(start_date, INTERVAL days - 1 DAY) >= ?', [$endDate]);
                  });
            })->exists();

        if ($hasConflict) {
            return redirect()->route('employee.vacations.index')
                ->with('error', 'Este período entra em conflito com outras férias já aprovadas.');
        }

        // hourly_rate já vem em centavos
        $hourlyRate = (int) $employee->getConfig('hourly_rate');
        $monthlyHours = (int) $employee->getConfig('monthly_hours');

        // Salário mensal em centavos
        $monthlySalary = $monthlyHours * $hourlyRate;

        $vacationBonusPct = (float) $employee->getConfig('vacation_bonus_pct');

        // forecast_value em centavos
        $estimatedValue = (int) round(($monthlySalary / 30 * $validated['days']) * (1 + $vacationBonusPct / 100));

        VacationRequest::create([
            'employee_id'     => $employee->id,
            'start_date'      => $validated['start_date'],
            'days'            => $validated['days'],
            'forecast_value'  => $estimatedValue,
            'status'          => 'pending',
        ]);

        return redirect()->route('employee.vacations.index')
            ->with('success', 'Pronto! Seu pedido de férias foi enviado e está em análise.');
    }
}
