<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rrhh_vacaciones_bitacora', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('empleado_id');
            $table->unsignedBigInteger('movimiento_id')->nullable();

            $table->string('accion');
            $table->text('detalle')->nullable();

            $table->string('usuario')->nullable();

            $table->timestamp('fecha_evento')->useCurrent();

            $table->timestamps();

            $table->foreign('empleado_id')
                ->references('id')
                ->on('rrhh_empleados')
                ->onDelete('cascade');

            $table->foreign('movimiento_id')
                ->references('id')
                ->on('rrhh_vacaciones_movimientos')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('rrhh_vacaciones_bitacora');
    }
};
