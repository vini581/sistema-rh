<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabela de itens dinâmicos da folha
        Schema::create('payroll_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->enum('type', ['earning', 'deduction']); // provento ou desconto
            $table->bigInteger('amount'); // em centavos
            $table->boolean('is_automatic')->default(false); // se foi gerado pelo sistema ou manual
            $table->timestamps();
        });

        // Adicionar tabelas de impostos configuráveis ao HrConfig
        Schema::table('hr_configs', function (Blueprint $table) {
            $table->json('inss_table')->nullable()->after('vigencia_inicio');
            $table->json('irrf_table')->nullable()->after('inss_table');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payroll_items');
        Schema::table('hr_configs', function (Blueprint $table) {
            $table->dropColumn(['inss_table', 'irrf_table']);
        });
    }
};
