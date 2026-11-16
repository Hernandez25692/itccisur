<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Credenciales desde .env (ADMIN_EMAIL / ADMIN_PASSWORD)
        $email = config('app.admin.email');
        $password = config('app.admin.password');
        $generada = ! $password;

        if ($generada) {
            $password = Str::password(16);
        }

        $admin = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => 'Administrador TI',
                'password' => bcrypt($password),
            ]
        );

        // Solo se informa si de verdad se usó (el admin no existía)
        if ($generada && $admin->wasRecentlyCreated) {
            $this->command?->warn("ADMIN_PASSWORD no está definido: contraseña generada para {$email}: {$password}");
        }

        $admin->assignRole('admin_ti');
    }
}
