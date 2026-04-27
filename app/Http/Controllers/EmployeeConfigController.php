<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\HrConfig;
use Illuminate\Http\Request;

class EmployeeConfigController extends Controller
{
    public function edit(Employee $employee)
    {
        $employee->load('user');

        $configs = HrConfig::where('employee_id', $employee->id)
            ->orderBy('vigencia_inicio', 'desc')
            ->get();

        // Para o form de nova vigência, usa a config vigente como base
        $current = HrConfig::forDate(now(), $employee->id);

        return view('employee-config.edit', compact('employee', 'configs', 'current'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'vigencia_inicio'      => 'required|date',
            'default_hourly_rate'  => 'required|numeric|min:0',
            'monthly_hours'        => 'required|integer|min:1|max:744',
            'payment_type'         => 'required|in:monthly,biweekly',
            'overtime_weekday_pct' => 'required|integer|min:0|max:200',
            'overtime_saturday_pct' => 'required|integer|min:0|max:200',
            'overtime_sunday_pct'  => 'required|integer|min:0|max:200',
            'overtime_holiday_pct' => 'required|integer|min:0|max:200',
            'night_shift_pct'      => 'required|integer|min:0|max:100',
            'overtime_min_minutes' => 'required|integer|min:0|max:120',
            'vacation_bonus_pct'   => 'required|numeric|min:0|max:100',
        ]);

        $validated['default_hourly_rate'] = (int) round($validated['default_hourly_rate'] * 100);
        $validated['employee_id'] = $employee->id;

        // Se já existe vigência com mesma data para este funcionário, atualiza
        $existing = HrConfig::where('employee_id', $employee->id)
            ->where('vigencia_inicio', $validated['vigencia_inicio'])
            ->first();

        if ($existing) {
            $existing->fill($validated)->save();
        } else {
            HrConfig::create($validated);
        }

        return redirect()->route('employee-config.edit', $employee)
            ->with('success', 'Vigência salva para ' . $employee->user->name . '.');
    }

    public function destroy(Employee $employee, HrConfig $config)
    {
        if ($config->employee_id !== $employee->id) {
            abort(403);
        }

        $config->delete();

        return redirect()->route('employee-config.edit', $employee)
            ->with('success', 'Vigência removida.');
    }
}
