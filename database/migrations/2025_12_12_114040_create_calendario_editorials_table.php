<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('calendario_editorials', function (Blueprint $table) {
            $table->id();

            // Control mensual
            $table->unsignedTinyInteger('semana'); // ingresada por usuario (1..6 por ejemplo)
            $table->string('dia', 20);            // Lunes, Martes, etc.

            // Programación
            $table->date('fecha_publicacion');
            $table->time('hora')->nullable();

            // Contenido
            $table->text('tema');          // título grande
            $table->string('area', 120)->nullable();
            $table->longText('encabezado')->nullable();

            // Selectores múltiples (frontend)
            $table->json('contenido')->nullable();   // ["EN VIVO","IMAGEN","VIDEO"...]
            $table->json('publicar_en')->nullable(); // ["FACEBOOK","INSTAGRAM","X"...]
            $table->text('etiquetas')->nullable();
            $table->longText('comentario')->nullable();

            // Post-publicación
            $table->text('enlace')->nullable();

            // Adjunto (ruta en storage)
            $table->string('adjunto_path')->nullable();
            $table->string('adjunto_nombre')->nullable();

            // Control admin_ti
            $table->string('estado', 20)->default('pendiente'); // pendiente|publicado|reprogramado|cancelado
            $table->timestamp('fecha_publicado')->nullable();
            $table->foreignId('publicado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->longText('nota_admin')->nullable();

            // Auditoría
            $table->foreignId('creado_por')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            // Índices útiles para dashboard/filtros
            $table->index(['fecha_publicacion', 'semana']);
            $table->index(['estado']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calendario_editorials');
    }
};
