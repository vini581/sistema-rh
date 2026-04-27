<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OvertimeLog extends Model
{
    protected $fillable = [
        'employee_id', 'work_log_id', 'payroll_id',
        'date', 'type', 'minutes', 'percentage', 'value',
    ];

    protected function casts(): array
    {
        return [
            'date'  => 'date',
            'value' => 'integer',
        ];
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function workLog()
    {
        return $this->belongsTo(WorkLog::class);
    }

    public function payroll()
    {
        return $this->belongsTo(Payroll::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'weekday'  => 'Dia Útil',
            'saturday' => 'Sábado',
            'sunday'   => 'Domingo',
            'holiday'  => 'Feriado',
            default    => $this->type,
        };
    }
}
