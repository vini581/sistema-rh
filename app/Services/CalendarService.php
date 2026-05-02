<?php

namespace App\Services;

use App\Models\Holiday;
use App\Models\HrConfig;
use Carbon\Carbon;

class CalendarService
{
    protected static $runtimeCache = [];

    /**
     * Retorna o número de dias úteis em um mês específico.
     */
    public static function getWorkingDaysCount(int $year, int $month, ?int $employeeId = null): int
    {
        $cacheKey = "count_{$year}_{$month}_{$employeeId}";
        if (isset(self::$runtimeCache[$cacheKey])) {
            return self::$runtimeCache[$cacheKey];
        }

        $start = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $end   = $start->copy()->endOfMonth();

        // Usa a vigência correta para o mês de referência
        $config = HrConfig::forDate($start, $employeeId);
        $saturdayIsOvertime = $config->saturday_is_overtime;
        
        $holidays = Holiday::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->pluck('date')
            ->map(fn($d) => Carbon::parse($d)->format('Y-m-d'))
            ->toArray();

        $count = 0;
        $date = $start->copy();

        while ($date->lte($end)) {
            if (!$date->isSunday()
                && !($date->isSaturday() && $saturdayIsOvertime)
                && !in_array($date->format('Y-m-d'), $holidays)
            ) {
                $count++;
            }
            $date->addDay();
        }

        return self::$runtimeCache[$cacheKey] = max(1, $count);
    }

    /**
     * Retorna o número de dias de descanso (domingos/feriados/sábados se configurado) em um mês.
     */
    public static function getNonWorkingDaysCount(int $year, int $month, ?int $employeeId = null): int
    {
        $start = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $totalDays = $start->daysInMonth;

        return $totalDays - self::getWorkingDaysCount($year, $month, $employeeId);
    }

    /**
     * Verifica se um dia específico é dia útil.
     */
    public static function isWorkingDay(Carbon $date, ?int $employeeId = null): bool
    {
        $dateStr = $date->format('Y-m-d');
        $cacheKey = "is_working_{$dateStr}_{$employeeId}";
        
        if (isset(self::$runtimeCache[$cacheKey])) {
            return self::$runtimeCache[$cacheKey];
        }

        if ($date->isSunday()) {
            return self::$runtimeCache[$cacheKey] = false;
        }

        $config = HrConfig::forDate($date, $employeeId);
        if ($date->isSaturday() && $config->saturday_is_overtime) {
            return self::$runtimeCache[$cacheKey] = false;
        }

        // Cache local de feriados para evitar query única
        $monthKey = "holidays_{$date->year}_{$date->month}";
        if (!isset(self::$runtimeCache[$monthKey])) {
            self::$runtimeCache[$monthKey] = Holiday::whereYear('date', $date->year)
                ->whereMonth('date', $date->month)
                ->pluck('date')
                ->map(fn($d) => Carbon::parse($d)->format('Y-m-d'))
                ->toArray();
        }

        if (in_array($dateStr, self::$runtimeCache[$monthKey])) {
            return self::$runtimeCache[$cacheKey] = false;
        }

        return self::$runtimeCache[$cacheKey] = true;
    }
}
