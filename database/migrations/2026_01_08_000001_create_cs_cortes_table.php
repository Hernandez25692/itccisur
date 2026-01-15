<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cs_cortes', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('dia_corte'); // 1-31
            $table->string('nombre')->nullable();     // Ej: "Corte 5"
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->unique('dia_corte');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cs_cortes');
    }
};
