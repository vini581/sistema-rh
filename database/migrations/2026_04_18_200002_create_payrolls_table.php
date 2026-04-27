<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->date('reference_month');
            $table->enum('period_type', ['monthly', 'biweekly_1', 'biweekly_2'])->default('monthly');
            $table->unsignedInteger('days_worked')->default(0);
            $table->integer('normal_minutes')->default(0);
            $table->integer('overtime_minutes')->default(0);
            $table->unsignedInteger('certificate_days')->default(0);
            $table->unsignedInteger('certificate_excused_days')->default(0);
            $table->unsignedInteger('certificate_deducted_days')->default(0);
            $table->decimal('normal_value', 10, 2)->default(0);
            $table->decimal('overtime_value', 10, 2)->default(0);
            $table->decimal('vacation_value', 10, 2)->default(0);
            $table->decimal('gross_total', 10, 2)->default(0);
            $table->decimal('biweekly_1_value', 10, 2)->nullable();
            $table->decimal('biweekly_2_value', 10, 2)->nullable();
            $table->enum('status', ['draft', 'calculated', 'paid'])->default('draft');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees')->cascadeOnDelete();
            $table->unique(['employee_id', 'reference_month', 'period_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
