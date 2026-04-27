<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\WorkSchedule;
use Illuminate\Http\Request;

class WorkScheduleController extends Controller
{
    public function edit(Employee $employee)
    {
        $schedule = $employee->workSchedule ?? new WorkSchedule([
            'clock_in_time'      => '08:00',
            'lunch_out_time'     => '12:00',
            'lunch_in_time'      => '13:00',
            'clock_out_time'     => '17:00',
            'tolerance_minutes'  => 10,
            'work_hours_per_day' => 8,
        ]);

        return view('work-schedule.edit', compact('employee', 'schedule'));
    }

    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'clock_in_time'      => 'required',
            'lunch_out_time'     => 'required',
            'lunch_in_time'      => 'required',
            'clock_out_time'     => 'required',
            'tolerance_minutes'  => 'required|integer|min:0|max:60',
            'work_hours_per_day' => 'required|integer|min:1|max:12',
        ]);

        $oldHours = $employee->workSchedule?->work_hours_per_day;

        WorkSchedule::updateOrCreate(
            ['employee_id' => $employee->id],
            $request->only([
                'clock_in_time',
                'lunch_out_time',
                'lunch_in_time',
                'clock_out_time',
                'tolerance_minutes',
                'work_hours_per_day',
            ])
        );

        if ($oldHours !== null && $oldHours !== (int) $request->work_hours_per_day) {
            $employee->refresh();
            $employee->recalculateHourBank();
        }

        return redirect()->route('employees.index')
            ->with('success', 'Pronto! A jornada de trabalho foi ajustada.');
    }
}