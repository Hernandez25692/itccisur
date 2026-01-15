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
        Schema::table('cs_empresas', function (Blueprint $table) {
            $table->date('fecha_ultimo_pago_historico')
                ->nullable()
                ->after('fecha_ultimo_pago')
                ->comment('Último pago antes de migrar al sistema');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cs_empresas', function (Blueprint $table) {
            //
        });
    }
};
