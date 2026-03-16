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
        Schema::create('rrhh_empleados', function (Blueprint $table) {
            $table->id();

            $table->string('nombre_completo');
            $table->string('identidad', 30)->nullable()->unique();
            $table->string('correo')->nullable();
            $table->string('telefono', 25)->nullable();

            $table->string('cargo')->nullable();
            $table->string('area')->nullable();

            $table->date('fecha_contratacion');

            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
            $table->date('fecha_salida')->nullable();

            $table->text('nota_general')->nullable();

            $table->timestamps();

            $table->index('nombre_completo');
            $table->index('estado');
            $table->index('fecha_contratacion');
            $table->decimal('vacaciones_acumuladas', 5, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rrhh_empleados');
    }
};
