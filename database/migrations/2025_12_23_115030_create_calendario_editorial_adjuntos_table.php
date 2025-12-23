<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('calendario_editorial_adjuntos', function (Blueprint $table) {
            $table->id();

            // Relación con calendario editorial
            $table->foreignId('calendario_editorial_id')
                ->constrained('calendario_editorials')
                ->cascadeOnDelete();

            // Datos del archivo
            $table->string('ruta');               // storage path
            $table->string('nombre_original');    // nombre real del archivo
            $table->string('mime_type', 100)->nullable();
            $table->unsignedBigInteger('tamano')->nullable(); // bytes

            // Auditoría básica
            $table->timestamps();

            // Índices útiles
            $table->index('calendario_editorial_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calendario_editorial_adjuntos');
    }
};
