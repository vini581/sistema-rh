<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hr_configs', function (Blueprint $table) {
            $table->date('vigencia_inicio')->nullable()->after('id');
            $table->index('vigencia_inicio');
        });
    }

    public function down(): void
    {
        Schema::table('hr_configs', function (Blueprint $table) {
            $table->dropColumn('vigencia_inicio');
        });
    }
};
