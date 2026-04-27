<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    protected $fillable = [
        'employee_id', 'reference_month', 'period_type',
        'worked_hours', 'gross_total', 'deductions', 'deduction_notes', 'net_total',
        'status', 'closed_at', 'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'reference_month' => 'date',
            'closed_at'       => 'datetime',
            'paid_at'         => 'datetime',
            'worked_hours'    => 'integer',
            'gross_total'     => 'integer',
            'deductions'      => 'integer',
            'net_total'       => 'integer',
        ];
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }



    /**
     * Fecha a folha para edições.
     */
    public function close()
    {
        if (in_array($this->status, ['closed', 'paid'])) {
            return;
        }

        $this->status = 'closed';
        $this->closed_at = now();
        $this->save();
    }

    /**
     * Formata minutos em HH:MM.
     */
    public function formatMinutes(int $minutes): string
    {
        $h = intdiv(abs($minutes), 60);
        $m = abs($minutes) % 60;
        return sprintf('%d:%02d', $h, $m);
    }

    public function getFormattedWorkedHoursAttribute(): string
    {
        return $this->formatMinutes($this->worked_hours);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft'      => 'Rascunho',
            'calculated' => 'Calculado',
            'closed'     => 'Fechado',
            'paid'       => 'Pago',
            default      => $this->status,
        };
    }
}
