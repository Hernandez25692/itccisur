<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('cs_rutas', function (Blueprint $table) {
            $table->id();

            // Datos base
            $table->string('nombre');
            $table->date('fecha_ruta');

            // 🔑 Gestor (puede ser NULL mientras la ruta es sugerida)
            $table->foreignId('gestor_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Estados reales del flujo
            $table->enum('estado', [
                'sugerida',
                'asignada',
                'en_ruta',
                'finalizada',
            ])->default('sugerida');

            // Parámetros de sugerencia
            $table->unsignedTinyInteger('ventana_dias_cobro')->default(7);
            $table->boolean('solo_con_coordenadas')->default(true);
            $table->boolean('incluir_en_mora')->default(true);

            // Totales y observaciones
            $table->unsignedSmallInteger('total_empresas')->default(0);
            $table->text('observaciones')->nullable();

            // Auditoría
            $table->foreignId('creado_por')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('confirmado_por')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('confirmado_en')->nullable();

            $table->timestamps();

            // Índices útiles
            $table->index(['fecha_ruta', 'estado']);
            $table->index('gestor_id');
        });

        // ===============================
        // TABLA PIVOTE RUTA ↔ EMPRESAS
        // ===============================
        Schema::create('cs_ruta_empresas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ruta_id')
                ->constrained('cs_rutas')
                ->cascadeOnDelete();

            $table->foreignId('empresa_id')
                ->constrained('cs_empresas')
                ->cascadeOnDelete();

            $table->unsignedSmallInteger('orden')->default(1);

            $table->enum('estado_visita', [
                'pendiente',
                'visitado',
                'no_encontrado',
                'reprogramado',
            ])->default('pendiente');

            $table->text('nota_visita')->nullable();
            $table->timestamp('checked_at')->nullable();

            $table->timestamps();

            $table->unique(['ruta_id', 'empresa_id']);
            $table->index(['ruta_id', 'orden']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cs_ruta_empresas');
        Schema::dropIfExists('cs_rutas');
    }
};
