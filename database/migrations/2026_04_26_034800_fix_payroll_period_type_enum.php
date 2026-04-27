<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Altera o enum para aceitar 'biweekly' além dos valores antigos
        DB::statement("ALTER TABLE payrolls MODIFY COLUMN period_type ENUM('monthly', 'biweekly', 'biweekly_1', 'biweekly_2') DEFAULT 'monthly'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE payrolls MODIFY COLUMN period_type ENUM('monthly', 'biweekly_1', 'biweekly_2') DEFAULT 'monthly'");
    }
};
