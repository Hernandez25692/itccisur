<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cs_cargos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('cs_empresas')->cascadeOnDelete();

            $table->date('periodo_inicio');
            $table->date('periodo_fin');
            $table->date('fecha_vencimiento');

            $table->decimal('monto_cuota', 14, 2);
            $table->decimal('monto_mora', 14, 2)->default(0); // si luego agregas recargo
            $table->decimal('total', 14, 2);

            $table->enum('estado', ['pendiente', 'pagado', 'anulado'])->default('pendiente');
            $table->dateTime('pagado_en')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['empresa_id', 'estado']);
            $table->unique(['empresa_id', 'periodo_inicio', 'periodo_fin'], 'cs_cargos_periodo_unico');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cs_cargos');
    }
};
