<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
