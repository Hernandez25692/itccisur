<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'admin_ti']);
        Role::firstOrCreate(['name' => 'gerencia']);
        Role::firstOrCreate(['name' => 'usuario']);
        Role::firstOrCreate(['name' => 'calendario']);
        Role::firstOrCreate(['name' => 'GOR']);
        Role::firstOrCreate(['name' => 'cobranza']);
         Role::firstOrCreate(['name' => 'GOR_Gerencia']);

    }
}
