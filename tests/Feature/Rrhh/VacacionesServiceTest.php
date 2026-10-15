<?php

use App\Models\RrhhEmpleado;
use App\Models\RrhhVacacionesBitacora;
use App\Models\RrhhVacacionesMovimiento;
use App\Models\RrhhVacacionesPeriodo;
use App\Services\Rrhh\JornadaLaboral;
use App\Services\Rrhh\VacacionesService;
use Carbon\CarbonImmutable;

function rrhhServicio(): VacacionesService
{
    return app(VacacionesService::class);
}

function rrhhEmpleado(string $contratacion, string $registro, float $acumuladas = 0, ?string $area = null): RrhhEmpleado
{
    $empleado = RrhhEmpleado::create([
        'nombre_completo' => 'Empleado Prueba',
        'fecha_contratacion' => $contratacion,
        'vacaciones_acumuladas' => $acumuladas,
        'area' => $area,
        'estado' => 'activo',
    ]);

    $empleado->forceFill(['created_at' => $registro . ' 08:00:00'])->save();

    return $empleado->fresh();
}

function rrhhMovimiento(RrhhEmpleado $empleado, array $datos): RrhhVacacionesMovimiento
{
    return RrhhVacacionesMovimiento::create(array_merge([
        'empleado_id' => $empleado->id,
        'estado' => 'aprobado',
    ], $datos));
}

function rrhhSincronizar(RrhhEmpleado $empleado, string $hoy): int
{
    return rrhhServicio()->sincronizarEmpleado($empleado->fresh(), CarbonImmutable::parse($hoy));
}

function rrhhEstado(RrhhEmpleado $empleado, string $hoy)
{
    return rrhhServicio()->estadoCuenta(
        $empleado->fresh(['periodosVacaciones', 'movimientosVacaciones']),
        null,
        CarbonImmutable::parse($hoy)
    );
}

it('asigna los días por ley según los años cumplidos', function () {
    $servicio = rrhhServicio();

    expect($servicio->diasPorAntiguedad(0))->toBe(0)
        ->and($servicio->diasPorAntiguedad(1))->toBe(10)
        ->and($servicio->diasPorAntiguedad(2))->toBe(12)
        ->and($servicio->diasPorAntiguedad(3))->toBe(15)
        ->and($servicio->diasPorAntiguedad(4))->toBe(20)
        ->and($servicio->diasPorAntiguedad(9))->toBe(20);
});

it('cuenta años completos de servicio aunque haya fracción de año', function () {
    $empleado = new RrhhEmpleado(['fecha_contratacion' => '2023-06-15']);

    expect(rrhhServicio()->numeroPeriodoEn($empleado, '2026-01-10'))->toBe(2)
        ->and(rrhhServicio()->numeroPeriodoEn($empleado, '2026-06-14'))->toBe(2)
        ->and(rrhhServicio()->numeroPeriodoEn($empleado, '2026-06-15'))->toBe(3)
        ->and(rrhhServicio()->numeroPeriodoEn($empleado, '2023-06-14'))->toBe(-1)
        ->and($empleado->vacacionesPorLey())->toBeInt();
});

it('maneja contrataciones del 29 de febrero', function () {
    $empleado = new RrhhEmpleado(['fecha_contratacion' => '2024-02-29']);

    expect(rrhhServicio()->aniversario($empleado, 1)->toDateString())->toBe('2025-02-28')
        ->and(rrhhServicio()->aniversario($empleado, 4)->toDateString())->toBe('2028-02-29');
});

it('no descuenta domingos en vacaciones por días', function () {
    $jornada = new JornadaLaboral;

    // viernes 16 a martes 20 de octubre de 2026
    expect($jornada->diasDescontables('2026-10-16', '2026-10-20'))->toBe(4)
        ->and($jornada->diasDescontables('2026-10-18', '2026-10-18'))->toBe(0);
});

it('excluye la hora de almuerzo y el tiempo fuera de jornada', function () {
    $jornada = new JornadaLaboral;

    expect($jornada->horasDescontables('10:00', '14:00'))->toBe(3.0)
        ->and($jornada->horasDescontables('08:00', '17:00'))->toBe(8.0)
        ->and($jornada->horasDescontables('12:00', '13:00'))->toBe(0.0)
        ->and($jornada->horasDescontables('07:00', '09:30'))->toBe(1.5)
        ->and($jornada->horasPorDia())->toBe(8.0);
});

it('calcula las cantidades del formulario', function () {
    $servicio = rrhhServicio();

    expect($servicio->calcularCantidades('vacacion_dias', ['fecha_inicio' => '2026-10-16', 'fecha_fin' => '2026-10-20']))
        ->toBe(['dias' => 4.0, 'horas' => 32.0])
        ->and($servicio->calcularCantidades('vacacion_dias', ['fecha_inicio' => '2026-10-16', 'fecha_fin' => '2026-10-20', 'dias_manual' => '3']))
        ->toBe(['dias' => 3.0, 'horas' => 24.0])
        ->and($servicio->calcularCantidades('vacacion_horas', ['fecha_inicio' => '2026-10-16', 'hora_inicio' => '10:00', 'hora_fin' => '14:00']))
        ->toBe(['dias' => 0.38, 'horas' => 3.0])
        ->and($servicio->calcularCantidades('ajuste_manual', ['fecha_inicio' => '2026-10-16', 'dias_manual' => '2', 'operacion' => 'restar']))
        ->toBe(['dias' => -2.0, 'horas' => -16.0]);
});

it('suma los días por ley y el acumulado histórico (caso Juan Ochoa)', function () {
    $empleado = rrhhEmpleado('2025-03-12', '2026-03-20', 5);

    rrhhSincronizar($empleado, '2026-04-01');
    $estado = rrhhEstado($empleado, '2026-04-01');

    expect($estado->saldo)->toBe(15.0)
        ->and($estado->periodoActual['dias_ley'])->toBe(10.0)
        ->and($estado->periodoActual['fin']->toDateString())->toBe('2027-03-11')
        ->and($estado->acumuladoRestante())->toBe(5.0)
        ->and(RrhhVacacionesBitacora::where('empleado_id', $empleado->id)->where('accion', 'ACREDITACION')->count())->toBe(1);
});

it('permite vacaciones adelantadas y las compensa con la acreditación', function () {
    $empleado = rrhhEmpleado('2025-10-14', '2026-05-01');
    rrhhSincronizar($empleado, '2026-05-01');

    rrhhMovimiento($empleado, [
        'tipo_movimiento' => 'vacacion_dias',
        'fecha_inicio' => '2026-09-01',
        'fecha_fin' => '2026-09-03',
        'dias_equivalentes' => 3,
        'horas_equivalentes' => 24,
    ]);

    $antes = rrhhEstado($empleado, '2026-09-10');

    expect($antes->saldo)->toBe(-3.0)
        ->and($antes->deudaGenerada)->toBe(3.0);

    rrhhSincronizar($empleado, '2026-10-14');
    $despues = rrhhEstado($empleado, '2026-10-14');

    expect($despues->saldo)->toBe(7.0)
        ->and($despues->deuda)->toBe(0.0);
});

it('vence lo no tomado el 31 de diciembre y en enero empieza en cero', function () {
    $empleado = rrhhEmpleado('2018-08-10', '2026-03-20', 5);
    rrhhSincronizar($empleado, '2026-11-01');

    expect($empleado->periodosVacaciones()->pluck('numero_periodo')->all())->toBe([7, 8]);

    rrhhMovimiento($empleado, [
        'tipo_movimiento' => 'vacacion_dias',
        'fecha_inicio' => '2026-09-07',
        'fecha_fin' => '2026-09-09',
        'dias_equivalentes' => 3,
        'horas_equivalentes' => 24,
    ]);

    // Lo acreditado en 2025 (antes del primer cierre) vence en el cierre de 2026
    $noviembre = rrhhEstado($empleado, '2026-11-01');

    expect($noviembre->saldo)->toBe(42.0)
        ->and($noviembre->acumuladoRestante())->toBe(2.0)
        ->and($noviembre->proximoVencimiento['fecha']->toDateString())->toBe('2026-12-31')
        ->and($noviembre->proximoVencimiento['dias'])->toBe(42.0);

    $enero = rrhhEstado($empleado, '2027-01-02');
    $cierres = collect($enero->kardex)->where('tipo', 'vencimiento');

    expect($enero->saldo)->toBe(0.0)
        ->and($enero->acumuladoHistorico['vencido'])->toBe(2.0)
        ->and($enero->anio(2026)['vencido'])->toBe(42.0)
        ->and($enero->anio(2027)['saldo_inicial'])->toBe(0.0)
        ->and($cierres)->toHaveCount(1);

    // En 2027 adelanta 5 días en febrero; el aniversario de agosto (+20) los compensa
    rrhhMovimiento($empleado, [
        'tipo_movimiento' => 'vacacion_dias',
        'fecha_inicio' => '2027-02-01',
        'fecha_fin' => '2027-02-05',
        'dias_equivalentes' => 5,
        'horas_equivalentes' => 40,
    ]);

    expect(rrhhEstado($empleado, '2027-03-01')->saldo)->toBe(-5.0);

    rrhhSincronizar($empleado, '2027-08-10');
    $agosto = rrhhEstado($empleado, '2027-08-10');

    expect($agosto->saldo)->toBe(15.0)
        ->and($agosto->proximoVencimiento['fecha']->toDateString())->toBe('2027-12-31')
        ->and(rrhhEstado($empleado, '2028-01-02')->saldo)->toBe(0.0);
});

it('no vence los días de Dirección Ejecutiva', function () {
    $empleado = rrhhEmpleado('2018-08-10', '2026-03-20', 5, 'Dirección Ejecutiva');
    rrhhSincronizar($empleado, '2026-11-01');

    rrhhMovimiento($empleado, [
        'tipo_movimiento' => 'vacacion_dias',
        'fecha_inicio' => '2026-09-07',
        'fecha_fin' => '2026-09-09',
        'dias_equivalentes' => 3,
        'horas_equivalentes' => 24,
    ]);

    $enero = rrhhEstado($empleado, '2027-01-02');

    expect($enero->saldo)->toBe(42.0)
        ->and($enero->proximoVencimiento)->toBeNull()
        ->and($enero->acumuladoHistorico['vence'])->toBeNull();

    rrhhSincronizar($empleado, '2027-08-10');

    expect(rrhhEstado($empleado, '2028-01-02')->saldo)->toBe(62.0);
});

it('permite adelantar en el primer año y el negativo pasa al año siguiente', function () {
    $empleado = rrhhEmpleado('2026-11-15', '2026-11-15');
    rrhhSincronizar($empleado, '2026-11-20');

    rrhhMovimiento($empleado, ['tipo_movimiento' => 'vacacion_dias', 'fecha_inicio' => '2026-12-24', 'fecha_fin' => '2026-12-24', 'dias_equivalentes' => 1, 'horas_equivalentes' => 8]);
    rrhhMovimiento($empleado, ['tipo_movimiento' => 'vacacion_dias', 'fecha_inicio' => '2026-12-31', 'fecha_fin' => '2026-12-31', 'dias_equivalentes' => 1, 'horas_equivalentes' => 8]);

    $enero = rrhhEstado($empleado, '2027-01-02');

    expect($enero->saldo)->toBe(-2.0)
        ->and($enero->anio(2026)['saldo_final'])->toBe(-2.0)
        ->and($enero->anio(2027)['saldo_inicial'])->toBe(-2.0);

    rrhhSincronizar($empleado, '2027-11-15');
    $aniversario = rrhhEstado($empleado, '2027-11-15');

    expect($aniversario->saldo)->toBe(8.0)
        ->and($aniversario->proximoVencimiento['fecha']->toDateString())->toBe('2027-12-31');
});

it('calcula la Semana Santa de cada año', function () {
    $jornada = new JornadaLaboral;

    expect($jornada->domingoDeResurreccion(2026)->toDateString())->toBe('2026-04-05')
        ->and($jornada->domingoDeResurreccion(2027)->toDateString())->toBe('2027-03-28');
});

it('no descuenta permisos especiales ni movimientos anulados; los ajustes suman o restan', function () {
    $empleado = rrhhEmpleado('2020-01-15', '2026-03-01');
    rrhhSincronizar($empleado, '2026-03-01');

    rrhhMovimiento($empleado, ['tipo_movimiento' => 'permiso_especial', 'fecha_inicio' => '2026-03-02', 'fecha_fin' => '2026-03-02', 'dias_equivalentes' => 1, 'horas_equivalentes' => 8]);
    rrhhMovimiento($empleado, ['tipo_movimiento' => 'vacacion_dias', 'estado' => 'anulado', 'fecha_inicio' => '2026-03-03', 'fecha_fin' => '2026-03-04', 'dias_equivalentes' => 2, 'horas_equivalentes' => 16]);
    rrhhMovimiento($empleado, ['tipo_movimiento' => 'ajuste_manual', 'fecha_inicio' => '2026-03-05', 'dias_equivalentes' => 1.5, 'horas_equivalentes' => 12]);
    rrhhMovimiento($empleado, ['tipo_movimiento' => 'ajuste_manual', 'fecha_inicio' => '2026-03-06', 'dias_equivalentes' => -0.5, 'horas_equivalentes' => -4]);

    expect(rrhhEstado($empleado, '2026-03-10')->saldo)->toBe(21.0);
});

it('convierte las horas en días con la jornada de 8 horas sin perder precisión', function () {
    $empleado = rrhhEmpleado('2020-01-15', '2026-03-01');
    rrhhSincronizar($empleado, '2026-03-01');

    rrhhMovimiento($empleado, [
        'tipo_movimiento' => 'vacacion_horas',
        'fecha_inicio' => '2026-03-02',
        'fecha_fin' => '2026-03-02',
        'hora_inicio' => '10:00',
        'hora_fin' => '14:00',
        'dias_equivalentes' => 0.38,
        'horas_equivalentes' => 3,
    ]);

    expect(rrhhEstado($empleado, '2026-03-10')->saldo)->toBe(19.625)
        ->and(VacacionesService::formatoDias(19.625))->toBe('19 d 5 h');
});

it('acredita una sola vez aunque el proceso se ejecute varias veces', function () {
    $empleado = rrhhEmpleado('2025-03-12', '2026-03-20');

    rrhhSincronizar($empleado, '2026-04-01');
    rrhhSincronizar($empleado, '2026-04-01');
    rrhhServicio()->sincronizarTodos(CarbonImmutable::parse('2026-04-01'));

    expect(RrhhVacacionesPeriodo::where('empleado_id', $empleado->id)->count())->toBe(1)
        ->and(RrhhVacacionesBitacora::where('empleado_id', $empleado->id)->where('accion', 'ACREDITACION')->count())->toBe(1)
        ->and(rrhhServicio()->requiereSincronizacion($empleado->fresh('periodosVacaciones'), CarbonImmutable::parse('2026-04-01')))->toBeFalse()
        ->and(rrhhServicio()->requiereSincronizacion($empleado->fresh('periodosVacaciones'), CarbonImmutable::parse('2027-03-12')))->toBeTrue();
});

it('no pide acreditar a quien está en su primer año (periodo 0)', function () {
    $empleado = rrhhEmpleado('2025-12-01', '2026-03-16');
    rrhhSincronizar($empleado, '2026-10-14');

    expect($empleado->periodosVacaciones()->pluck('numero_periodo')->all())->toBe([0])
        ->and(rrhhServicio()->requiereSincronizacion($empleado->fresh('periodosVacaciones'), CarbonImmutable::parse('2026-10-14')))->toBeFalse()
        ->and(rrhhServicio()->requiereSincronizacion($empleado->fresh('periodosVacaciones'), CarbonImmutable::parse('2026-12-01')))->toBeTrue();
});

it('convierte los periodos por año calendario en periodos laborales', function () {
    $empleado = rrhhEmpleado('2018-08-10', '2026-03-20', 5);

    $calendario = RrhhVacacionesPeriodo::create([
        'empleado_id' => $empleado->id,
        'anio' => 2026,
        'dias_correspondientes' => 20,
        'acumulado_anterior' => 5,
        'total_disponible' => 25,
        'total_tomado' => 3,
        'dias_pendientes' => 22,
    ]);

    $movimiento = rrhhMovimiento($empleado, [
        'periodo_vacacion_id' => $calendario->id,
        'tipo_movimiento' => 'vacacion_dias',
        'fecha_inicio' => '2026-04-06',
        'fecha_fin' => '2026-04-08',
        'dias_equivalentes' => 3,
        'horas_equivalentes' => 24,
    ]);

    rrhhSincronizar($empleado, '2026-10-14');

    $periodos = RrhhVacacionesPeriodo::where('empleado_id', $empleado->id)->orderBy('numero_periodo')->get();

    expect($periodos->pluck('numero_periodo')->all())->toBe([7, 8])
        ->and($movimiento->fresh()->periodo_vacacion_id)->toBe($periodos[0]->id)
        ->and(RrhhVacacionesBitacora::where('empleado_id', $empleado->id)->where('accion', 'CONVERSION_PERIODOS')->exists())->toBeTrue()
        ->and(rrhhEstado($empleado, '2026-10-14')->saldo)->toBe(42.0);
});

it('recalcula los periodos al cambiar la fecha de contratación', function () {
    $empleado = rrhhEmpleado('2025-03-12', '2026-03-20');
    rrhhSincronizar($empleado, '2026-04-01');

    $empleado->update(['fecha_contratacion' => '2023-03-12']);
    rrhhSincronizar($empleado, '2026-04-01');

    $periodo = RrhhVacacionesPeriodo::where('empleado_id', $empleado->id)->sole();

    expect($periodo->numero_periodo)->toBe(3)
        ->and((float) $periodo->dias_correspondientes)->toBe(15.0);
});

it('no exige autorizar adelanto si el aniversario llega antes de las vacaciones', function () {
    $empleado = rrhhEmpleado('2025-10-14', '2026-05-01');
    rrhhSincronizar($empleado, '2026-09-01');
    $empleado = $empleado->fresh(['periodosVacaciones', 'movimientosVacaciones']);

    $noviembre = new RrhhVacacionesMovimiento([
        'empleado_id' => $empleado->id,
        'estado' => 'aprobado',
        'tipo_movimiento' => 'vacacion_dias',
        'fecha_inicio' => '2026-11-02',
        'fecha_fin' => '2026-11-04',
        'dias_equivalentes' => 3,
    ]);

    $septiembre = new RrhhVacacionesMovimiento([
        'empleado_id' => $empleado->id,
        'estado' => 'aprobado',
        'tipo_movimiento' => 'vacacion_dias',
        'fecha_inicio' => '2026-09-14',
        'fecha_fin' => '2026-09-16',
        'dias_equivalentes' => 3,
    ]);

    $hoy = CarbonImmutable::parse('2026-09-01');

    expect(rrhhServicio()->evaluarAdelanto($empleado, $noviembre, $hoy)['requiere'])->toBe(0.0)
        ->and(rrhhServicio()->evaluarAdelanto($empleado, $septiembre, $hoy))->toBe(['requiere' => 3.0, 'propio' => 3.0]);
});
