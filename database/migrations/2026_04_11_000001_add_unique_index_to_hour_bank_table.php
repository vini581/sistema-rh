<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hour_bank', function (Blueprint $table) {
            $table->unique(['employee_id', 'work_log_id'], 'hour_bank_employee_work_log_unique');
        });
    }

    public function down(): void
    {
        Schema::table('hour_bank', function (Blueprint $table) {
            $table->dropUnique('hour_bank_employee_work_log_unique');
        });
    }
};
