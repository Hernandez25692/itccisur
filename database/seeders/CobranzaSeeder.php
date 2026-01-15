<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class CobranzaSeeder extends Seeder
{
    public function run(): void
    {
        

        // Cortes fijos (5, 12, 20, 28)
        $cortes = [5, 12, 20, 28];

        foreach ($cortes as $dia) {
            DB::table('cs_cortes')->updateOrInsert(
                ['dia_corte' => $dia],
                ['nombre' => "Corte {$dia}", 'activo' => true, 'created_at' => now(), 'updated_at' => now()]
            );
        }

        // Catálogos básicos (opcionales)
        DB::table('cs_tipos_empresa')->updateOrInsert(['nombre' => 'Jurídica'], ['activo' => true, 'created_at' => now(), 'updated_at' => now()]);
        DB::table('cs_tipos_empresa')->updateOrInsert(['nombre' => 'Natural'], ['activo' => true, 'created_at' => now(), 'updated_at' => now()]);
    }
}
