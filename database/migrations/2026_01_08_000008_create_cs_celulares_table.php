<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cs_celulares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('cs_empresas')->cascadeOnDelete();
            $table->string('celular');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cs_celulares');
    }
};
