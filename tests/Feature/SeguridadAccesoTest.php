<?php

use App\Models\BitacoraActividad;
use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    foreach (['admin_ti', 'gerencia', 'usuario', 'GOR'] as $rol) {
        Role::findOrCreate($rol);
    }

    Storage::fake('local');
    Storage::fake('public');
});

function usuarioConRol(?string $rol = null): User
{
    $usuario = User::factory()->create();

    if ($rol) {
        $usuario->assignRole($rol);
    }

    return $usuario;
}

function bitacoraConEvidencia(string $ruta): BitacoraActividad
{
    return BitacoraActividad::create([
        'user_id' => usuarioConRol()->id,
        'titulo' => 'Falla de impresora',
        'fecha' => '2026-10-28',
        'evidencia' => $ruta,
    ]);
}

// El dashboard usa DATEDIFF (solo MySQL), por eso aquí no se prueba que cargue
it('pide sesión y rol para el dashboard TI', function () {
    $this->get('/dashboard-ti')->assertRedirect(route('login'));
    $this->actingAs(usuarioConRol('usuario'))->get('/dashboard-ti')->assertForbidden();
});

it('no deja entrar a módulos internos a un usuario sin el rol', function () {
    $sinRol = usuarioConRol();

    foreach (['bitacora.index', 'plan-trabajo.index', 'control.index', 'gor.resumen'] as $ruta) {
        $this->actingAs($sinRol)->get(route($ruta))->assertForbidden();
    }

    $this->actingAs(usuarioConRol('admin_ti'))->get(route('bitacora.index'))->assertOk();
});

it('sirve la evidencia de bitácora solo con sesión y rol', function () {
    Storage::disk('local')->put('bitacora_evidencias/foto.png', 'imagen');
    $actividad = bitacoraConEvidencia('bitacora_evidencias/foto.png');

    $url = route('bitacora.evidencia', $actividad->id);

    $this->get($url)->assertRedirect(route('login'));
    $this->actingAs(usuarioConRol('usuario'))->get($url)->assertForbidden();
    $this->actingAs(usuarioConRol('admin_ti'))->get($url)->assertOk();
});

it('sigue encontrando archivos antiguos del disco público', function () {
    Storage::disk('public')->put('bitacora_evidencias/vieja.png', 'imagen');
    $actividad = bitacoraConEvidencia('bitacora_evidencias/vieja.png');

    $this->actingAs(usuarioConRol('admin_ti'))
        ->get(route('bitacora.evidencia', $actividad->id))
        ->assertOk();
});

it('mueve los archivos del disco público al privado', function () {
    Storage::disk('public')->put('gor/comprobantes/gor_1.jpg', 'comprobante');
    Storage::disk('public')->put('calendario_editorial/arte.png', 'arte');
    Storage::disk('public')->put('logos/logo2.png', 'logo');

    $this->artisan('archivos:privatizar')->assertSuccessful();

    Storage::disk('local')->assertExists(['gor/comprobantes/gor_1.jpg', 'calendario_editorial/arte.png']);
    Storage::disk('public')->assertMissing(['gor/comprobantes/gor_1.jpg', 'calendario_editorial/arte.png']);

    // El logo es público a propósito y no se toca
    Storage::disk('public')->assertExists('logos/logo2.png');
});

it('crea el admin con la contraseña del .env y no con una fija', function () {
    config(['app.admin.email' => 'ti@ccisur.org', 'app.admin.password' => 'clave-del-env']);

    $this->seed(AdminUserSeeder::class);

    $admin = User::where('email', 'ti@ccisur.org')->first();

    expect(Hash::check('clave-del-env', $admin->password))->toBeTrue()
        ->and($admin->hasRole('admin_ti'))->toBeTrue();
});
