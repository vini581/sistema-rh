<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hour_bank', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('work_log_id');
            $table->integer('balance_minutes');
            $table->date('reference_date');
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees')->cascadeOnDelete();
            $table->foreign('work_log_id')->references('id')->on('work_logs')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hour_bank');
    }
};