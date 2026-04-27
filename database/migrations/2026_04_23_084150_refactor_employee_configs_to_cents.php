<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Converter valores existentes para centavos
        DB::statement("UPDATE employee_configs SET hourly_rate = hourly_rate * 100");

        // 2. Mudar tipo da coluna
        Schema::table('employee_configs', function (Blueprint $table) {
            $table->bigInteger('hourly_rate')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('employee_configs', function (Blueprint $table) {
            $table->decimal('hourly_rate', 10, 2)->nullable()->change();
        });

        DB::statement("UPDATE employee_configs SET hourly_rate = hourly_rate / 100");
    }
};
