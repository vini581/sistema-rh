<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id')->unique();
            $table->time('clock_in_time')->default('08:00:00');
            $table->time('lunch_out_time')->default('12:00:00');
            $table->time('lunch_in_time')->default('13:00:00');
            $table->time('clock_out_time')->default('17:00:00');
            $table->unsignedInteger('tolerance_minutes')->default(10);
            $table->unsignedInteger('work_hours_per_day')->default(8);
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_schedules');
    }
};