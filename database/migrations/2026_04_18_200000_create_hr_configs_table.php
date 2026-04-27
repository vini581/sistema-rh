<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hr_configs', function (Blueprint $table) {
            $table->id();

            // Valores e carga horária
            $table->decimal('default_hourly_rate', 8, 2)->default(15.00);
            $table->unsignedInteger('monthly_hours')->default(220);

            // Pagamento
            $table->enum('payment_type', ['monthly', 'biweekly'])->default('monthly');

            // Horas extras
            $table->unsignedInteger('overtime_weekday_pct')->default(50);
            $table->unsignedInteger('overtime_saturday_pct')->default(50);
            $table->unsignedInteger('overtime_sunday_pct')->default(100);
            $table->unsignedInteger('overtime_holiday_pct')->default(100);
            $table->unsignedInteger('overtime_min_minutes')->default(0);
            $table->boolean('saturday_is_overtime')->default(false);

            // Banco de horas
            $table->boolean('use_hour_bank')->default(true);

            // Quinzenas
            $table->unsignedTinyInteger('biweekly_first_start')->default(1);
            $table->unsignedTinyInteger('biweekly_first_end')->default(15);
            $table->unsignedTinyInteger('biweekly_second_start')->default(16);
            $table->unsignedTinyInteger('biweekly_second_end')->default(31);
            $table->unsignedTinyInteger('biweekly_first_pct')->default(40);
            $table->unsignedTinyInteger('biweekly_second_pct')->default(60);

            // Férias
            $table->decimal('vacation_bonus_pct', 5, 2)->default(33.33);
            $table->boolean('vacation_include_overtime_avg')->default(false);
            $table->boolean('vacation_allow_split')->default(true);

            // Atestados
            $table->boolean('certificate_excuses_absence')->default(true);
            $table->boolean('certificate_counts_as_worked')->default(true);
            $table->unsignedInteger('certificate_discount_after_days')->default(0);
            $table->boolean('certificate_discount_transport')->default(false);
            $table->boolean('certificate_discount_food')->default(false);
            $table->unsignedInteger('certificate_company_paid_days')->default(15);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hr_configs');
    }
};
