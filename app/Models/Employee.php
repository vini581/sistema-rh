<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'cpf',
        'position',
        'address',
        'admission_date',
    ];

    protected function casts(): array
    {
        return [
            'admission_date' => 'date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function workLogs()
    {
        return $this->hasMany(WorkLog::class);
    }

    public function todayLog()
    {
        return $this->hasOne(WorkLog::class)->whereDate('work_date', today());
    }
}