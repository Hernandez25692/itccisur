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
        Schema::create('rrhh_vacaciones_movimientos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('empleado_id')
                ->constrained('rrhh_empleados')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('periodo_vacacion_id')
                ->nullable()
                ->constrained('rrhh_vacaciones_periodos')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            $table->enum('tipo_movimiento', [
                'vacacion_dias',
                'vacacion_horas',
                'permiso_especial',
                'ajuste_manual',
            ]);

            $table->enum('estado', [
                'pendiente',
                'aprobado',
                'rechazado',
                'anulado',
            ])->default('pendiente');

            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable();

            $table->time('hora_inicio')->nullable();
            $table->time('hora_fin')->nullable();

            $table->decimal('dias_equivalentes', 5, 2)->default(0);
            $table->decimal('horas_equivalentes', 5, 2)->default(0);

            $table->string('mes_referencia', 20)->nullable();

            $table->text('motivo')->nullable();
            $table->text('observacion')->nullable();

            $table->string('archivo_comprobante')->nullable();

            $table->string('aprobado_por')->nullable();
            $table->timestamp('fecha_aprobacion')->nullable();

            $table->timestamps();

            $table->index('tipo_movimiento');
            $table->index('estado');
            $table->index('fecha_inicio');
            $table->index('fecha_fin');
            $table->index('mes_referencia');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rrhh_vacaciones_movimientos');
    }
};
