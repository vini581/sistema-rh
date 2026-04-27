<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hr_configs', function (Blueprint $table) {
            if (Schema::hasColumn('hr_configs', 'inss_table')) {
                $table->dropColumn('inss_table');
            }
            if (Schema::hasColumn('hr_configs', 'irrf_table')) {
                $table->dropColumn('irrf_table');
            }
        });
    }

    public function down(): void
    {
        Schema::table('hr_configs', function (Blueprint $table) {
            $table->json('inss_table')->nullable();
            $table->json('irrf_table')->nullable();
        });
    }
};
