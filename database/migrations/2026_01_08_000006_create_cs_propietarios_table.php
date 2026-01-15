<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cs_propietarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('cs_empresas')->cascadeOnDelete();
            $table->string('nombre');
            $table->string('identidad');
            $table->string('rtn')->nullable();
            $table->timestamps();

            $table->index(['empresa_id', 'identidad']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cs_propietarios');
    }
};
