<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            // Remover campos de cálculos automáticos e detalhes complexos
            $table->dropColumn([
                'dsr_value',
                'inss_value',
                'fgts_value',
                'overtime_value',
                'night_value',
                'vacation_value',
                'biweekly_1_value',
                'biweekly_2_value',
                'overtime_amount',
                'night_shift_amount',
                'dsr_overtime_amount',
                'dsr_night_shift_amount',
                'days_worked',
                'normal_minutes',
                'overtime_minutes',
                'night_minutes',
                'certificate_days',
                'certificate_excused_days',
                'certificate_deducted_days',
                'normal_value'
            ]);
        });

        // Mudar deductions de JSON para bigInteger
        // Dependendo do banco de dados, pode ser necessário dropar e recriar
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropColumn('deductions');
        });

        Schema::table('payrolls', function (Blueprint $table) {
            $table->bigInteger('deductions')->default(0)->after('non_working_days');
            $table->text('deduction_notes')->nullable()->after('deductions');
        });
        
        Schema::dropIfExists('payroll_items');
    }

    public function down(): void
    {
    }
};
