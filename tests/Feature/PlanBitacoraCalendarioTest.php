<?php

use App\Models\ActividadEjecucion;
use App\Models\BitacoraActividad;
use App\Models\CalendarioEditorial;
use App\Models\ControlRecordatorio;
use App\Models\PlanActividad;
use App\Models\PlanTrabajo;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    foreach (['admin_ti', 'gerencia', 'usuario', 'calendario'] as $rol) {
        Role::findOrCreate($rol);
    }

    Storage::fake('local');
    Storage::fake('public');
});

function pbcUsuario(string $rol): User
{
    $usuario = User::factory()->create();
    $usuario->assignRole($rol);

    return $usuario;
}

function pbcPublicacion(User $creador, array $datos = []): CalendarioEditorial
{
    return CalendarioEditorial::create($datos + [
        'semana' => 49, 'fecha_publicacion' => '2026-12-01', 'tema' => 'Tema',
        'creado_por' => $creador->id, 'estado' => 'pendiente',
    ]);
}

function pbcFormPublicacion(array $datos = []): array
{
    return $datos + ['fecha_publicacion' => '2026-12-01', 'tema' => 'Tema editado'];
}

function pbcPlan(string $estado = 'aprobado', int $anio = 2026): PlanTrabajo
{
    return PlanTrabajo::create(['anio' => $anio, 'estado' => $estado]);
}

function pbcActividad(PlanTrabajo $plan, array $datos = []): PlanActividad
{
    return $plan->actividades()->create($datos + [
        'codigo' => (string) ($plan->actividades()->count() + 1),
        'seccion' => 'INFRAESTRUCTURA', 'actividad' => 'Mantenimiento de servidores',
        'objetivo' => 'Disponibilidad', 'frecuencia' => 'Mensual', 'responsable' => 'Soporte',
    ]);
}

function pbcBitacora(User $autor, array $datos = []): BitacoraActividad
{
    return BitacoraActividad::create($datos + [
        'user_id' => $autor->id, 'titulo' => 'Falla', 'fecha' => '2026-11-20',
        'estado' => 'pendiente', 'prioridad' => 'media',
    ]);
}

function pbcFormBitacora(array $datos = []): array
{
    return $datos + ['titulo' => 'Cambio de disco', 'fecha' => '2026-11-20', 'estado' => 'resuelto', 'prioridad' => 'media'];
}

/*
|--------------------------------------------------------------------------
| Calendario Editorial
|--------------------------------------------------------------------------
*/

it('deja al rol calendario guardar su publicación sin tocar el estado', function () {
    $usuario = pbcUsuario('calendario');
    $publicacion = pbcPublicacion($usuario, ['estado' => 'reprogramado']);

    $this->actingAs($usuario)
        ->put(route('calendario-editorial.update', $publicacion), pbcFormPublicacion(['estado' => 'publicado', 'nota_admin' => 'no']))
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('calendario-editorial.index'));

    $publicacion->refresh();
    expect($publicacion->tema)->toBe('Tema editado')
        ->and($publicacion->estado)->toBe('reprogramado')
        ->and($publicacion->nota_admin)->toBeNull();
});

it('guarda la nota administrativa y el enlace de admin_ti', function () {
    $admin = pbcUsuario('admin_ti');
    $publicacion = pbcPublicacion($admin);

    $this->actingAs($admin)->put(route('calendario-editorial.update', $publicacion), pbcFormPublicacion([
        'estado' => 'publicado', 'nota_admin' => 'Revisado por TI', 'enlace' => 'facebook.com/ccisur/posts/1',
    ]))->assertSessionHasNoErrors();

    $publicacion->refresh();
    expect($publicacion->nota_admin)->toBe('Revisado por TI')
        ->and($publicacion->enlace)->toBe('https://facebook.com/ccisur/posts/1')
        ->and($publicacion->estado)->toBe('publicado')
        ->and($publicacion->publicado_por)->toBe($admin->id);
});

it('no deja al rol calendario editar publicaciones de otros', function () {
    $publicacion = pbcPublicacion(pbcUsuario('admin_ti'));
    $usuario = pbcUsuario('calendario');

    $this->actingAs($usuario)->get(route('calendario-editorial.edit', $publicacion))->assertForbidden();
    $this->actingAs($usuario)
        ->put(route('calendario-editorial.update', $publicacion), pbcFormPublicacion(['estado' => 'publicado']))
        ->assertForbidden();

    expect($publicacion->fresh()->estado)->toBe('pendiente');
});

it('borra adjuntos propios y protege los ajenos', function () {
    $usuario = pbcUsuario('calendario');
    $propia = pbcPublicacion($usuario, ['adjunto_path' => 'calendario_editorial/a.png']);
    $adjunto = $propia->adjuntos()->create(['ruta' => 'calendario_editorial/a.png', 'nombre_original' => 'a.png']);
    $ajena = pbcPublicacion(pbcUsuario('admin_ti'));
    $adjuntoAjeno = $ajena->adjuntos()->create(['ruta' => 'calendario_editorial/b.png', 'nombre_original' => 'b.png']);

    $this->actingAs($usuario)->deleteJson(route('calendario-editorial.adjuntos.destroy', $adjunto))->assertOk();
    $this->actingAs($usuario)->deleteJson(route('calendario-editorial.adjuntos.destroy', $adjuntoAjeno))->assertForbidden();

    expect($propia->fresh()->adjunto_path)->toBeNull()
        ->and($ajena->adjuntos()->count())->toBe(1);
});

it('calcula el día desde la fecha y la semana ISO si no se indica', function () {
    $publicacion = CalendarioEditorial::create([
        'dia' => 'Domingo', 'fecha_publicacion' => '2026-12-28', 'tema' => 'Fin de año',
    ]);

    // 28/12/2026 es lunes de la semana ISO 53
    expect($publicacion->dia)->toBe('Lunes')->and($publicacion->semana)->toBe(53);
});

it('rechaza adjuntos HTML y no deja crear al rol usuario', function () {
    $admin = pbcUsuario('admin_ti');

    $this->actingAs($admin)->post(route('calendario-editorial.store'), pbcFormPublicacion([
        'adjuntos' => [UploadedFile::fake()->create('pagina.html', 2, 'text/html')],
    ]))->assertSessionHasErrors('adjuntos.0');

    $this->actingAs(pbcUsuario('usuario'))->get(route('calendario-editorial.create'))->assertForbidden();
});

it('entrega al calendario solo el rango visible y con filtros', function () {
    $admin = pbcUsuario('admin_ti');
    pbcPublicacion($admin, ['tema' => 'Diciembre FB', 'fecha_publicacion' => '2026-12-05', 'hora' => '09:30', 'publicar_en' => ['FACEBOOK']]);
    pbcPublicacion($admin, ['tema' => 'Diciembre IG', 'fecha_publicacion' => '2026-12-06', 'publicar_en' => ['INSTAGRAM']]);
    pbcPublicacion($admin, ['tema' => 'Enero', 'fecha_publicacion' => '2027-01-15']);

    $respuesta = $this->actingAs($admin)->getJson(route('calendario-editorial.calendar.eventos', [
        'start' => '2026-11-30', 'end' => '2027-01-11', 'red' => 'FACEBOOK',
    ]))->assertOk();

    expect($respuesta->json())->toHaveCount(1)
        ->and($respuesta->json('0.title'))->toBe('Diciembre FB')
        ->and($respuesta->json('0.start'))->toBe('2026-12-05T09:30')
        ->and($respuesta->json('0.backgroundColor'))->toBe(CalendarioEditorial::COLORES['pendiente']);
});

it('carga el listado, el calendario y el dashboard editorial con filtros', function () {
    $admin = pbcUsuario('admin_ti');
    pbcPublicacion($admin, ['area' => 'Evento', 'publicar_en' => ['FACEBOOK'], 'contenido' => ['VIDEO']]);
    pbcPublicacion($admin, ['estado' => 'publicado', 'fecha_publicacion' => '2026-12-02']);

    $this->actingAs($admin)
        ->get(route('calendario-editorial.index', ['area' => 'Evento', 'red' => 'FACEBOOK', 'tipo' => 'VIDEO', 'buscar' => 'Tem', 'anio' => 2026]))
        ->assertOk()
        ->assertSee('Tema');

    $this->actingAs($admin)->get(route('calendario-editorial.calendar'))->assertOk();

    $this->actingAs($admin)
        ->get(route('calendario-editorial.dashboard', ['mes' => 12, 'anio' => 2026]))
        ->assertOk()
        ->assertViewHas('cumplimiento', 50.0);
});

/*
|--------------------------------------------------------------------------
| Plan de Trabajo
|--------------------------------------------------------------------------
*/

it('numera las actividades sin repetir el código después de la 10', function () {
    $admin = pbcUsuario('admin_ti');
    $plan = pbcPlan('borrador');
    $datos = ['seccion' => 'S', 'actividad' => 'A', 'objetivo' => 'O', 'frecuencia' => 'Mensual', 'responsable' => 'R'];

    foreach (range(1, 12) as $_) {
        $this->actingAs($admin)->post(route('plan-actividad.store', $plan), $datos);
    }

    $codigos = $plan->actividades()->pluck('codigo')->all();
    expect($codigos)->toHaveCount(12)->and(array_unique($codigos))->toHaveCount(12);
});

it('solo deja a gerencia revisar planes enviados y guarda el historial', function () {
    $admin = pbcUsuario('admin_ti');
    $gerencia = pbcUsuario('gerencia');
    $plan = pbcPlan('borrador');

    $this->actingAs($gerencia)->post(route('plan-trabajo.aprobar', $plan));
    expect($plan->fresh()->estado)->toBe('borrador');

    // Sin actividades no se envía
    $this->actingAs($admin)->post(route('plan-trabajo.enviar', $plan))->assertSessionHas('error');
    expect($plan->fresh()->estado)->toBe('borrador');

    pbcActividad($plan);
    $this->actingAs($admin)->post(route('plan-trabajo.enviar', $plan));
    $this->actingAs($gerencia)->post(route('plan-trabajo.aprobar', $plan), ['comentario_gerencia' => 'OK']);

    expect($plan->fresh()->estado)->toBe('aprobado')
        ->and($plan->revisiones()->pluck('accion')->sort()->values()->all())->toBe(['aprobado', 'enviado']);
});

it('bloquea cambios del plan según su estado', function () {
    $admin = pbcUsuario('admin_ti');
    $aprobado = pbcPlan('aprobado');
    $actividad = pbcActividad($aprobado);

    // Aprobado: no se agregan actividades ni se edita el plan, pero sí se ajusta una actividad
    $this->actingAs($admin)->get(route('plan-actividad.create', $aprobado))->assertRedirect(route('plan-trabajo.show', $aprobado));
    $this->actingAs($admin)->get(route('plan-trabajo.edit', $aprobado))->assertRedirect(route('plan-trabajo.show', $aprobado));
    $this->actingAs($admin)->get(route('plan-actividad.edit', $actividad))->assertOk();

    // En revisión: tampoco se editan sus actividades
    $aprobado->update(['estado' => 'enviado']);
    $this->actingAs($admin)
        ->put(route('plan-actividad.update', $actividad), ['codigo' => '1', 'seccion' => 'S', 'actividad' => 'Cambiada', 'objetivo' => 'O', 'frecuencia' => 'F', 'responsable' => 'R'])
        ->assertSessionHas('error');
    expect($actividad->fresh()->actividad)->toBe('Mantenimiento de servidores');

    // Gerencia solo consulta
    $this->actingAs(pbcUsuario('gerencia'))->get(route('plan-trabajo.create'))->assertForbidden();
    $this->actingAs(pbcUsuario('gerencia'))->get(route('plan-trabajo.show', $aprobado))->assertOk();
});

it('no permite pasar del 100% y deriva el estado del progreso', function () {
    $admin = pbcUsuario('admin_ti');
    $actividad = pbcActividad(pbcPlan());

    $this->actingAs($admin)->post(route('actividad.ejecucion.store', $actividad), ['avance' => 60, 'comentario' => 'Parte 1', 'fecha' => '2026-11-01']);
    expect($actividad->fresh()->estado)->toBe('en_progreso');

    $this->actingAs($admin)
        ->post(route('actividad.ejecucion.store', $actividad), ['avance' => 50, 'comentario' => 'Parte 2', 'fecha' => '2026-11-02'])
        ->assertSessionHasErrors('avance');

    $this->actingAs($admin)->post(route('actividad.ejecucion.store', $actividad), ['avance' => 40, 'comentario' => 'Parte 2', 'fecha' => '2026-11-02']);
    expect($actividad->fresh()->estado)->toBe('finalizado')
        ->and($actividad->fresh()->progreso)->toBe(100);
});

it('marca atrasadas y filtra las actividades del plan', function () {
    $this->travelTo('2026-11-26');
    $admin = pbcUsuario('admin_ti');
    $plan = pbcPlan();
    pbcActividad($plan, ['actividad' => 'Respaldo de marzo', 'mes_previsto' => 'Marzo']);
    pbcActividad($plan, ['actividad' => 'Inventario de diciembre', 'mes_previsto' => 'Diciembre']);

    $this->actingAs($admin)
        ->get(route('plan-trabajo.show', ['plan' => $plan, 'situacion' => 'atrasada']))
        ->assertOk()
        ->assertSee('Respaldo de marzo')
        ->assertDontSee('Inventario de diciembre')
        ->assertViewHas('resumen', fn ($r) => $r['atrasadas'] === 1 && $r['total'] === 2);

    $this->actingAs($admin)->get(route('plan-trabajo.index', ['anio' => 2026]))->assertOk()->assertSee('1 atrasada');
});

/*
|--------------------------------------------------------------------------
| Bitácora TI e integración con el Plan
|--------------------------------------------------------------------------
*/

it('no deja cambiar el autor de una bitácora y acepta PDF al editar', function () {
    $admin = pbcUsuario('admin_ti');
    $otro = pbcUsuario('usuario');
    $bitacora = pbcBitacora($admin);

    $this->actingAs($admin)->put(route('bitacora.update', $bitacora->id), pbcFormBitacora([
        'user_id' => $otro->id,
        'evidencia' => UploadedFile::fake()->create('acta.pdf', 100, 'application/pdf'),
    ]))->assertSessionHasNoErrors();

    $bitacora->refresh();
    expect($bitacora->user_id)->toBe($admin->id)->and($bitacora->evidencia)->toEndWith('.pdf');
    Storage::disk('local')->assertExists($bitacora->evidencia);
});

it('rechaza una hora de fin anterior a la de inicio', function () {
    $this->actingAs(pbcUsuario('admin_ti'))
        ->post(route('bitacora.store'), pbcFormBitacora(['hora_inicio' => '10:00', 'hora_fin' => '09:00']))
        ->assertSessionHasErrors('hora_fin');

    expect(BitacoraActividad::count())->toBe(0);
});

it('suma avance al plan desde la bitácora y la deja como evidencia', function () {
    $admin = pbcUsuario('admin_ti');
    $actividad = pbcActividad(pbcPlan());

    $this->actingAs($admin)->post(route('bitacora.store'), pbcFormBitacora([
        'hora_inicio' => '08:00', 'hora_fin' => '09:30',
        'plan_actividad_id' => $actividad->id, 'avance_plan' => 30,
    ]))->assertSessionHasNoErrors();

    $bitacora = BitacoraActividad::firstOrFail();
    $ejecucion = ActividadEjecucion::firstOrFail();

    expect($bitacora->plan_actividad_id)->toBe($actividad->id)
        ->and($bitacora->tiempo_empleado_minutos)->toBe(90)
        ->and($ejecucion->bitacora_actividad_id)->toBe($bitacora->id)
        ->and($ejecucion->avance)->toBe(30)
        ->and($actividad->fresh()->estado)->toBe('en_progreso');

    $this->actingAs($admin)->get(route('plan-actividad.show', $actividad))->assertOk()->assertSee('Cambio de disco');
});

it('no guarda la bitácora ni su archivo si el avance excede lo que falta', function () {
    $admin = pbcUsuario('admin_ti');
    $actividad = pbcActividad(pbcPlan());
    ActividadEjecucion::create(['plan_actividad_id' => $actividad->id, 'avance' => 90, 'fecha' => '2026-11-01', 'user_id' => $admin->id]);

    $this->actingAs($admin)->post(route('bitacora.store'), pbcFormBitacora([
        'plan_actividad_id' => $actividad->id, 'avance_plan' => 20,
        'evidencia' => UploadedFile::fake()->image('foto.png'),
    ]))->assertSessionHasErrors('avance_plan');

    expect(BitacoraActividad::count())->toBe(0);
    expect(Storage::disk('local')->allFiles('bitacora_evidencias'))->toBeEmpty();
});

it('fija el vínculo con el plan cuando la bitácora ya sumó avance', function () {
    $admin = pbcUsuario('admin_ti');
    $plan = pbcPlan();
    $original = pbcActividad($plan);
    $otra = pbcActividad($plan, ['actividad' => 'Otra']);

    $this->actingAs($admin)->post(route('bitacora.store'), pbcFormBitacora(['plan_actividad_id' => $original->id, 'avance_plan' => 10]));
    $bitacora = BitacoraActividad::firstOrFail();

    $this->actingAs($admin)->put(route('bitacora.update', $bitacora->id), pbcFormBitacora([
        'plan_actividad_id' => $otra->id, 'avance_plan' => 50,
    ]))->assertSessionHasNoErrors();

    expect($bitacora->fresh()->plan_actividad_id)->toBe($original->id)
        ->and(ActividadEjecucion::count())->toBe(1);
});

it('cuenta los estados de la bitácora sobre todo lo filtrado y conserva filtros al paginar', function () {
    $admin = pbcUsuario('admin_ti');
    foreach (range(1, 20) as $i) {
        pbcBitacora($admin, ['titulo' => "Impresora {$i}", 'estado' => $i <= 18 ? 'pendiente' : 'resuelto']);
    }
    pbcBitacora($admin, ['titulo' => 'Otra cosa', 'estado' => 'pendiente']);

    $this->actingAs($admin)
        ->get(route('bitacora.index', ['buscar' => 'Impresora']))
        ->assertOk()
        ->assertViewHas('porEstado', fn ($c) => $c['pendiente'] === 18 && $c['resuelto'] === 2)
        ->assertSee('buscar=Impresora&amp;page=2', false);

    $this->actingAs(pbcUsuario('gerencia'))->get(route('bitacora.create'))->assertForbidden();
});

it('abre todas las pantallas de los tres módulos', function () {
    $admin = pbcUsuario('admin_ti');
    $gerencia = pbcUsuario('gerencia');
    $plan = pbcPlan();
    $actividad = pbcActividad($plan, ['mes_previsto' => 'Marzo']);
    $borrador = pbcPlan('borrador', 2027);

    $this->actingAs($admin)->post(route('bitacora.store'), pbcFormBitacora([
        'plan_actividad_id' => $actividad->id, 'avance_plan' => 10,
        'evidencia' => UploadedFile::fake()->create('informe.pdf', 10, 'application/pdf'),
    ]));
    $bitacora = BitacoraActividad::firstOrFail();
    $publicacion = pbcPublicacion($admin, ['nota_admin' => 'Nota interna', 'publicar_en' => ['FACEBOOK']]);
    $publicacion->adjuntos()->create(['ruta' => 'calendario_editorial/x.png', 'nombre_original' => 'x.png', 'mime_type' => 'image/png', 'tamano' => 10]);

    foreach ([
        route('bitacora.index'), route('bitacora.create', ['plan_actividad_id' => $actividad->id]),
        route('bitacora.edit', $bitacora->id), route('bitacora.show', $bitacora->id),
        route('plan-trabajo.index'), route('plan-trabajo.show', $plan), route('plan-trabajo.create'),
        route('plan-trabajo.edit', $borrador), route('plan-actividad.create', $borrador),
        route('plan-actividad.show', $actividad), route('plan-actividad.edit', $actividad),
        route('calendario-editorial.create', ['fecha' => '2026-12-10']), route('calendario-editorial.edit', $publicacion),
        route('calendario-editorial.show', $publicacion),
    ] as $url) {
        $this->actingAs($admin)->get($url)->assertOk();
    }

    $this->actingAs($admin)->get(route('bitacora.edit', $bitacora->id))->assertSee('ya sumó');
    $this->actingAs($admin)->get(route('calendario-editorial.show', $publicacion))->assertSee('Nota interna');

    foreach ([route('bitacora.index'), route('bitacora.show', $bitacora->id), route('plan-trabajo.show', $plan),
        route('plan-actividad.show', $actividad), route('calendario-editorial.show', $publicacion)] as $url) {
        $this->actingAs($gerencia)->get($url)->assertOk()->assertDontSee('Editar Actividad');
    }
});

it('carga el dashboard TI con el avance del plan y el calendario', function () {
    $this->travelTo('2026-11-26');
    $admin = pbcUsuario('admin_ti');
    $actividad = pbcActividad(pbcPlan(), ['mes_previsto' => 'Enero']);
    ActividadEjecucion::create(['plan_actividad_id' => $actividad->id, 'avance' => 50, 'fecha' => '2026-02-01', 'user_id' => $admin->id]);
    pbcPublicacion($admin, ['fecha_publicacion' => '2026-11-27', 'tema' => 'Publicación de mañana']);
    ControlRecordatorio::create([
        'user_id' => $admin->id, 'actividad' => 'Dominio ccisur.org',
        'fecha_vencimiento' => '2026-12-05', 'dias_recordatorio' => 15,
    ]);

    $this->actingAs($admin)
        ->get(route('dashboard-ti.index'))
        ->assertOk()
        ->assertSee('Avance Plan TI 2026')
        ->assertSee('Publicación de mañana')
        ->assertSee('Dominio ccisur.org')
        ->assertViewHas('plan', fn ($p) => $p['avance'] === 50 && $p['atrasadas'] === 1);
});
