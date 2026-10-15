<?php

namespace App\Services\Rrhh;

use App\Models\RrhhEmpleado;
use App\Models\RrhhVacacionesBitacora;
use App\Models\RrhhVacacionesMovimiento;
use App\Models\RrhhVacacionesPeriodo;
use Carbon\CarbonImmutable;
use DateTimeInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Lógica de vacaciones de CCISUR.
 *
 * - Al cumplir cada aniversario de contratación se acreditan los días por ley
 *   (10, 12, 15, 20). Los periodos laborales (de aniversario a aniversario)
 *   son el registro de esas acreditaciones.
 * - El saldo se calcula a partir de los movimientos, nunca de un campo guardado:
 *   acreditaciones y acumulado histórico suman; vacaciones por días/horas y
 *   ajustes negativos restan; los permisos especiales solo se registran.
 * - Año calendario: lo acreditado en un año vence el 31 de diciembre de ese
 *   año (lo acreditado antes del primer cierre, el 31-dic-2026, vence ese día).
 *   Las áreas sin vencimiento (Dirección Ejecutiva) acumulan siempre.
 * - Al tomar vacaciones se descuentan primero los días que vencen antes.
 * - Se puede adelantar: si no hay saldo, queda negativo y se compensa con la
 *   siguiente acreditación, aunque sea del año siguiente (la deuda no vence).
 */
class VacacionesService
{
    private const EPSILON = 0.00001;

    public function __construct(private JornadaLaboral $jornada) {}

    /*
    |--------------------------------------------------------------------------
    | Reglas por antigüedad
    |--------------------------------------------------------------------------
    */

    public function diasPorAntiguedad(int $anios): int
    {
        $dias = 0;

        foreach (config('rrhh.vacaciones.dias_por_antiguedad') as $desde => $valor) {
            if ($anios >= $desde) {
                $dias = $valor;
            }
        }

        return $dias;
    }

    /**
     * Fecha del aniversario número $numero (0 = fecha de contratación).
     * Un ingreso del 29-feb cumple años el 28-feb en años no bisiestos.
     */
    public function aniversario(RrhhEmpleado $empleado, int $numero): CarbonImmutable
    {
        return $this->fecha($empleado->fecha_contratacion)->addYearsNoOverflow($numero);
    }

    /**
     * Años completos de servicio cumplidos a una fecha (-1 si aún no ingresaba).
     */
    public function numeroPeriodoEn(RrhhEmpleado $empleado, DateTimeInterface|string $fecha): int
    {
        $fecha = $this->fecha($fecha);
        $contratacion = $this->fecha($empleado->fecha_contratacion);

        if ($fecha->lt($contratacion)) {
            return -1;
        }

        $numero = $fecha->year - $contratacion->year;

        if ($this->aniversario($empleado, $numero)->gt($fecha)) {
            $numero--;
        }

        return $numero;
    }

    public function aniosServicio(RrhhEmpleado $empleado, DateTimeInterface|string|null $fecha = null): int
    {
        return max(0, $this->numeroPeriodoEn($empleado, $fecha ?? CarbonImmutable::today()));
    }

    /*
    |--------------------------------------------------------------------------
    | Vencimientos
    |--------------------------------------------------------------------------
    */

    /**
     * Primer cierre anual (31-dic-2026).
     */
    public function primerCierre(): CarbonImmutable
    {
        return $this->fecha(config('rrhh.vacaciones.primer_cierre'));
    }

    /**
     * Los empleados de estas áreas no pierden días: se les acumulan.
     */
    public function sinVencimiento(RrhhEmpleado $empleado): bool
    {
        return in_array($empleado->area, config('rrhh.vacaciones.areas_sin_vencimiento', []), true);
    }

    /**
     * Último día para tomar días acreditados en $fecha: el 31 de diciembre de
     * ese año (o el primer cierre si se acreditaron antes). Null = no vencen.
     */
    public function vencimientoCredito(RrhhEmpleado $empleado, DateTimeInterface|string $fecha): ?CarbonImmutable
    {
        if ($this->sinVencimiento($empleado)) {
            return null;
        }

        $cierre = $this->fecha($fecha)->endOfYear()->startOfDay();

        return $cierre->max($this->primerCierre());
    }

    /**
     * Vencimiento de las vacaciones acumuladas (histórico): el primer cierre.
     */
    public function vencimientoAcumuladoHistorico(?RrhhEmpleado $empleado = null): ?CarbonImmutable
    {
        return $empleado && $this->sinVencimiento($empleado) ? null : $this->primerCierre();
    }

    /**
     * Último día para tomar los días acreditados al cumplir el aniversario $numero.
     */
    public function vencimientoPeriodo(RrhhEmpleado $empleado, int $numero): ?CarbonImmutable
    {
        return $this->vencimientoCredito($empleado, $this->aniversario($empleado, $numero));
    }

    /**
     * Próxima acreditación por aniversario: [fecha, dias, anios].
     */
    public function proximaAcreditacion(RrhhEmpleado $empleado, ?CarbonImmutable $hoy = null): array
    {
        $numero = max(0, $this->numeroPeriodoEn($empleado, $hoy ?? CarbonImmutable::today())) + 1;

        return [
            'fecha' => $this->aniversario($empleado, $numero),
            'dias' => $this->diasPorAntiguedad($numero),
            'anios' => $numero,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Periodos y acreditación
    |--------------------------------------------------------------------------
    */

    /**
     * Fecha desde la que el sistema lleva el control del empleado (su registro).
     * El primer periodo controlado es el que contiene esta fecha; lo anterior
     * se representa con el campo "vacaciones acumuladas".
     */
    public function inicioControl(RrhhEmpleado $empleado): CarbonImmutable
    {
        $registro = $this->fecha($empleado->created_at ?? CarbonImmutable::today());

        return $registro->max($this->fecha($empleado->fecha_contratacion));
    }

    /**
     * Rango [desde, hasta] de números de periodo que deben existir a la fecha.
     */
    public function rangoPeriodos(RrhhEmpleado $empleado, ?CarbonImmutable $hoy = null): ?array
    {
        $hoy ??= CarbonImmutable::today();

        $hasta = $this->numeroPeriodoEn($empleado, $this->fechaLimite($empleado, $hoy));

        if ($hasta < 0) {
            return null;
        }

        $desde = max(0, $this->numeroPeriodoEn($empleado, $this->inicioControl($empleado)));

        return [min($desde, $hasta), $hasta];
    }

    /**
     * Indica si al empleado le faltan acreditaciones o tiene periodos del
     * esquema anterior (por año calendario).
     */
    public function requiereSincronizacion(RrhhEmpleado $empleado, ?CarbonImmutable $hoy = null): bool
    {
        $rango = $this->rangoPeriodos($empleado, $hoy);
        $numeros = $empleado->periodosVacaciones->pluck('numero_periodo');

        if (! $rango) {
            return $numeros->isNotEmpty();
        }

        // Comparación estricta: el periodo 0 no es un periodo sin número
        if ($numeros->containsStrict(null)) {
            return true;
        }

        return $numeros->map(fn ($n) => (int) $n)->sort()->values()->all() !== range($rango[0], $rango[1]);
    }

    /**
     * Crea los periodos laborales que falten (acreditación por aniversario),
     * corrige los que no coincidan con la fecha de contratación, enlaza los
     * movimientos con su periodo y recalcula saldos. Es idempotente: ejecutarlo
     * varias veces no duplica acreditaciones.
     *
     * Devuelve la cantidad de acreditaciones nuevas.
     */
    public function sincronizarEmpleado(RrhhEmpleado $empleado, ?CarbonImmutable $hoy = null, string $usuario = 'Sistema'): int
    {
        $hoy ??= CarbonImmutable::today();

        return DB::transaction(function () use ($empleado, $hoy, $usuario) {
            $existentes = RrhhVacacionesPeriodo::where('empleado_id', $empleado->id)->lockForUpdate()->get();
            $esperados = $this->periodosEsperados($empleado, $hoy);

            $numerosPrevios = $existentes->pluck('numero_periodo')
                ->filter(fn ($n) => $n !== null)
                ->map(fn ($n) => (int) $n)
                ->all();
            $esConversion = $existentes->contains(fn ($p) => $p->numero_periodo === null);

            $coinciden = $existentes->every(function ($periodo) use ($esperados) {
                $esperado = $periodo->numero_periodo === null ? null : ($esperados[$periodo->numero_periodo] ?? null);

                return $esperado
                    && (int) $periodo->anio === $esperado['anio']
                    && $periodo->fecha_inicio?->toDateString() === $esperado['fecha_inicio'];
            });

            if (! $coinciden) {
                // Los periodos son datos derivados: se reconstruyen desde cero.
                RrhhVacacionesMovimiento::where('empleado_id', $empleado->id)->update(['periodo_vacacion_id' => null]);
                RrhhVacacionesPeriodo::where('empleado_id', $empleado->id)->delete();
                $existentes = collect();
            }

            $acreditados = [];

            foreach ($esperados as $numero => $datos) {
                $periodo = $existentes->firstWhere('numero_periodo', $numero)
                    ?? new RrhhVacacionesPeriodo(['empleado_id' => $empleado->id]);

                $periodo->fill($datos + ['cerrado' => $datos['fecha_fin'] < $hoy->toDateString()]);

                if (! $periodo->exists && ! in_array($numero, $numerosPrevios, true)) {
                    $acreditados[] = $periodo;
                }

                if ($periodo->isDirty()) {
                    $periodo->save();
                }
            }

            $this->enlazarMovimientos($empleado);

            if ($esConversion) {
                RrhhVacacionesBitacora::registrar(
                    $empleado->id,
                    'CONVERSION_PERIODOS',
                    'Periodos por año calendario reemplazados por periodos laborales (de aniversario a aniversario): '
                        . collect($esperados)->map(fn ($p) => 'periodo ' . $p['numero_periodo'] . ' ('
                            . $this->fecha($p['fecha_inicio'])->format('d-m-Y') . ' al '
                            . $this->fecha($p['fecha_fin'])->format('d-m-Y') . '): '
                            . $p['dias_correspondientes'] . ' días')->implode('; ') . '.',
                    null,
                    $usuario
                );
            }

            $nuevas = 0;

            foreach ($acreditados as $periodo) {
                if ($periodo->dias_correspondientes <= 0 || $esConversion) {
                    continue;
                }

                $nuevas++;

                RrhhVacacionesBitacora::registrar(
                    $empleado->id,
                    'ACREDITACION',
                    'Acreditación por aniversario: +' . self::numero($periodo->dias_correspondientes) . ' días por '
                        . "{$periodo->numero_periodo} año(s) de servicio. Periodo "
                        . $periodo->fecha_inicio->format('d-m-Y') . ' al ' . $periodo->fecha_fin->format('d-m-Y')
                        . ($periodo->fecha_vencimiento
                            ? '. Vencen el ' . $periodo->fecha_vencimiento->format('d-m-Y') . ' si no se toman.'
                            : '. Su área no tiene vencimiento: se acumulan.'),
                    null,
                    $usuario
                );
            }

            $this->recalcular($empleado, $hoy);

            return $nuevas;
        });
    }

    /**
     * Sincroniza a todos los empleados. Devuelve [empleados con acreditación nueva, acreditaciones].
     */
    public function sincronizarTodos(?CarbonImmutable $hoy = null, string $usuario = 'Sistema'): array
    {
        $resultado = ['empleados' => 0, 'acreditaciones' => 0];

        RrhhEmpleado::orderBy('id')->each(function (RrhhEmpleado $empleado) use ($hoy, $usuario, &$resultado) {
            $nuevas = $this->sincronizarEmpleado($empleado, $hoy, $usuario);

            if ($nuevas > 0) {
                $resultado['empleados']++;
                $resultado['acreditaciones'] += $nuevas;
            }
        });

        return $resultado;
    }

    /**
     * Guarda en cada periodo los totales calculados (solo como referencia;
     * las vistas siempre calculan el saldo con estadoCuenta()).
     */
    public function recalcular(RrhhEmpleado $empleado, ?CarbonImmutable $hoy = null): EstadoCuentaVacaciones
    {
        $empleado->load(['periodosVacaciones', 'movimientosVacaciones']);

        $estado = $this->estadoCuenta($empleado, null, $hoy);

        foreach ($empleado->periodosVacaciones as $periodo) {
            $resumen = $estado->periodos[$periodo->numero_periodo] ?? null;

            if (! $resumen) {
                continue;
            }

            $periodo->fill([
                'acumulado_anterior' => round($resumen['saldo_inicial'], 2),
                'total_disponible' => round($resumen['saldo_inicial'] + $resumen['acreditado'], 2),
                'total_tomado' => round($resumen['tomado'], 2),
                'dias_vencidos' => round($resumen['vencido'], 2),
                'dias_pendientes' => round($resumen['saldo_final'], 2),
            ]);

            if ($periodo->isDirty()) {
                $periodo->save();
            }
        }

        return $estado;
    }

    /*
    |--------------------------------------------------------------------------
    | Estado de cuenta
    |--------------------------------------------------------------------------
    */

    /**
     * Calcula el saldo y el historial (kardex) de un empleado.
     *
     * $movimientos permite simular el resultado con un movimiento nuevo o
     * editado sin guardarlo (se usa para detectar vacaciones adelantadas).
     */
    public function estadoCuenta(
        RrhhEmpleado $empleado,
        ?Collection $movimientos = null,
        ?CarbonImmutable $hoy = null,
        ?Collection $periodos = null,
    ): EstadoCuentaVacaciones {
        $hoy ??= CarbonImmutable::today();

        $periodos = ($periodos ?? $empleado->periodosVacaciones)
            ->filter(fn ($p) => $p->numero_periodo !== null)
            ->sortBy('numero_periodo')
            ->values();

        $movimientos = ($movimientos ?? $empleado->movimientosVacaciones)
            ->filter(fn ($m) => $m->estado === 'aprobado')
            ->sort(fn ($a, $b) => [$a->fecha_inicio->toDateString(), $a->id ?? PHP_INT_MAX]
                <=> [$b->fecha_inicio->toDateString(), $b->id ?? PHP_INT_MAX])
            ->values();

        [$eventos, $definiciones] = $this->construirEventos($empleado, $periodos, $movimientos);

        $bolsas = [];
        $saldo = 0.0;
        $deuda = 0.0;
        $deudaGenerada = 0.0;
        $kardex = [];

        foreach ($eventos as $evento) {
            $credito = $debito = $vencido = $adelanto = 0.0;

            if ($evento['accion'] === 'credito') {
                $credito = $evento['dias'];
                $pago = min($deuda, $credito);
                $deuda -= $pago;

                $bolsas[$evento['bolsa']] = $definiciones[$evento['bolsa']] + [
                    'acreditado' => $credito,
                    'usado' => $pago,
                    'vencido' => 0.0,
                    'restante' => $credito - $pago,
                ];

                $saldo += $credito;
            } elseif ($evento['accion'] === 'debito') {
                $debito = $evento['dias'];
                $pendiente = $debito;

                // Se descuentan primero los días que vencen antes; solo sirven
                // los que siguen vigentes en la fecha del movimiento.
                $vigentes = array_filter(
                    array_keys($bolsas),
                    fn ($id) => $bolsas[$id]['restante'] > self::EPSILON
                        && ($bolsas[$id]['vence'] === null || $bolsas[$id]['vence']->gte($evento['fecha']))
                );
                usort($vigentes, fn ($a, $b) => [$bolsas[$a]['vence']?->toDateString() ?? '9999-12-31', $a]
                    <=> [$bolsas[$b]['vence']?->toDateString() ?? '9999-12-31', $b]);

                foreach ($vigentes as $id) {
                    $toma = min($bolsas[$id]['restante'], $pendiente);
                    $bolsas[$id]['restante'] -= $toma;
                    $bolsas[$id]['usado'] += $toma;
                    $pendiente -= $toma;

                    if ($pendiente <= self::EPSILON) {
                        break;
                    }
                }

                if ($pendiente > self::EPSILON) {
                    $adelanto = $pendiente;
                    $deuda += $pendiente;
                    $deudaGenerada += $pendiente;
                }

                $saldo -= $debito;
            } elseif ($evento['accion'] === 'vencimiento') {
                // Los días se pueden tomar hasta el día de vencimiento inclusive.
                if (! $evento['fecha']->lt($hoy) || ! isset($bolsas[$evento['bolsa']])) {
                    continue;
                }

                $vencido = $bolsas[$evento['bolsa']]['restante'];

                if ($vencido <= self::EPSILON) {
                    continue;
                }

                $bolsas[$evento['bolsa']]['restante'] = 0.0;
                $bolsas[$evento['bolsa']]['vencido'] = $vencido;
                $saldo -= $vencido;

                // Un solo renglón por cierre aunque venzan varias acreditaciones
                $ultimo = array_key_last($kardex);

                if ($ultimo !== null && $kardex[$ultimo]['tipo'] === 'vencimiento'
                    && $kardex[$ultimo]['fecha']->equalTo($evento['fecha'])) {
                    $kardex[$ultimo]['vencido'] = round($kardex[$ultimo]['vencido'] + $vencido, 4);
                    $kardex[$ultimo]['saldo'] = round($saldo, 4);

                    continue;
                }
            }

            $kardex[] = [
                'fecha' => $evento['fecha'],
                'tipo' => $evento['tipo'],
                'concepto' => $evento['concepto'],
                'credito' => round($credito, 4),
                'debito' => round($debito, 4),
                'vencido' => round($vencido, 4),
                'saldo' => round($saldo, 4),
                'programado' => $evento['fecha']->gt($hoy),
                'adelanto' => round($adelanto, 4),
                'movimiento_id' => $evento['movimiento_id'],
            ];
        }

        $resumen = $this->resumirPeriodos($empleado, $periodos, $kardex);
        $anios = $this->resumirAnios($empleado, $periodos, $kardex, $hoy);

        $bolsas = array_values(array_map(fn ($b) => [
            'origen' => $b['origen'],
            'etiqueta' => $b['etiqueta'],
            'fecha' => $b['fecha'],
            'vence' => $b['vence'],
            'acreditado' => round($b['acreditado'], 4),
            'usado' => round($b['usado'], 4),
            'vencido' => round($b['vencido'], 4),
            'restante' => round($b['restante'], 4),
        ], $bolsas));

        $vigentes = array_filter($bolsas, fn ($b) => $b['restante'] > self::EPSILON && $b['vence'] !== null);
        $proximo = null;

        if ($vigentes) {
            $fecha = min(array_map(fn ($b) => $b['vence']->toDateString(), $vigentes));
            $proximo = [
                'fecha' => $this->fecha($fecha),
                'dias' => round(array_sum(array_map(
                    fn ($b) => $b['vence']->toDateString() === $fecha ? $b['restante'] : 0,
                    $vigentes
                )), 4),
            ];
        }

        $actual = collect($resumen)->first(fn ($p) => $p['inicio']->lte($hoy) && $p['fin']->gte($hoy))
            ?? (collect($resumen)->last() ?: null);

        return new EstadoCuentaVacaciones(
            saldo: round($saldo, 4),
            deuda: round($deuda, 4),
            deudaGenerada: round($deudaGenerada, 4),
            kardex: $kardex,
            bolsas: $bolsas,
            periodos: $resumen,
            anios: $anios,
            periodoActual: $actual,
            acumuladoHistorico: collect($bolsas)->firstWhere('origen', 'historico'),
            proximoVencimiento: $proximo,
        );
    }

    /**
     * Revisa si un movimiento nuevo o editado deja días sin cubrir con saldo
     * (vacaciones adelantadas). Toma en cuenta las acreditaciones por
     * aniversario que ocurrirán antes de la fecha del movimiento.
     *
     * - requiere: días adicionales sin saldo que genera el cambio frente a lo
     *   guardado; si es mayor que cero debe autorizarse como adelanto.
     * - propio: días del propio movimiento que quedan sin cubrir.
     *
     * @return array{requiere: float, propio: float}
     */
    public function evaluarAdelanto(RrhhEmpleado $empleado, RrhhVacacionesMovimiento $movimiento, ?CarbonImmutable $hoy = null): array
    {
        $hoy ??= CarbonImmutable::today();

        $empleado->loadMissing(['periodosVacaciones', 'movimientosVacaciones']);

        $otros = $empleado->movimientosVacaciones
            ->reject(fn ($m) => $movimiento->exists && $m->id === $movimiento->id)
            ->values();
        $guardado = $movimiento->exists
            ? $empleado->movimientosVacaciones->firstWhere('id', $movimiento->id)
            : null;

        $hasta = $this->fecha($movimiento->fecha_inicio)->max($hoy);

        if ($guardado) {
            $hasta = $hasta->max($this->fecha($guardado->fecha_inicio));
        }

        $periodos = $this->periodosProyectados($empleado, $hasta);

        $antes = $this->estadoCuenta($empleado, $guardado ? $otros->concat([$guardado]) : $otros, $hoy, $periodos);
        $despues = $this->estadoCuenta($empleado, $otros->concat([$movimiento]), $hoy, $periodos);

        $fila = collect($despues->kardex)->first(fn ($f) => $movimiento->exists
            ? $f['movimiento_id'] === $movimiento->id
            : $f['movimiento_id'] === null && $f['tipo'] === $movimiento->tipo_movimiento);

        return [
            'requiere' => max(0.0, round($despues->deudaGenerada - $antes->deudaGenerada, 4)),
            'propio' => $fila['adelanto'] ?? 0.0,
        ];
    }

    /**
     * Movimiento aprobado que se cruza en fechas (u horas, si ambos son por
     * horas) con $movimiento. Los ajustes no se cruzan con nada.
     */
    public function buscarTraslape(RrhhEmpleado $empleado, RrhhVacacionesMovimiento $movimiento): ?RrhhVacacionesMovimiento
    {
        if ($movimiento->tipo_movimiento === 'ajuste_manual') {
            return null;
        }

        $inicio = $movimiento->fecha_inicio->toDateString();
        $fin = ($movimiento->fecha_fin ?? $movimiento->fecha_inicio)->toDateString();

        $candidatos = RrhhVacacionesMovimiento::where('empleado_id', $empleado->id)
            ->where('estado', 'aprobado')
            ->where('tipo_movimiento', '!=', 'ajuste_manual')
            ->when($movimiento->exists, fn ($q) => $q->where('id', '!=', $movimiento->id))
            ->whereDate('fecha_inicio', '<=', $fin)
            ->where(fn ($q) => $q->whereDate('fecha_fin', '>=', $inicio)
                ->orWhere(fn ($q) => $q->whereNull('fecha_fin')->whereDate('fecha_inicio', '>=', $inicio)))
            ->get();

        foreach ($candidatos as $existente) {
            // Dos movimientos por horas el mismo día solo chocan si se cruzan las horas
            if ($movimiento->esPorHoras() && $existente->esPorHoras()) {
                if ($movimiento->horaInicioCorta() < $existente->horaFinCorta()
                    && $movimiento->horaFinCorta() > $existente->horaInicioCorta()) {
                    return $existente;
                }

                continue;
            }

            return $existente;
        }

        return null;
    }

    /**
     * Días que el movimiento resta del saldo (negativo si suma).
     */
    public function diasADescontar(RrhhVacacionesMovimiento $movimiento): float
    {
        return match ($movimiento->tipo_movimiento) {
            'vacacion_dias' => (float) $movimiento->dias_equivalentes,
            'vacacion_horas' => (float) $movimiento->horas_equivalentes / $this->jornada->horasPorDia(),
            // En los ajustes el valor guardado es el efecto sobre el saldo (+ suma, - resta)
            'ajuste_manual' => -1 * (float) $movimiento->dias_equivalentes,
            default => 0.0,
        };
    }

    /**
     * Calcula días y horas equivalentes de un movimiento a partir del formulario.
     * dias_manual / horas_manual reemplazan el cálculo automático.
     *
     * @return array{dias: float, horas: float}
     */
    public function calcularCantidades(string $tipo, array $datos): array
    {
        $horasDia = $this->jornada->horasPorDia();
        $diasManual = $this->numeroOpcional($datos['dias_manual'] ?? null);
        $horasManual = $this->numeroOpcional($datos['horas_manual'] ?? null);
        $porHoras = $tipo === 'vacacion_horas'
            || ($tipo === 'permiso_especial' && ! empty($datos['hora_inicio']) && ! empty($datos['hora_fin']));

        if ($tipo === 'ajuste_manual') {
            $dias = ($diasManual ?? 0) * (($datos['operacion'] ?? 'sumar') === 'restar' ? -1 : 1);

            return ['dias' => round($dias, 2), 'horas' => round($dias * $horasDia, 2)];
        }

        if ($porHoras) {
            $horas = $horasManual ?? $this->jornada->horasDescontables($datos['hora_inicio'], $datos['hora_fin']);

            return ['dias' => round($horas / $horasDia, 2), 'horas' => round($horas, 2)];
        }

        $dias = $diasManual ?? $this->jornada->diasDescontables(
            $datos['fecha_inicio'],
            ($datos['fecha_fin'] ?? null) ?: $datos['fecha_inicio']
        );

        return ['dias' => round($dias, 2), 'horas' => round($dias * $horasDia, 2)];
    }

    /**
     * Formatea días como "9 d 5 h" usando la jornada de 8 horas.
     */
    public static function formatoDias(float $dias): string
    {
        $horasDia = (new JornadaLaboral)->horasPorDia();
        $horasTotales = round(abs($dias) * $horasDia, 2);
        $enteros = (int) floor($horasTotales / $horasDia + self::EPSILON);
        $horas = round($horasTotales - $enteros * $horasDia, 2);

        $texto = $enteros . ' d';

        if ($horas > 0) {
            $texto .= ' ' . rtrim(rtrim(number_format($horas, 2, '.', ''), '0'), '.') . ' h';
        }

        return ($dias < -self::EPSILON ? '-' : '') . $texto;
    }

    /**
     * Separa días en días completos y horas restantes (8 h = 1 día).
     * Ej.: 15.625 => ['dias' => 15, 'horas' => 5.0, 'negativo' => false].
     *
     * @return array{dias: int, horas: float, negativo: bool}
     */
    public static function partesDias(float $dias): array
    {
        $horasDia = (new JornadaLaboral)->horasPorDia();
        $horasTotales = round(abs($dias) * $horasDia, 2);
        $enteros = (int) floor($horasTotales / $horasDia + self::EPSILON);

        return [
            'dias' => $enteros,
            'horas' => round($horasTotales - $enteros * $horasDia, 2),
            'negativo' => $dias < -self::EPSILON,
        ];
    }

    /**
     * Número sin ceros sobrantes: 10.00 => "10", 2.50 => "2.5".
     */
    public static function numero(float|string|null $valor): string
    {
        return rtrim(rtrim(number_format((float) $valor, 2, '.', ''), '0'), '.');
    }

    /*
    |--------------------------------------------------------------------------
    | Internos
    |--------------------------------------------------------------------------
    */

    private function periodosEsperados(RrhhEmpleado $empleado, CarbonImmutable $hoy): array
    {
        $rango = $this->rangoPeriodos($empleado, $hoy);

        if (! $rango) {
            return [];
        }

        $periodos = [];

        for ($numero = $rango[0]; $numero <= $rango[1]; $numero++) {
            $inicio = $this->aniversario($empleado, $numero);

            $periodos[$numero] = [
                'numero_periodo' => $numero,
                'anio' => $inicio->year,
                'fecha_inicio' => $inicio->toDateString(),
                'fecha_fin' => $this->aniversario($empleado, $numero + 1)->subDay()->toDateString(),
                'fecha_vencimiento' => $this->vencimientoPeriodo($empleado, $numero)?->toDateString(),
                'dias_correspondientes' => $this->diasPorAntiguedad($numero),
            ];
        }

        return $periodos;
    }

    /**
     * Periodos guardados más los que se crearían hasta $hasta (sin guardarlos).
     */
    private function periodosProyectados(RrhhEmpleado $empleado, CarbonImmutable $hasta): Collection
    {
        $periodos = $empleado->periodosVacaciones
            ->filter(fn ($p) => $p->numero_periodo !== null)
            ->keyBy('numero_periodo');

        foreach ($this->periodosEsperados($empleado, $hasta) as $numero => $datos) {
            if (! $periodos->has($numero)) {
                $periodos->put($numero, new RrhhVacacionesPeriodo($datos + ['empleado_id' => $empleado->id]));
            }
        }

        return $periodos->sortKeys()->values();
    }

    /**
     * Hasta qué fecha se acreditan periodos: hoy, o la fecha de salida.
     * Un inactivo sin fecha de salida no recibe periodos nuevos.
     */
    private function fechaLimite(RrhhEmpleado $empleado, CarbonImmutable $hoy): CarbonImmutable
    {
        if ($empleado->fecha_salida) {
            return $this->fecha($empleado->fecha_salida)->min($hoy);
        }

        if ($empleado->estado === 'inactivo') {
            $base = $this->inicioControl($empleado);
            $ultimo = $empleado->movimientosVacaciones()->max('fecha_inicio');

            return ($ultimo ? $this->fecha($ultimo)->max($base) : $base)->min($hoy);
        }

        return $hoy;
    }

    private function enlazarMovimientos(RrhhEmpleado $empleado): void
    {
        $periodos = RrhhVacacionesPeriodo::where('empleado_id', $empleado->id)->get();

        RrhhVacacionesMovimiento::where('empleado_id', $empleado->id)->get()->each(function ($movimiento) use ($periodos) {
            $fecha = $movimiento->fecha_inicio->toDateString();

            $periodo = $periodos->first(fn ($p) => $p->fecha_inicio->toDateString() <= $fecha
                && $p->fecha_fin->toDateString() >= $fecha);

            $movimiento->periodo_vacacion_id = $periodo?->id;
            $movimiento->mes_referencia = $movimiento->fecha_inicio->format('m');

            if ($movimiento->isDirty()) {
                $movimiento->save();
            }
        });
    }

    /**
     * Arma la línea de tiempo: acreditaciones (con su evento de vencimiento),
     * descuentos y permisos. Orden dentro de un mismo día: primero lo que suma,
     * luego lo que resta y al final los vencimientos (cierre del año).
     * Los vencimientos se calculan con la regla vigente, no con lo guardado.
     */
    private function construirEventos(RrhhEmpleado $empleado, Collection $periodos, Collection $movimientos): array
    {
        $eventos = [];
        $definiciones = [];

        $agregarCredito = function (string $origen, string $etiqueta, string $concepto, CarbonImmutable $fecha, float $dias, ?CarbonImmutable $vence, ?int $movimientoId = null) use (&$eventos, &$definiciones) {
            $id = count($definiciones);
            $vence = $vence && $vence->lt($fecha) ? $fecha : $vence;

            $definiciones[$id] = compact('origen', 'etiqueta', 'fecha', 'vence');

            $eventos[] = ['fecha' => $fecha, 'orden' => 0, 'accion' => 'credito', 'bolsa' => $id, 'dias' => $dias,
                'tipo' => $origen, 'concepto' => $concepto, 'movimiento_id' => $movimientoId];

            if ($vence) {
                $eventos[] = ['fecha' => $vence, 'orden' => 2, 'accion' => 'vencimiento', 'bolsa' => $id, 'dias' => 0.0,
                    'tipo' => 'vencimiento', 'concepto' => 'Cierre del año ' . $vence->year . ': vencen los días no tomados',
                    'movimiento_id' => null];
            }
        };

        $acumulado = (float) $empleado->vacaciones_acumuladas;

        if ($acumulado > self::EPSILON && $periodos->isNotEmpty()) {
            $agregarCredito(
                'historico',
                'vacaciones acumuladas (histórico)',
                'Vacaciones acumuladas antes de iniciar el control en el sistema',
                $this->fecha($periodos->first()->fecha_inicio),
                $acumulado,
                $this->vencimientoAcumuladoHistorico($empleado)
            );
        }

        foreach ($periodos as $periodo) {
            if ((float) $periodo->dias_correspondientes <= self::EPSILON) {
                continue;
            }

            $fecha = $this->fecha($periodo->fecha_inicio);

            $agregarCredito(
                'periodo',
                'días acreditados el ' . $fecha->format('d-m-Y'),
                "Acreditación por {$periodo->numero_periodo} año(s) de servicio (aniversario)",
                $fecha,
                (float) $periodo->dias_correspondientes,
                $this->vencimientoPeriodo($empleado, (int) $periodo->numero_periodo)
            );
        }

        foreach ($movimientos as $movimiento) {
            $fecha = $this->fecha($movimiento->fecha_inicio);
            $dias = $this->diasADescontar($movimiento);

            if ($dias < -self::EPSILON) {
                $agregarCredito(
                    'ajuste',
                    'ajuste del ' . $fecha->format('d-m-Y'),
                    $movimiento->descripcion(),
                    $fecha,
                    abs($dias),
                    $this->vencimientoCredito($empleado, $fecha),
                    $movimiento->id
                );

                continue;
            }

            $eventos[] = ['fecha' => $fecha, 'orden' => 1, 'accion' => $dias > self::EPSILON ? 'debito' : 'informativo',
                'bolsa' => null, 'dias' => max(0.0, $dias), 'tipo' => $movimiento->tipo_movimiento,
                'concepto' => $movimiento->descripcion(), 'movimiento_id' => $movimiento->id];
        }

        foreach (array_keys($eventos) as $indice) {
            $eventos[$indice]['secuencia'] = $indice;
        }

        usort($eventos, fn ($a, $b) => [$a['fecha']->toDateString(), $a['orden'], $a['secuencia']]
            <=> [$b['fecha']->toDateString(), $b['orden'], $b['secuencia']]);

        return [$eventos, $definiciones];
    }

    /**
     * Totales por año calendario (el periodo de vacaciones). El acumulado
     * histórico se muestra aparte; lo negativo al cierre pasa al año siguiente.
     */
    private function resumirAnios(RrhhEmpleado $empleado, Collection $periodos, array $kardex, CarbonImmutable $hoy): array
    {
        if (! $kardex && $periodos->isEmpty()) {
            return [];
        }

        $inicios = array_map(fn ($fila) => $fila['fecha']->year, $kardex);

        if ($periodos->isNotEmpty()) {
            $inicios[] = $this->fecha($periodos->first()->fecha_inicio)->year;
        }

        $desde = min($inicios);
        $hasta = max(max($inicios), $hoy->year);
        $anios = [];

        for ($anio = $desde; $anio <= $hasta; $anio++) {
            $anios[$anio] = [
                'anio' => $anio,
                'saldo_inicial' => 0.0,
                'acumulado' => 0.0,
                'acreditado' => 0.0,
                'tomado' => 0.0,
                'vencido' => 0.0,
                'saldo_final' => 0.0,
                'acreditaciones' => [],
                // Antes del primer cierre el saldo pasa al año siguiente
                'cierre' => $this->sinVencimiento($empleado) || $anio < $this->primerCierre()->year
                    ? null
                    : CarbonImmutable::create($anio, 12, 31)->startOfDay(),
            ];
        }

        foreach ($kardex as $fila) {
            $anio = $fila['fecha']->year;

            if ($fila['tipo'] === 'historico') {
                $anios[$anio]['acumulado'] += $fila['credito'];
            } else {
                $anios[$anio]['acreditado'] += $fila['credito'];
            }

            if ($fila['tipo'] === 'periodo') {
                $anios[$anio]['acreditaciones'][] = ['fecha' => $fila['fecha'], 'dias' => $fila['credito']];
            }

            $anios[$anio]['tomado'] += $fila['debito'];
            $anios[$anio]['vencido'] += $fila['vencido'];
        }

        $anterior = 0.0;

        foreach ($anios as $anio => $datos) {
            $final = $anterior + $datos['acumulado'] + $datos['acreditado'] - $datos['tomado'] - $datos['vencido'];

            $anios[$anio]['saldo_inicial'] = round($anterior, 4);
            $anios[$anio]['saldo_final'] = round($final, 4);

            $anterior = $final;
        }

        return $anios;
    }

    /**
     * Totales por periodo laboral (de aniversario a aniversario). Se guardan en
     * rrhh_vacaciones_periodos como referencia.
     */
    private function resumirPeriodos(RrhhEmpleado $empleado, Collection $periodos, array $kardex): array
    {
        $resumen = [];

        foreach ($periodos as $periodo) {
            $resumen[(int) $periodo->numero_periodo] = [
                'id' => $periodo->id,
                'numero' => (int) $periodo->numero_periodo,
                'inicio' => $this->fecha($periodo->fecha_inicio),
                'fin' => $this->fecha($periodo->fecha_fin),
                'vence' => $this->vencimientoPeriodo($empleado, (int) $periodo->numero_periodo),
                'etiqueta' => $this->etiquetaPeriodo($periodo),
                'dias_ley' => (float) $periodo->dias_correspondientes,
                'saldo_inicial' => 0.0,
                'acreditado' => 0.0,
                'tomado' => 0.0,
                'vencido' => 0.0,
                'saldo_final' => 0.0,
            ];
        }

        if (! $resumen) {
            return [];
        }

        foreach ($kardex as $fila) {
            $numero = array_key_first($resumen);

            foreach ($resumen as $n => $periodo) {
                if ($periodo['inicio']->lte($fila['fecha'])) {
                    $numero = $n;
                }
            }

            if ($fila['tipo'] === 'historico') {
                $resumen[$numero]['saldo_inicial'] += $fila['credito'];
            } else {
                $resumen[$numero]['acreditado'] += $fila['credito'];
            }

            $resumen[$numero]['tomado'] += $fila['debito'];
            $resumen[$numero]['vencido'] += $fila['vencido'];
        }

        $anterior = 0.0;

        foreach ($resumen as $numero => $periodo) {
            $inicial = $periodo['saldo_inicial'] + $anterior;
            $final = $inicial + $periodo['acreditado'] - $periodo['tomado'] - $periodo['vencido'];

            $resumen[$numero]['saldo_inicial'] = round($inicial, 4);
            $resumen[$numero]['saldo_final'] = round($final, 4);

            $anterior = $final;
        }

        return $resumen;
    }

    private function etiquetaPeriodo(RrhhVacacionesPeriodo $periodo): string
    {
        return $this->fecha($periodo->fecha_inicio)->year . '-' . $this->fecha($periodo->fecha_fin)->year;
    }

    private function numeroOpcional(mixed $valor): ?float
    {
        return $valor === null || $valor === '' ? null : (float) $valor;
    }

    private function fecha(DateTimeInterface|string $fecha): CarbonImmutable
    {
        return CarbonImmutable::parse($fecha)->startOfDay();
    }
}
