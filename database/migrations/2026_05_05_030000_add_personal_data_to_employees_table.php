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
        Schema::table('employees', function (Blueprint $table) {
            $table->date('birth_date')->nullable()->after('admission_date');
            $table->string('rg', 20)->nullable()->after('cpf');
            $table->string('pis', 20)->nullable()->after('rg');
            $table->enum('gender', ['male', 'female', 'other', 'not_specified'])->default('not_specified')->after('pis');
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed', 'other'])->default('single')->after('gender');
            $table->string('emergency_contact_name')->nullable()->after('address');
            $table->string('emergency_contact_phone', 20)->nullable()->after('emergency_contact_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
                'birth_date',
                'rg',
                'pis',
                'gender',
                'marital_status',
                'emergency_contact_name',
                'emergency_contact_phone'
            ]);
        });
    }
};
