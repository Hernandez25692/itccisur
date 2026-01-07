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
        Schema::create('bitacora_actividades', function (Blueprint $table) {
            $table->id();

            // Usuario que registra la actividad (técnico / personal TI)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Información general de la actividad
            $table->string('titulo'); // Ej: "Falla en impresora recepción"
            $table->text('descripcion')->nullable(); // Detalle de la incidencia / actividad

            // Datos de la falla
            $table->string('equipo_afectado')->nullable(); 
            $table->string('tipo_falla')->nullable();   
            $table->string('ubicacion')->nullable();    

            // Estado de la actividad
            $table->enum('estado', ['pendiente', 'en_proceso', 'resuelto'])->default('pendiente');

            // Indicador si se solucionó o no (por si el gerente solo quiere ver esto rápido)
            $table->boolean('solucionado')->default(false);

            // Tiempos
            $table->date('fecha')->index();         // Fecha del evento
            $table->time('hora_inicio')->nullable();
            $table->time('hora_fin')->nullable();

            // Descripción de la solución
            $table->text('solucion_aplicada')->nullable();
            $table->text('observaciones')->nullable();

            // Archivo de evidencia (foto, captura, etc.)
            $table->string('evidencia')->nullable(); // guardaremos ruta del archivo

            // Severidad / prioridad
            $table->enum('prioridad', ['baja', 'media', 'alta', 'critica'])->default('media');

            // Tiempo total estimado (en minutos) para métricas
            $table->unsignedInteger('tiempo_empleado_minutos')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bitacora_actividades');
    }
};
