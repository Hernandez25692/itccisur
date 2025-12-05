<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('actividad_ejecuciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_actividad_id')->constrained('plan_actividades')->onDelete('cascade');
            $table->unsignedTinyInteger('avance'); // 0–100%
            $table->text('comentario')->nullable();
            $table->date('fecha')->nullable();

            // Evidencias (opcional: PDF/IMG)
            $table->string('evidencia')->nullable();

            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('actividad_ejecuciones');
    }
};
