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
        DB::table('cs_tipos_empresa')->updateOrInsert(['nombre' => 'Comerciante Individual'], ['activo' => true, 'created_at' => now(), 'updated_at' => now()]);
        DB::table('cs_tipos_empresa')->updateOrInsert(['nombre' => 'Sociedad'], ['activo' => true, 'created_at' => now(), 'updated_at' => now()]);

        // Categorías de rubros
        $categorias = [
            ['nombre' => 'ACUACULTURA', 'descripcion' => null],
            ['nombre' => 'ADUANAS', 'descripcion' => null],
            ['nombre' => 'AGRICULTURA', 'descripcion' => null],
            ['nombre' => 'ASESORIA/ LEGAL', 'descripcion' => null],
            ['nombre' => 'AVICULTURA', 'descripcion' => null],
            ['nombre' => 'COMERCIO Y VENTAS', 'descripcion' => null],
            ['nombre' => 'COMUNICACIÓN Y PERIODISMO', 'descripcion' => null],
            ['nombre' => 'CONSTRUCCION', 'descripcion' => null],
            ['nombre' => 'CONTABILIDAD', 'descripcion' => null],
            ['nombre' => 'DEPORTES', 'descripcion' => null],
            ['nombre' => 'EDUCACION', 'descripcion' => null],
            ['nombre' => 'ELECTRONICA', 'descripcion' => null],
            ['nombre' => 'EXTRACCION DE MINERALES', 'descripcion' => null],
            ['nombre' => 'FERRETERIA', 'descripcion' => null],
            ['nombre' => 'FINANCIERA', 'descripcion' => null],
            ['nombre' => 'GANADERIA', 'descripcion' => null],
            ['nombre' => 'HOSTELERIA Y TURISMO', 'descripcion' => null],
            ['nombre' => 'INDUSTRIAL', 'descripcion' => null],
            ['nombre' => 'INFORMATICA', 'descripcion' => null],
            ['nombre' => 'INMOBILIARIA', 'descripcion' => null],
            ['nombre' => 'MECANICA', 'descripcion' => null],
            ['nombre' => 'MEDICINA Y SALUD', 'descripcion' => null],
            ['nombre' => 'RECURSOS HUMANOS', 'descripcion' => null],
            ['nombre' => 'RESTAURANTE', 'descripcion' => null],
            ['nombre' => 'SEGURIDAD', 'descripcion' => null],
            ['nombre' => 'SERVICIOS VARIOS', 'descripcion' => null],
            ['nombre' => 'TRANSPORTE', 'descripcion' => null],
            ['nombre' => 'ENERGETICA', 'descripcion' => null],
            ['nombre' => 'FUNERARIA', 'descripcion' => null],
            ['nombre' => 'REPOSTERIA', 'descripcion' => null],
            ['nombre' => 'GIMNASIO', 'descripcion' => null],
            ['nombre' => 'CONSULTORIAS', 'descripcion' => null],
            ['nombre' => 'MINERIA', 'descripcion' => null],
            ['nombre' => 'PRODUCTOS LACTEOS', 'descripcion' => null],
        ];

        foreach ($categorias as $categoria) {
            DB::table('cs_categorias')->updateOrInsert(
                ['nombre' => $categoria['nombre']],
                ['descripcion' => $categoria['descripcion'], 'activo' => true, 'created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
