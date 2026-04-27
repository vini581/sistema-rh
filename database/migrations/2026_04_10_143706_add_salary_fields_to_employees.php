<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->decimal('hourly_rate', 8, 2)->nullable()->after('admission_date');
            $table->decimal('base_salary', 10, 2)->nullable()->after('hourly_rate');
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['hourly_rate', 'base_salary']);
        });
    }
};