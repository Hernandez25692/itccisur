<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plan_actividades', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('plan_trabajo_id');

            $table->string('codigo', 10); // Ej: 1.1
            $table->string('seccion'); // Ej: INFRAESTRUCTURA Y SEGURIDAD
            $table->string('actividad');
            $table->text('objetivo');
            $table->string('frecuencia');
            $table->string('responsable');
            $table->string('mes_previsto')->nullable();
            $table->string('fecha_ejecucion')->nullable(); // Rango o fecha en texto
            $table->string('metrica_exito')->nullable();

            // ENUM DEFINITIVO Y CORREGIDO
            $table->enum('estado', ['pendiente', 'en_progreso', 'finalizado'])
                ->default('pendiente');

            $table->text('observaciones')->nullable();

            $table->timestamps();

            $table->foreign('plan_trabajo_id')
                ->references('id')->on('plan_trabajos')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plan_actividades');
    }
};
