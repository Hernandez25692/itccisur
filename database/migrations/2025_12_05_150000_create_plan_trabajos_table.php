<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plan_trabajos', function (Blueprint $table) {
            $table->id();
            $table->year('anio');
            $table->enum('estado', ['borrador', 'enviado', 'aprobado', 'rechazado'])
                ->default('borrador');
            $table->unsignedBigInteger('aprobado_por')->nullable();
            $table->date('fecha_aprobacion')->nullable();
            $table->text('descripcion_general')->nullable();
            $table->timestamps();

            $table->foreign('aprobado_por')
                ->references('id')->on('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plan_trabajos');
    }
};
