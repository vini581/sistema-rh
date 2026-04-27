<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    protected $fillable = ['date', 'name'];

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    public static function isHoliday(\Carbon\Carbon|string $date): bool
    {
        $date = is_string($date) ? $date : $date->format('Y-m-d');
        return static::whereDate('date', $date)->exists();
    }
}
