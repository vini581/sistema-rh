<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            // Novos campos de controle
            $table->bigInteger('base_salary')->default(0)->after('period_type');
            $table->integer('worked_hours')->default(0)->after('base_salary');
            
            // Renomeando/Ajustando para o padrão solicitado
            $table->bigInteger('overtime_amount')->default(0)->after('overtime_minutes');
            $table->bigInteger('night_shift_amount')->default(0)->after('night_minutes');
            
            // DSR Detalhado
            $table->bigInteger('dsr_overtime_amount')->default(0)->after('overtime_amount');
            $table->bigInteger('dsr_night_shift_amount')->default(0)->after('night_shift_amount');
            
            // Dias do mês
            $table->integer('working_days')->default(26)->after('dsr_night_shift_amount');
            $table->integer('non_working_days')->default(4)->after('working_days');

            // JSONs para flexibilidade Sênior
            $table->json('deductions')->nullable()->after('non_working_days');
            $table->json('bonuses')->nullable()->after('deductions');
            $table->json('config_snapshot')->nullable()->after('bonuses');

            // Datas de controle
            $table->timestamp('closed_at')->nullable()->after('status');
            $table->timestamp('paid_at')->nullable()->after('closed_at');
        });

        // Conversão de Decimal para Inteiro (Centavos) para dados existentes
        // Multiplicamos por 100 os campos que já existiam
        DB::statement("UPDATE payrolls SET 
            normal_value = normal_value * 100,
            overtime_value = overtime_value * 100,
            dsr_value = dsr_value * 100,
            night_value = night_value * 100,
            vacation_value = vacation_value * 100,
            gross_total = gross_total * 100,
            inss_value = inss_value * 100,
            fgts_value = fgts_value * 100,
            net_total = net_total * 100
        ");

        // Agora mudamos o tipo da coluna para bigInteger
        Schema::table('payrolls', function (Blueprint $table) {
            $table->bigInteger('normal_value')->default(0)->change();
            $table->bigInteger('overtime_value')->default(0)->change();
            $table->bigInteger('dsr_value')->default(0)->change();
            $table->bigInteger('night_value')->default(0)->change();
            $table->bigInteger('vacation_value')->default(0)->change();
            $table->bigInteger('gross_total')->default(0)->change();
            $table->bigInteger('inss_value')->default(0)->change();
            $table->bigInteger('fgts_value')->default(0)->change();
            $table->bigInteger('net_total')->default(0)->change();
        });
    }

    public function down(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropColumn([
                'base_salary', 'worked_hours', 'overtime_amount', 'night_shift_amount',
                'dsr_overtime_amount', 'dsr_night_shift_amount', 'working_days', 
                'non_working_days', 'deductions', 'bonuses', 'config_snapshot', 
                'closed_at', 'paid_at'
            ]);
        });
    }
};
