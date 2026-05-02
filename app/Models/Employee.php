<?php

namespace App\Models;

use App\Services\CalendarService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'cpf',
        'position',
        'address',
        'admission_date',
        'hourly_rate',
        'base_salary',
    ];

    protected function casts(): array
    {
        return [
            'admission_date' => 'date',
            'hourly_rate'    => 'integer',
            'base_salary'    => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function workLogs(): HasMany
    {
        return $this->hasMany(WorkLog::class);
    }

    public function todayLog(): HasOne
    {
        return $this->hasOne(WorkLog::class)->whereDate('work_date', today());
    }

    public function workSchedule(): HasOne
    {
        return $this->hasOne(WorkSchedule::class);
    }

    public function hourBank(): HasMany
    {
        return $this->hasMany(HourBank::class);
    }

    public function config(): HasOne
    {
        return $this->hasOne(EmployeeConfig::class);
    }

    /**
     * Vigências específicas deste funcionário.
     */
    public function hrConfigs(): HasMany
    {
        return $this->hasMany(HrConfig::class)->orderBy('vigencia_inicio', 'desc');
    }

    public function payrolls(): HasMany
    {
        return $this->hasMany(Payroll::class);
    }

    public function overtimeLogs(): HasMany
    {
        return $this->hasMany(OvertimeLog::class);
    }

    public function medicalCertificates(): HasMany
    {
        return $this->hasMany(MedicalCertificate::class);
    }

    public function vacationRequests(): HasMany
    {
        return $this->hasMany(VacationRequest::class);
    }

    public function getTotalHourBankMinutesAttribute(): int
    {
        return (int) $this->hourBank()->sum('balance_minutes');
    }

    /**
     * Resolve valor de configuração via vigência (funcionário → global).
     */
    public function getConfig(string $field, $date = null): mixed
    {
        $config = HrConfig::forDate($date ?? now(), $this->id);

        $map = [
            'hourly_rate'           => 'default_hourly_rate',
            'monthly_hours'         => 'monthly_hours',
            'payment_type'          => 'payment_type',
            'overtime_weekday_pct'  => 'overtime_weekday_pct',
            'overtime_saturday_pct' => 'overtime_saturday_pct',
            'overtime_sunday_pct'   => 'overtime_sunday_pct',
            'overtime_holiday_pct'  => 'overtime_holiday_pct',
            'night_shift_pct'       => 'night_shift_pct',
            'overtime_min_minutes'  => 'overtime_min_minutes',
            'vacation_bonus_pct'    => 'vacation_bonus_pct',
        ];

        $configField = $map[$field] ?? $field;

        return $config->getAttribute($configField) ?? match($field) {
            'monthly_hours' => 220,
            'payment_type'  => 'monthly',
            'hourly_rate'   => 0,
            default         => null
        };
    }

    public function recalculateHourBank(?WorkLog $specificLog = null): void
    {
        $schedule = $this->workSchedule;
        
        // Eager load todos os atestados abonados válidos para processamento em memória
        $certificates = $this->medicalCertificates()
            ->where('status', 'approved')
            ->where('excused', true)
            ->get();

        if ($schedule) {
            $expectedMinutes = $schedule->work_hours_per_day * 60;
            $tolerance       = (int) ($schedule->tolerance_minutes ?? 0);

            if ($specificLog) {
                $this->processLogForHourBank($specificLog, $expectedMinutes, $tolerance, $certificates);
                return;
            }

            DB::transaction(function () use ($expectedMinutes, $tolerance, $certificates) {
                $this->workLogs()
                    ->whereNotNull('clock_out')
                    ->whereNotNull('total_minutes')
                    ->chunkById(100, function ($logs) use ($expectedMinutes, $tolerance, $certificates) {
                        foreach ($logs as $log) {
                            $this->processLogForHourBank($log, $expectedMinutes, $tolerance, $certificates);
                        }
                    });
            });
            return;
        }

        // Sem jornada configurada: calcula com base no mês de cada log
        if ($specificLog) {
            $refDate = $specificLog->work_date;
            $monthlyHours = (int) $this->getConfig('monthly_hours', $refDate);
            $workingDays = CalendarService::getWorkingDaysCount($refDate->year, $refDate->month, $this->id);
            $expectedMinutes = $workingDays > 0 ? ($monthlyHours / $workingDays) * 60 : 0;
            $this->processLogForHourBank($specificLog, $expectedMinutes, 0, $certificates);
            return;
        }

        DB::transaction(function () use ($certificates) {
            $this->workLogs()
                ->whereNotNull('clock_out')
                ->whereNotNull('total_minutes')
                ->chunkById(100, function ($logs) use ($certificates) {
                    foreach ($logs as $log) {
                        $refDate = $log->work_date;
                        $monthlyHours = (int) $this->getConfig('monthly_hours', $refDate);
                        $workingDays = CalendarService::getWorkingDaysCount($refDate->year, $refDate->month, $this->id);
                        $expectedMinutes = $workingDays > 0 ? ($monthlyHours / $workingDays) * 60 : 0;
                        $this->processLogForHourBank($log, $expectedMinutes, 0, $certificates);
                    }
                });
        });
    }

    protected function processLogForHourBank(WorkLog $log, float $expectedMinutes, int $tolerance, $certificates = null): void
    {
        if (!$log->clock_out || $log->total_minutes === null) return;

        // Finais de semana e feriados não têm expectativa de horas (tudo que for trabalhado é extra)
        if (!CalendarService::isWorkingDay($log->work_date, $this->id)) {
            $expectedMinutes = 0;
        }

        // Se houver atestado médico abonado para este dia, a expectativa de horas é zerada
        $hasCertificate = false;
        if ($certificates) {
            $hasCertificate = $certificates->contains(function ($c) use ($log) {
                return $log->work_date->between(
                    \Carbon\Carbon::parse($c->start_date),
                    \Carbon\Carbon::parse($c->end_date)
                );
            });
        }

        if ($hasCertificate) {
            $expectedMinutes = 0;
        }

        $balance = $log->total_minutes - $expectedMinutes;
        
        // Se o funcionário chegou antes ou na hora, a tolerância não importa. Só damos desconto no saldo se ele atrasar além do combinado.
        if ($balance < 0 && abs($balance) <= $tolerance) {
            $balance = 0;
        }

        HourBank::updateOrCreate(
            ['employee_id' => $this->id, 'work_log_id' => $log->id],
            [
                'balance_minutes' => (int) $balance,
                'reference_date'  => $log->work_date,
            ]
        );
    }
}