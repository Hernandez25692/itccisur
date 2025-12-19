<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('gor_antecedentes_registrales', function (Blueprint $table) {
            $table->id();

            // Datos generales
            $table->string('centro')->default('CAS');
            $table->string('circunscripcion');

            // Fechas
            $table->date('fecha_recepcion');

            // Solicitante
            $table->string('solicitante_nombre');
            $table->text('solicitante_direccion')->nullable();

            // Datos registrales
            $table->string('numero_exequatur')->nullable();
            $table->string('asiento_tomo_matricula')->nullable();
            $table->string('tipo_libro')->nullable();

            // Motivo
            $table->text('motivo')->nullable();

            // Auditoría básica
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gor_antecedentes_registrales');
    }
};
