<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'work_date',
        'clock_in',
        'lunch_out',
        'lunch_in',
        'clock_out',
        'total_minutes',
    ];

    protected function casts(): array
    {
        return [
            'work_date' => 'date',
            'clock_in'  => 'datetime',
            'lunch_out' => 'datetime',
            'lunch_in'  => 'datetime',
            'clock_out' => 'datetime',
        ];
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function getNextAction(): ?string
    {
        if (!$this->clock_in)  return 'clock_in';
        if (!$this->lunch_out) return 'lunch_out';
        if (!$this->lunch_in)  return 'lunch_in';
        if (!$this->clock_out) return 'clock_out';
        return null;
    }

    public function calculateTotalMinutes(): int
    {
        if (!$this->clock_out || !$this->clock_in) return 0;

        $start = $this->clock_in;
        $end   = $this->clock_out;

        if ($start->gt($end)) return 0;

        $total = $start->diffInMinutes($end);

        if ($this->lunch_out && $this->lunch_in) {
            $lStart = $this->lunch_out;
            $lEnd   = $this->lunch_in;
            
            if ($lEnd->gt($lStart) && $lStart->gt($start) && $lEnd->lt($end)) {
                $total -= $lStart->diffInMinutes($lEnd);
            }
        }

        return (int) max(0, $total);
    }

    public function getFormattedHoursAttribute(): string
    {
        $minutes = $this->total_minutes ?? 0;
        $h = intdiv($minutes, 60);
        $m = $minutes % 60;
        return sprintf('%02d:%02d', $h, $m);
    }

    public function hourBank()
    {
        return $this->hasOne(HourBank::class);
    }
}