<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('cs_ruta_empresas', function (Blueprint $table) {
            $table->foreignId('gestor_id')
                ->nullable()
                ->after('empresa_id')
                ->constrained('users')
                ->nullOnDelete();

            $table->index('gestor_id');
        });
    }

    public function down(): void
    {
        Schema::table('cs_ruta_empresas', function (Blueprint $table) {
            $table->dropForeign(['gestor_id']);
            $table->dropColumn('gestor_id');
        });
    }
};
