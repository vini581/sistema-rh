<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HrConfig extends Model
{
    protected $fillable = [
        'employee_id', 'vigencia_inicio',
        'default_hourly_rate', 'monthly_hours', 'payment_type',
        'overtime_weekday_pct', 'overtime_saturday_pct', 'overtime_sunday_pct',
        'overtime_holiday_pct', 'night_shift_pct', 'overtime_min_minutes', 'saturday_is_overtime',
        'use_hour_bank',
        'biweekly_first_start', 'biweekly_first_end',
        'biweekly_second_start', 'biweekly_second_end',
        'biweekly_first_pct', 'biweekly_second_pct',
        'vacation_bonus_pct', 'vacation_include_overtime_avg', 'vacation_allow_split',
        'certificate_excuses_absence', 'certificate_counts_as_worked',
        'certificate_discount_after_days', 'certificate_discount_transport',
        'certificate_discount_food', 'certificate_company_paid_days',
        'fixed_discount_pct',
    ];

    protected function casts(): array
    {
        return [
            'vigencia_inicio'        => 'date',
            'default_hourly_rate'    => 'integer',
            'vacation_bonus_pct'     => 'decimal:2',
            'saturday_is_overtime'   => 'boolean',
            'use_hour_bank'          => 'boolean',
            'vacation_include_overtime_avg' => 'boolean',
            'vacation_allow_split'   => 'boolean',
            'certificate_excuses_absence'   => 'boolean',
            'certificate_counts_as_worked'  => 'boolean',
            'certificate_discount_transport' => 'boolean',
            'certificate_discount_food'      => 'boolean',
        ];
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Cache in-memory para evitar queries repetidas em processamentos em lote.
     */
    protected static $runtimeCache = [];

    /**
     * Busca a vigência correta para uma data.
     * Prioridade: funcionário específico → global (employee_id = null).
     */
    public static function forDate($date, ?int $employeeId = null): self
    {
        $targetDate = \Carbon\Carbon::parse($date)->endOfMonth();
        $cacheKey = ($employeeId ?? 'global') . '_' . $targetDate->format('Y-m');

        if (isset(self::$runtimeCache[$cacheKey])) {
            return self::$runtimeCache[$cacheKey];
        }

        if ($employeeId) {
            $specific = static::where('employee_id', $employeeId)
                ->where('vigencia_inicio', '<=', $targetDate)
                ->orderBy('vigencia_inicio', 'desc')
                ->first();

            if ($specific) {
                return self::$runtimeCache[$cacheKey] = $specific;
            }
        }

        // Fallback: vigência global
        $globalCacheKey = 'global_' . $targetDate->format('Y-m');
        if (isset(self::$runtimeCache[$globalCacheKey])) {
            return self::$runtimeCache[$cacheKey] = self::$runtimeCache[$globalCacheKey];
        }

        $global = static::whereNull('employee_id')
            ->where('vigencia_inicio', '<=', $targetDate)
            ->orderBy('vigencia_inicio', 'desc')
            ->first();

        $result = $global ?? static::defaults();
        self::$runtimeCache[$globalCacheKey] = $result;
        return self::$runtimeCache[$cacheKey] = $result;
    }

    /**
     * Vigência global mais recente.
     */
    public static function current(): self
    {
        return static::whereNull('employee_id')
            ->orderBy('vigencia_inicio', 'desc')
            ->first() ?? static::defaults();
    }

    /**
     * Valores padrão quando não existe nenhuma vigência.
     */
    public static function defaults(): self
    {
        return new static([
            'vigencia_inicio'     => now()->startOfMonth(),
            'default_hourly_rate' => 0,
            'monthly_hours'       => 220,
            'payment_type'        => 'monthly',
            'overtime_weekday_pct'  => 50,
            'overtime_saturday_pct' => 50,
            'overtime_sunday_pct'   => 100,
            'overtime_holiday_pct'  => 100,
            'night_shift_pct'       => 20,
            'overtime_min_minutes'  => 0,
            'saturday_is_overtime'  => false,
            'use_hour_bank'         => true,
            'biweekly_first_start'  => 1,
            'biweekly_first_end'    => 15,
            'biweekly_second_start' => 16,
            'biweekly_second_end'   => 31,
            'biweekly_first_pct'    => 40,
            'biweekly_second_pct'   => 60,
            'vacation_bonus_pct'    => 33.33,
            'vacation_include_overtime_avg' => false,
            'vacation_allow_split'  => true,
            'certificate_excuses_absence'  => true,
            'certificate_counts_as_worked' => true,
            'certificate_discount_after_days' => 0,
            'certificate_discount_transport' => false,
            'certificate_discount_food'      => false,
            'certificate_company_paid_days' => 15,
        ]);
    }
}
