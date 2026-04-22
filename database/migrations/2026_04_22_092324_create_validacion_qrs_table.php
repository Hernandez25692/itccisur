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
        Schema::create('validaciones_qr', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_formacion');
            $table->date('fecha_formacion')->nullable();
            $table->year('anio')->nullable();
            $table->string('token')->unique();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('validaciones_qr');
    }
};
