<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('overtime_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('work_log_id');
            $table->unsignedBigInteger('payroll_id')->nullable();
            $table->date('date');
            $table->enum('type', ['weekday', 'saturday', 'sunday', 'holiday']);
            $table->unsignedInteger('minutes');
            $table->unsignedInteger('percentage');
            $table->decimal('value', 10, 2)->default(0);
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees')->cascadeOnDelete();
            $table->foreign('work_log_id')->references('id')->on('work_logs')->cascadeOnDelete();
            $table->foreign('payroll_id')->references('id')->on('payrolls')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('overtime_logs');
    }
};
