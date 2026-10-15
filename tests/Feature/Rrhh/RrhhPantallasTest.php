<?php

use App\Models\RrhhEmpleado;
use App\Models\RrhhVacacionesMovimiento;
use App\Models\User;
use App\Services\Rrhh\VacacionesService;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    // Fecha fija para que el cierre anual no dependa del día en que corre la prueba
    $this->travelTo(Carbon::parse('2026-10-15 09:00:00'));

    foreach (['admin_ti', 'rrhh', 'cobranza'] as $rol) {
        Role::findOrCreate($rol);
    }
});

function rrhhUsuario(string $rol): User
{
    $usuario = User::factory()->create();
    $usuario->assignRole($rol);

    return $usuario;
}

function rrhhSaldo(RrhhEmpleado $empleado): float
{
    return app(VacacionesService::class)
        ->estadoCuenta($empleado->fresh(['periodosVacaciones', 'movimientosVacaciones']))
        ->saldo;
}

it('protege las pantallas de RRHH por sesión y rol', function () {
    $this->get(route('empleados.index'))->assertRedirect(route('login'));
    $this->get(route('vacaciones.index'))->assertRedirect(route('login'));

    $this->actingAs(rrhhUsuario('cobranza'))->get(route('empleados.index'))->assertForbidden();
    $this->actingAs(rrhhUsuario('rrhh'))->get(route('empleados.index'))->assertOk();
    $this->actingAs(rrhhUsuario('admin_ti'))->get(route('vacaciones.index'))->assertOk();
});

it('registra empleados con identidad y teléfono normalizados y acredita su periodo', function () {
    $this->actingAs(rrhhUsuario('admin_ti'));

    $this->post(route('empleados.store'), [
        'nombre_completo' => 'Luis Prueba',
        'identidad' => '0801199912345',
        'telefono' => '99998888',
        'cargo' => 'Auxiliar Contable',
        'area' => 'Dirección Ejecutiva',
        'fecha_contratacion' => now()->subYears(2)->subDays(10)->toDateString(),
    ])->assertSessionHasNoErrors()->assertRedirect(route('empleados.index'));

    $empleado = RrhhEmpleado::where('identidad', '0801-1999-12345')->firstOrFail();

    expect($empleado->telefono)->toBe('+504 9999-8888')
        ->and((float) $empleado->vacaciones_acumuladas)->toBe(0.0)
        ->and($empleado->periodosVacaciones()->sole()->numero_periodo)->toBe(2)
        ->and(rrhhSaldo($empleado))->toBe(12.0);

    $this->post(route('empleados.store'), [
        'nombre_completo' => 'Otro',
        'identidad' => '0801-1999-12345',
        'fecha_contratacion' => '2020-01-01',
    ])->assertSessionHasErrors('identidad');

    $this->post(route('empleados.store'), [
        'nombre_completo' => 'Otro',
        'area' => 'PRUEBA',
        'fecha_contratacion' => '2020-01-01',
    ])->assertSessionHasErrors('area');

    $this->flushSession();
    $this->get(route('empleados.edit', $empleado))->assertOk()->assertSee('0801-1999-12345');

    $this->put(route('empleados.update', $empleado), [
        'nombre_completo' => 'Luis Prueba',
        'identidad' => '0801-1999-12345',
        'fecha_contratacion' => $empleado->fecha_contratacion->toDateString(),
        'vacaciones_acumuladas' => 3,
    ])->assertSessionHasNoErrors();

    expect(rrhhSaldo($empleado))->toBe(15.0)
        ->and($empleado->bitacoraVacaciones()->where('accion', 'EDICION_EMPLEADO')->exists())->toBeTrue();

    $this->patch(route('empleados.estado', $empleado))->assertRedirect();

    expect($empleado->fresh()->estado)->toBe('inactivo')
        ->and($empleado->fresh()->fecha_salida)->not->toBeNull();
});

it('registra, edita y anula vacaciones recalculando el saldo', function () {
    $this->actingAs(rrhhUsuario('rrhh'));

    $this->post(route('empleados.store'), [
        'nombre_completo' => 'Ana Prueba',
        'fecha_contratacion' => now()->subYears(6)->subMonth()->toDateString(),
    ])->assertSessionHasNoErrors();

    $empleado = RrhhEmpleado::where('nombre_completo', 'Ana Prueba')->firstOrFail();

    expect(rrhhSaldo($empleado))->toBe(20.0);

    $this->get(route('vacaciones.crear', $empleado))->assertOk()->assertSee('Ana Prueba');

    // Lunes a miércoles de la próxima semana: 3 días
    $lunes = Carbon::today()->next(Carbon::MONDAY);
    $datos = [
        'empleado_id' => $empleado->id,
        'tipo_movimiento' => 'vacacion_dias',
        'fecha_inicio' => $lunes->toDateString(),
        'fecha_fin' => $lunes->copy()->addDays(2)->toDateString(),
        'motivo' => 'Viaje familiar',
    ];

    $this->post(route('vacaciones.guardar'), $datos + [
        'archivo' => UploadedFile::fake()->create('constancia.pdf', 50, 'application/pdf'),
    ])->assertSessionHasNoErrors()->assertRedirect(route('vacaciones.historial', $empleado));

    $primero = $empleado->movimientosVacaciones()->sole();

    expect(rrhhSaldo($empleado))->toBe(17.0)
        ->and($primero->periodo_vacacion_id)->not->toBeNull()
        ->and($primero->aprobado_por)->not->toBeNull();

    $this->get(route('vacaciones.comprobante', $primero))->assertOk();

    // Mismas fechas: traslape
    $this->post(route('vacaciones.guardar'), $datos)->assertSessionHasErrors('fecha_inicio');

    // 4 semanas (25 días sin domingos) superan el saldo: se permite y queda como adelanto
    $largo = [
        'empleado_id' => $empleado->id,
        'tipo_movimiento' => 'vacacion_dias',
        'fecha_inicio' => $lunes->copy()->addWeeks(2)->toDateString(),
        'fecha_fin' => $lunes->copy()->addWeeks(6)->toDateString(),
    ];

    $this->post(route('vacaciones.guardar'), $largo)->assertSessionHasNoErrors();

    expect(rrhhSaldo($empleado))->toBe(-8.0)
        ->and(RrhhVacacionesMovimiento::where('es_adelanto', true)->count())->toBe(1);

    // Reducir el primer movimiento a 2 días no lo marca como adelanto
    $this->get(route('vacaciones.editar', $primero))->assertOk();
    $this->put(route('vacaciones.actualizar', $primero), $datos + ['dias_manual' => '2'])->assertSessionHasNoErrors();

    expect(rrhhSaldo($empleado))->toBe(-7.0);

    // Vacaciones por horas: 10:00 a 14:00 = 3 horas (sin almuerzo)
    $this->post(route('vacaciones.guardar'), [
        'empleado_id' => $empleado->id,
        'tipo_movimiento' => 'vacacion_horas',
        'fecha_inicio' => $lunes->copy()->addWeeks(8)->toDateString(),
        'hora_inicio' => '10:00',
        'hora_fin' => '14:00',
        'adelanto' => '1',
    ])->assertSessionHasNoErrors();

    expect((float) RrhhVacacionesMovimiento::where('tipo_movimiento', 'vacacion_horas')->sole()->horas_equivalentes)->toBe(3.0)
        ->and(rrhhSaldo($empleado))->toBe(-7.375);

    // Permiso especial: se registra pero no descuenta
    $this->post(route('vacaciones.guardar'), [
        'empleado_id' => $empleado->id,
        'tipo_movimiento' => 'permiso_especial',
        'fecha_inicio' => $lunes->copy()->addWeeks(9)->toDateString(),
    ])->assertSessionHasNoErrors();

    expect(rrhhSaldo($empleado))->toBe(-7.375);

    // Anular el primero devuelve sus 2 días
    $this->patch(route('vacaciones.anular', $primero), ['motivo_anulacion' => 'Registro duplicado'])
        ->assertSessionHasNoErrors();

    expect($primero->fresh()->estado)->toBe('anulado')
        ->and(rrhhSaldo($empleado))->toBe(-5.375);

    $this->get(route('vacaciones.historial', $empleado))
        ->assertOk()
        ->assertSee('Ana Prueba')
        ->assertSee('ANULACION MOVIMIENTO')
        ->assertSee('Estado de cuenta');

    $this->get(route('vacaciones.index'))->assertOk()->assertSee('Ana Prueba');
    $this->get(route('empleados.index', ['pendiente' => 'sin']))->assertOk()->assertSee('Ana Prueba');
    $this->get(route('empleados.index', ['pendiente' => 'con']))->assertOk()->assertDontSee('Ana Prueba');
});

it('registra ajustes administrativos y exige motivo', function () {
    $this->actingAs(rrhhUsuario('rrhh'));

    $this->post(route('empleados.store'), [
        'nombre_completo' => 'Pedro Prueba',
        'fecha_contratacion' => now()->subYears(3)->subMonth()->toDateString(),
    ]);

    $empleado = RrhhEmpleado::where('nombre_completo', 'Pedro Prueba')->firstOrFail();

    $this->post(route('vacaciones.guardar'), [
        'empleado_id' => $empleado->id,
        'tipo_movimiento' => 'ajuste_manual',
        'fecha_inicio' => now()->toDateString(),
        'operacion' => 'sumar',
        'dias_manual' => '2.5',
    ])->assertSessionHasErrors('motivo');

    $this->post(route('vacaciones.guardar'), [
        'empleado_id' => $empleado->id,
        'tipo_movimiento' => 'ajuste_manual',
        'fecha_inicio' => now()->toDateString(),
        'operacion' => 'sumar',
        'dias_manual' => '2.5',
        'motivo' => 'Compensación por trabajo en feriado',
    ])->assertSessionHasNoErrors();

    expect(rrhhSaldo($empleado))->toBe(17.5);

    $this->post(route('vacaciones.procesar'))->assertRedirect()->assertSessionHas('success');
});

it('registra días colectivos a los seleccionados y omite a quien ya tiene esas fechas', function () {
    $this->actingAs(rrhhUsuario('rrhh'));

    foreach (['Ana Colectivo', 'Beto Colectivo', 'Caro Colectivo'] as $nombre) {
        $this->post(route('empleados.store'), [
            'nombre_completo' => $nombre,
            'fecha_contratacion' => '2020-09-15',
        ])->assertSessionHasNoErrors();
    }

    [$ana, $beto, $caro] = RrhhEmpleado::orderBy('nombre_completo')->get()->all();

    // Ana ya tiene registrado el jueves santo
    $this->post(route('vacaciones.guardar'), [
        'empleado_id' => $ana->id,
        'tipo_movimiento' => 'vacacion_dias',
        'fecha_inicio' => '2027-03-25',
        'fecha_fin' => '2027-03-25',
    ])->assertSessionHasNoErrors();

    $this->get(route('vacaciones.colectivos.crear'))
        ->assertOk()
        ->assertSee('Semana Santa 2027')
        ->assertSee('Beto Colectivo');

    $this->post(route('vacaciones.colectivos.guardar'), [
        'motivo' => 'Semana Santa 2027',
        'fecha_inicio' => '2027-03-24',
        'fecha_fin' => '2027-03-26',
        'empleados' => [$ana->id, $beto->id],
    ])->assertRedirect(route('vacaciones.index', ['anio' => 2027]))->assertSessionHas('omitidos');

    $movimiento = $beto->movimientosVacaciones()->sole();

    // Los días de 2026 vencen el 31-dic: en marzo de 2027 queda como adelanto
    expect((float) $movimiento->dias_equivalentes)->toBe(3.0)
        ->and($movimiento->motivo)->toBe('Semana Santa 2027')
        ->and($movimiento->es_adelanto)->toBeTrue()
        ->and($ana->movimientosVacaciones()->count())->toBe(1)
        ->and($caro->movimientosVacaciones()->count())->toBe(0)
        ->and($beto->bitacoraVacaciones()->where('accion', 'REGISTRO_COLECTIVO')->exists())->toBeTrue();

    $this->get(route('vacaciones.index', ['anio' => 2027]))->assertOk()->assertSee('Ana Colectivo');
});
