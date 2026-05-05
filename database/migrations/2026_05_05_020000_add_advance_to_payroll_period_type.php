<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE payrolls MODIFY COLUMN period_type ENUM('monthly', 'biweekly', 'biweekly_1', 'biweekly_2', 'advance') DEFAULT 'monthly'");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE payrolls MODIFY COLUMN period_type ENUM('monthly', 'biweekly', 'biweekly_1', 'biweekly_2') DEFAULT 'monthly'");
        }
    }
};
