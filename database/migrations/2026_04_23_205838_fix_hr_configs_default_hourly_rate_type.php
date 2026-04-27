<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Converter valores existentes para centavos (se já não estiverem)
        // Se a coluna ainda for decimal e guardava 15.00 (que significa R$ 15,00)
        DB::statement("UPDATE hr_configs SET default_hourly_rate = default_hourly_rate * 100");

        // 2. Mudar tipo da coluna para bigInteger
        Schema::table('hr_configs', function (Blueprint $table) {
            $table->bigInteger('default_hourly_rate')->default(0)->change();
        });
    }

    public function down(): void
    {
        Schema::table('hr_configs', function (Blueprint $table) {
            $table->decimal('default_hourly_rate', 8, 2)->default(15.00)->change();
        });

        DB::statement("UPDATE hr_configs SET default_hourly_rate = default_hourly_rate / 100");
    }
};
