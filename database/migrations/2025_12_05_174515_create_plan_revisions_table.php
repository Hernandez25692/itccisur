<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plan_revisions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('plan_trabajo_id')->constrained('plan_trabajos')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->enum('accion', ['enviado', 'aprobado', 'rechazado']);
            $table->text('comentario')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plan_revisions');
    }
};
