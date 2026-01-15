<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cs_capital_rangos', function (Blueprint $table) {
            $table->id();
            $table->decimal('capital_min', 14, 2);
            $table->decimal('capital_max', 14, 2);
            $table->decimal('cuota_mensual', 14, 2);
            $table->decimal('inscripcion', 14, 2)->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cs_capital_rangos');
    }
};
