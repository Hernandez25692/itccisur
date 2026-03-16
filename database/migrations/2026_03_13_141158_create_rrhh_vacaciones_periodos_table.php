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
        Schema::create('rrhh_vacaciones_periodos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('empleado_id')
                ->constrained('rrhh_empleados')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->year('anio');

            $table->decimal('dias_correspondientes', 5, 2)->default(0);
            $table->decimal('acumulado_anterior', 5, 2)->default(0);

            $table->decimal('dias_descontados', 5, 2)->default(0);
            $table->decimal('total_disponible', 5, 2)->default(0);
            $table->decimal('total_tomado', 5, 2)->default(0);
            $table->decimal('dias_pendientes', 5, 2)->default(0);

            $table->text('nota')->nullable();
            $table->boolean('cerrado')->default(false);

            $table->timestamps();

            $table->unique(['empleado_id', 'anio']);
            $table->index('anio');
            $table->index('cerrado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rrhh_vacaciones_periodos');
    }
};
