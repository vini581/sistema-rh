<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('hr_configs', function (Blueprint $table) {
            $table->integer('night_shift_pct')->default(20)->after('overtime_holiday_pct');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hr_configs', function (Blueprint $table) {
            //
        });
    }
};
