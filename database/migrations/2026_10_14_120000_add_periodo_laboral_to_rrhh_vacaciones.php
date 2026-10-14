<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Los periodos de vacaciones pasan de ser por año calendario a ser por año
 * laboral (de aniversario a aniversario). Los datos existentes se convierten
 * con el comando: php artisan rrhh:acreditar-vacaciones
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rrhh_vacaciones_periodos', function (Blueprint $table) {
            // 0 = antes del primer aniversario, 1 = primer año cumplido, ...
            $table->unsignedSmallInteger('numero_periodo')->nullable()->after('anio');
            $table->date('fecha_inicio')->nullable()->after('numero_periodo');
            $table->date('fecha_fin')->nullable()->after('fecha_inicio');
            // Último día en que se pueden tomar los días acreditados en el periodo
            $table->date('fecha_vencimiento')->nullable()->after('fecha_fin');
            $table->decimal('dias_vencidos', 6, 2)->default(0)->after('total_tomado');

            $table->unique(['empleado_id', 'numero_periodo']);
        });

        Schema::table('rrhh_vacaciones_movimientos', function (Blueprint $table) {
            $table->boolean('es_adelanto')->default(false)->after('horas_equivalentes');
        });
    }

    public function down(): void
    {
        Schema::table('rrhh_vacaciones_movimientos', function (Blueprint $table) {
            $table->dropColumn('es_adelanto');
        });

        Schema::table('rrhh_vacaciones_periodos', function (Blueprint $table) {
            $table->dropUnique(['empleado_id', 'numero_periodo']);
            $table->dropColumn([
                'numero_periodo',
                'fecha_inicio',
                'fecha_fin',
                'fecha_vencimiento',
                'dias_vencidos',
            ]);
        });
    }
};
