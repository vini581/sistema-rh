<?php

namespace App\Services;

use App\Models\Holiday;
use App\Models\HrConfig;
use Carbon\Carbon;

class CalendarService
{
    /**
     * Retorna o número de dias úteis em um mês específico.
     */
    public static function getWorkingDaysCount(int $year, int $month, ?int $employeeId = null): int
    {
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

        return max(1, $count);
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
        if ($date->isSunday()) {
            return false;
        }

        $config = HrConfig::forDate($date, $employeeId);
        if ($date->isSaturday() && $config->saturday_is_overtime) {
            return false;
        }

        if (Holiday::isHoliday($date)) {
            return false;
        }

        return true;
    }
}
