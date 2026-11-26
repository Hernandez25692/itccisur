<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Une la Bitácora TI con el Plan de Trabajo:
 * - una bitácora puede indicar a qué actividad del plan corresponde;
 * - un avance del plan puede venir de una bitácora (queda como su evidencia).
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::table('bitacora_actividades', function (Blueprint $table) {
            $table->foreignId('plan_actividad_id')
                ->nullable()
                ->after('user_id')
                ->constrained('plan_actividades')
                ->nullOnDelete();
        });

        Schema::table('actividad_ejecuciones', function (Blueprint $table) {
            $table->foreignId('bitacora_actividad_id')
                ->nullable()
                ->after('plan_actividad_id')
                ->constrained('bitacora_actividades')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('actividad_ejecuciones', function (Blueprint $table) {
            $table->dropConstrainedForeignId('bitacora_actividad_id');
        });

        Schema::table('bitacora_actividades', function (Blueprint $table) {
            $table->dropConstrainedForeignId('plan_actividad_id');
        });
    }
};
