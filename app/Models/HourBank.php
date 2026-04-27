<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HourBank extends Model
{
    protected $table = 'hour_bank';

    protected $fillable = [
        'employee_id',
        'work_log_id',
        'balance_minutes',
        'reference_date',
    ];

    protected function casts(): array
    {
        return [
            'reference_date' => 'date',
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

    public function getFormattedBalanceAttribute(): string
    {
        $minutes = abs($this->balance_minutes);
        $h = intdiv($minutes, 60);
        $m = $minutes % 60;
        $signal = $this->balance_minutes >= 0 ? '+' : '-';
        return sprintf('%s%02d:%02d', $signal, $h, $m);
    }
}