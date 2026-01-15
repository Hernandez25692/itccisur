<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cs_empresas', function (Blueprint $table) {
            $table->id();

            $table->string('nombre_empresa');
            $table->enum('estado_empresa', ['activo', 'inactivo'])->default('activo'); // si inactivo -> se mueve a histórico

            $table->string('rtn_empresa')->unique();
            $table->string('rubro_actividad')->nullable();
            $table->foreignId('categoria_id')->nullable()->constrained('cs_categorias')->nullOnDelete();
            $table->foreignId('tipo_empresa_id')->nullable()->constrained('cs_tipos_empresa')->nullOnDelete();

            $table->decimal('capital_declarado', 14, 2)->nullable();
            $table->foreignId('capital_rango_id')->nullable()->constrained('cs_capital_rangos')->nullOnDelete();

            // Congelar valores para evitar que cambios en rangos dañen historia:
            $table->decimal('cuota_base', 14, 2)->default(0);
            $table->decimal('inscripcion_base', 14, 2)->default(0);
            $table->decimal('cuota_especial', 14, 2)->nullable(); // si aplica descuento / negociación

            // Corte fijo
            $table->foreignId('corte_id')->constrained('cs_cortes');

            $table->enum('tipo_pago', ['mensual', 'bimensual', 'trimestral', 'semestral', 'anual'])->default('mensual');

            // ubicación
            $table->string('direccion')->nullable();
            $table->string('ciudad')->nullable();
            $table->string('barrio_colonia')->nullable();
            $table->double('latitud', 15, 14)->nullable();
            $table->double('longitud', 15, 14)->nullable();



            // responsables
            $table->string('gerente_adm')->nullable();
            $table->string('gerente_rrhh')->nullable();
            $table->string('gerente_contabilidad')->nullable();

            // control cobranza (cache calculado para dashboard rápido)
            $table->date('fecha_ultimo_pago')->nullable();
            $table->date('proxima_fecha_cobro')->nullable();

            $table->enum('estatus_cobranza', ['al_dia', 'en_mora'])->default('al_dia');
            $table->unsignedInteger('meses_mora')->default(0);
            $table->decimal('valor_mora', 14, 2)->default(0);
            $table->enum('observacion_cobro', ['cobrable', 'incobrable'])->default('cobrable');

            $table->text('comentario')->nullable();
            $table->timestamps();

            $table->index(['estatus_cobranza', 'observacion_cobro']);
            $table->index(['ciudad', 'barrio_colonia']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cs_empresas');
    }
};
