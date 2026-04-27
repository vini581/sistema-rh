<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeConfig extends Model
{
    protected $fillable = [
        'employee_id', 'hourly_rate', 'monthly_hours', 'payment_type',
        'overtime_weekday_pct', 'overtime_saturday_pct',
        'overtime_sunday_pct', 'overtime_holiday_pct',
        'vacation_bonus_pct',
    ];

    protected function casts(): array
    {
        return [
            'hourly_rate'       => 'integer',
            'vacation_bonus_pct' => 'decimal:2',
        ];
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function getEffective(string $field, $date = null): mixed
    {
        if ($this->$field !== null) {
            return $this->$field;
        }

        $global = HrConfig::forDate($date ?? now());

        $map = [
            'hourly_rate'          => 'default_hourly_rate',
            'monthly_hours'        => 'monthly_hours',
            'payment_type'         => 'payment_type',
            'overtime_weekday_pct' => 'overtime_weekday_pct',
            'overtime_saturday_pct' => 'overtime_saturday_pct',
            'overtime_sunday_pct'  => 'overtime_sunday_pct',
            'overtime_holiday_pct' => 'overtime_holiday_pct',
            'vacation_bonus_pct'   => 'vacation_bonus_pct',
        ];

        $globalField = $map[$field] ?? $field;
        return $global->$globalField;
    }
}
