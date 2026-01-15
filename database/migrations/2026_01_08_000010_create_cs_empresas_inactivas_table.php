<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cs_empresas_inactivas', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('empresa_original_id')->nullable();
            $table->date('fecha_inactivacion');
            $table->string('motivo_inactivacion')->nullable();
            $table->foreignId('inactivado_por')->nullable()->constrained('users')->nullOnDelete();

            // snapshot mínimo (puedes ampliar si deseas)
            $table->string('nombre_empresa');
            $table->string('rtn_empresa');
            $table->string('ciudad')->nullable();
            $table->string('barrio_colonia')->nullable();
            $table->string('direccion')->nullable();
            $table->decimal('latitud', 10, 7)->nullable();
            $table->decimal('longitud', 10, 7)->nullable();

            $table->text('comentario')->nullable();
            $table->timestamps();

            $table->index(['rtn_empresa', 'empresa_original_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cs_empresas_inactivas');
    }
};
