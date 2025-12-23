<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('control_audiencias', function (Blueprint $table) {
            $table->id();

            // Datos principales
            $table->string('nombre_solicitante');
            $table->date('fecha_recepcion');
            $table->dateTime('fecha_hora_atencion')->nullable();

            // Detalles
            $table->text('motivo');
            $table->string('numero_documento');
            $table->text('dictamen')->nullable();

            // Auditoría
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('control_audiencias');
    }
};
