<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_configs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id')->unique();
            $table->decimal('hourly_rate', 8, 2)->nullable();
            $table->unsignedInteger('monthly_hours')->nullable();
            $table->enum('payment_type', ['monthly', 'biweekly'])->nullable();
            $table->unsignedInteger('overtime_weekday_pct')->nullable();
            $table->unsignedInteger('overtime_saturday_pct')->nullable();
            $table->unsignedInteger('overtime_sunday_pct')->nullable();
            $table->unsignedInteger('overtime_holiday_pct')->nullable();
            $table->decimal('vacation_bonus_pct', 5, 2)->nullable();
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_configs');
    }
};
