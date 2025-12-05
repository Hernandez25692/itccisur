<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plan_actividad_ejecuciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('plan_actividad_id');

            $table->date('fecha_ejecucion');
            $table->decimal('avance', 5, 2)->default(0); // CORREGIDO
            $table->text('comentarios')->nullable();
            $table->string('evidencia')->nullable();

            $table->timestamps();

            $table->foreign('plan_actividad_id')
                ->references('id')->on('plan_actividades')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plan_actividad_ejecuciones');
    }
};
