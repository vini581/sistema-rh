<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class VacationRequest extends Model
{
    protected $fillable = [
        'employee_id', 'start_date', 'days', 'status', 'forecast_value', 'response_notes'
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'forecast_value' => 'integer',
        ];
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Data final das férias (calculada a partir de start_date + days).
     */
    public function getEndDateAttribute(): Carbon
    {
        return Carbon::parse($this->start_date)->addDays($this->days - 1);
    }

    /**
     * Verifica se uma data específica está coberta por estas férias.
     */
    public function coversDate(Carbon $date): bool
    {
        return $date->between(Carbon::parse($this->start_date), $this->end_date);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending'  => 'Pendente',
            'approved' => 'Aprovada',
            'rejected' => 'Recusada',
            default    => $this->status,
        };
    }
}
