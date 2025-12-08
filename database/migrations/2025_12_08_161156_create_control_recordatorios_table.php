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
        Schema::create('control_recordatorios', function (Blueprint $table) {
            $table->id();

            // Usuario que registra el control (TI)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Actividad a controlar (licencia, dominio, servicio, etc.)
            $table->string('actividad');
            // Ej: "Renovación licencia Windows Server", "Dominio ccisur.hn"

            // Opcional: tipo de elemento (no obligatorio, por si luego segmentás)
            $table->string('tipo')->nullable();
            // Ej: "licencia", "dominio", "soporte", "certificado SSL"

            // Breve descripción o detalle
            $table->text('descripcion')->nullable();

            // Fecha en la que debes ejecutar la acción (pagar/renovar)
            $table->date('fecha_ejecucion')->nullable();

            // Fecha de vencimiento real
            $table->date('fecha_vencimiento');

            // Días antes del vencimiento para empezar a alertar
            $table->unsignedSmallInteger('dias_recordatorio')->default(15);

            // Por si quieres pausar recordatorios o marcarlo como atendido
            $table->boolean('notificar')->default(true);
            $table->boolean('atendido')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('control_recordatorios');
    }
};
