<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Converter valores existentes para centavos antes de mudar o tipo
        DB::statement("UPDATE employees SET 
            hourly_rate = hourly_rate * 100,
            base_salary = base_salary * 100
        ");

        // 2. Mudar tipo das colunas para bigInteger (Cents Pattern)
        Schema::table('employees', function (Blueprint $table) {
            $table->bigInteger('hourly_rate')->nullable()->default(0)->change();
            $table->bigInteger('base_salary')->nullable()->default(0)->change();
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->decimal('hourly_rate', 10, 2)->nullable()->change();
            $table->decimal('base_salary', 12, 2)->nullable()->change();
        });

        DB::statement("UPDATE employees SET 
            hourly_rate = hourly_rate / 100,
            base_salary = base_salary / 100
        ");
    }
};
