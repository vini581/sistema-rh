<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalCertificate extends Model
{
    protected $fillable = [
        'employee_id', 'start_date', 'end_date', 'days',
        'type', 'file_path', 'observations',
        'status', 'excused', 'deducted',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date'   => 'date',
            'excused'    => 'boolean',
            'deducted'   => 'boolean',
        ];
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'medical'       => 'Médico',
            'dental'        => 'Odontológico',
            'attendance'    => 'Comparecimento',
            'work_accident' => 'Acidente de Trabalho',
            default         => $this->type,
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending'  => 'Pendente',
            'approved' => 'Aprovado',
            'rejected' => 'Recusado',
            default    => $this->status,
        };
    }
}
