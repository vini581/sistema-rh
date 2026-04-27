<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkSchedule extends Model
{
    protected $fillable = [
        'employee_id',
        'clock_in_time',
        'lunch_out_time',
        'lunch_in_time',
        'clock_out_time',
        'tolerance_minutes',
        'work_hours_per_day',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}