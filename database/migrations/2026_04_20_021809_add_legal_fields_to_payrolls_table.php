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
        Schema::table('payrolls', function (Blueprint $table) {
            $table->decimal('dsr_value', 10, 2)->default(0)->after('overtime_value');
            $table->decimal('inss_value', 10, 2)->default(0)->after('gross_total');
            $table->decimal('fgts_value', 10, 2)->default(0)->after('inss_value');
            $table->decimal('net_total', 10, 2)->default(0)->after('fgts_value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            //
        });
    }
};
