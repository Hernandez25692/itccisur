<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        // 🟢 ESCENARIO 1: base nueva (servidor)
        if (!Schema::hasTable('cs_capital_rangos')) {

            Schema::create('cs_capital_rangos', function (Blueprint $table) {
                $table->id();

                $table->foreignId('tipo_empresa_id')
                    ->constrained('cs_tipos_empresa')
                    ->cascadeOnDelete();

                $table->decimal('capital_min', 14, 2);
                $table->decimal('capital_max', 14, 2);
                $table->decimal('cuota_mensual', 14, 2);
                $table->decimal('inscripcion', 14, 2)->default(0);
                $table->boolean('activo')->default(true);
                $table->timestamps();
            });

            return;
        }

        // 🟡 ESCENARIO 2: base existente (local)
        if (!Schema::hasColumn('cs_capital_rangos', 'tipo_empresa_id')) {

            Schema::table('cs_capital_rangos', function (Blueprint $table) {
                $table->foreignId('tipo_empresa_id')
                    ->after('id')
                    ->constrained('cs_tipos_empresa')
                    ->cascadeOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('cs_capital_rangos')) {

            Schema::table('cs_capital_rangos', function (Blueprint $table) {

                if (Schema::hasColumn('cs_capital_rangos', 'tipo_empresa_id')) {
                    $table->dropForeign(['tipo_empresa_id']);
                    $table->dropColumn('tipo_empresa_id');
                }
            });
        }
    }
};
