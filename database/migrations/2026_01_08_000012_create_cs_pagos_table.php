<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cs_pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('cs_empresas')->cascadeOnDelete();

            $table->date('fecha_pago');
            $table->decimal('monto', 14, 2);

            $table->enum('metodo', ['efectivo', 'transferencia', 'deposito', 'cheque', 'otro'])->default('efectivo');
            $table->string('referencia')->nullable();
            $table->text('comentario')->nullable();

            $table->foreignId('gestor_id')->nullable()->constrained('users')->nullOnDelete(); // quien cobró
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['empresa_id', 'fecha_pago']);
        });

        Schema::create('cs_pago_cargo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pago_id')->constrained('cs_pagos')->cascadeOnDelete();
            $table->foreignId('cargo_id')->constrained('cs_cargos')->cascadeOnDelete();
            $table->decimal('monto_aplicado', 14, 2);
            $table->timestamps();

            $table->unique(['pago_id', 'cargo_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cs_pago_cargo');
        Schema::dropIfExists('cs_pagos');
    }
};
