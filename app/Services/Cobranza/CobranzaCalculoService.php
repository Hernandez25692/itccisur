<?php

namespace App\Services\Cobranza;

use App\Models\Cobranza\Empresa;
use Carbon\Carbon;

class CobranzaCalculoService
{
    public function __construct(
        private CargosGeneratorService $gen
    ) {}

    public function recalcularEmpresa(Empresa $empresa): void
    {
        $hoy = Carbon::today();

        // Asegurar relación corte cargada
        $empresa->loadMissing('corte');

        $ultimoPagoSistema = $empresa->pagos()
            ->orderBy('fecha_pago', 'desc')
            ->first();

        if ($ultimoPagoSistema) {
            $empresa->fecha_ultimo_pago = $ultimoPagoSistema->fecha_pago;
        } else {
            $empresa->fecha_ultimo_pago = $empresa->fecha_ultimo_pago_historico;
        }

        $this->autogenerarCargosHastaHoy($empresa, $hoy);

        $pendiente = $empresa->cargos()
            ->where('estado', 'pendiente')
            ->orderBy('fecha_vencimiento', 'asc')
            ->first();

        if ($pendiente) {
            $empresa->proxima_fecha_cobro = $pendiente->fecha_vencimiento;
        } else {
            $empresa->proxima_fecha_cobro = $this->calcularProximoCorte($empresa, $hoy);
        }

        $cuotasVencidas = 0;
        $valorMora = 0;

        if ($empresa->fecha_ultimo_pago) {

            $cuotasVencidas = $this->contarCuotasVencidasDesdeUltimoPago($empresa, $hoy);

            $cuotaMensual = $empresa->cuota_especial ?? $empresa->cuota_base;

            $mesesPorCuota = match ($empresa->tipo_pago) {
                'mensual'   => 1,
                'bimensual' => 2,
                'trimestral' => 3,
                'semestral' => 6,
                'anual'     => 12,
                default     => 1,
            };

            $valorMora = $cuotasVencidas * $mesesPorCuota * (float)$cuotaMensual;
        }

        // meses_mora guarda cuotas vencidas, no meses
        $empresa->meses_mora = max(0, (int)$cuotasVencidas);
        $empresa->valor_mora = max(0, (float)$valorMora);


        if ($empresa->meses_mora > 0) {
            $empresa->estatus_cobranza = 'en_mora';
        } else {
            $empresa->estatus_cobranza = 'al_dia';
            $empresa->valor_mora = 0;
        }

        $empresa->observacion_cobro = ($empresa->meses_mora >= 18)
            ? 'incobrable'
            : 'cobrable';

        $empresa->save();
    }

    /**
     * Genera cargos vencidos (hasta hoy) si faltan,
     * sin duplicar. Esto hace que el Estado de cuenta
     * siempre refleje mora real sin botón.
     */
    private function autogenerarCargosHastaHoy(Empresa $empresa, Carbon $hoy): void
    {
        // Seguridad: si no hay corte, no se puede calcular vencimiento real
        if (!$empresa->corte) return;

        $maxIter = 120; // tope: 10 años de cargos mensuales

        for ($i = 0; $i < $maxIter; $i++) {

            // Tomar el último cargo válido (sin anulado)
            $ultimo = $empresa->cargos()
                ->whereIn('estado', ['pendiente', 'pagado'])
                ->orderBy('periodo_fin', 'desc')
                ->first();

            // Simular cuál sería el siguiente cargo y su vencimiento
            $meses = match ($empresa->tipo_pago) {
                'mensual' => 1,
                'bimensual' => 2,
                'trimestral' => 3,
                'semestral' => 6,
                'anual' => 12,
                default => 1,
            };

            if ($ultimo) {
                $inicio = Carbon::parse($ultimo->periodo_fin)->addDay();
            } else {
                if ($empresa->fecha_ultimo_pago) {
                    $inicio = Carbon::parse($empresa->fecha_ultimo_pago)->addDay();
                } else {
                    // Sin fecha base no se generan cargos hacia atrás, solo desde este mes
                    $inicio = $hoy->copy()->startOfMonth();
                }
            }

            $fin = $inicio->copy()->addMonthsNoOverflow($meses)->subDay();

            $diaCorte = (int)$empresa->corte->dia_corte;
            $venc = Carbon::create($fin->year, $fin->month, 1)
                ->day(min($diaCorte, $fin->daysInMonth));

            if ($venc->gt($hoy)) {
                break;
            }

            $existe = $empresa->cargos()
                ->whereIn('estado', ['pendiente', 'pagado'])
                ->whereDate('periodo_inicio', $inicio->toDateString())
                ->whereDate('periodo_fin', $fin->toDateString())
                ->exists();

            if ($existe) {
                // Si ya existe, avanzamos artificialmente "como si se hubiese creado"
                // para evitar estancarnos.
                // (pero en condiciones normales no debería pasar)
                continue;
            }

            // Crear realmente el siguiente cargo
            $this->gen->generarCargoSiguiente($empresa);
        }
    }

    /**
     * Cuenta cuántas cuotas han vencido desde el último pago,
     * usando el día de corte y el tipo de pago (mensual, bimensual, etc).
     */
    private function contarCuotasVencidasDesdeUltimoPago(Empresa $empresa, Carbon $hoy): int
    {
        if (!$empresa->corte || !$empresa->fecha_ultimo_pago) return 0;

        $meses = match ($empresa->tipo_pago) {
            'mensual' => 1,
            'bimensual' => 2,
            'trimestral' => 3,
            'semestral' => 6,
            'anual' => 12,
            default => 1,
        };

        $diaCorte = (int)$empresa->corte->dia_corte;

        $base = Carbon::parse($empresa->fecha_ultimo_pago);

        // Primer vencimiento después del último pago
        $due = $base->copy()->addMonthsNoOverflow($meses);
        $due->day(min($diaCorte, $due->daysInMonth));

        $count = 0;
        $maxIter = 120;

        for ($i = 0; $i < $maxIter; $i++) {
            if ($due->lte($hoy)) {
                $count++;
                $due = $due->copy()->addMonthsNoOverflow($meses);
                $due->day(min($diaCorte, $due->daysInMonth));
            } else {
                break;
            }
        }

        return $count;
    }

    /**
     * Próximo corte después de HOY, basado en último pago y tipo.
     */
    public function calcularProximoCorte(Empresa $empresa, ?Carbon $hoy = null): Carbon
    {
        $hoy = $hoy ?? Carbon::today();

        $empresa->loadMissing('corte');

        $dia = (int) ($empresa->corte?->dia_corte ?? 1);

        // Si no hay último pago, próximo cobro = corte de este mes o próximo según hoy
        if (!$empresa->fecha_ultimo_pago) {
            $corteEsteMes = Carbon::create($hoy->year, $hoy->month, 1)->day(min($dia, $hoy->daysInMonth));
            if ($hoy->lte($corteEsteMes)) return $corteEsteMes;

            $meses = match ($empresa->tipo_pago) {
                'mensual' => 1,
                'bimensual' => 2,
                'trimestral' => 3,
                'semestral' => 6,
                'anual' => 12,
                default => 1,
            };

            $next = $corteEsteMes->copy()->addMonthsNoOverflow($meses);
            $next->day(min($dia, $next->daysInMonth));
            return $next;
        }

        $meses = match ($empresa->tipo_pago) {
            'mensual' => 1,
            'bimensual' => 2,
            'trimestral' => 3,
            'semestral' => 6,
            'anual' => 12,
            default => 1,
        };

        $base = Carbon::parse($empresa->fecha_ultimo_pago);

        $due = $base->copy()->addMonthsNoOverflow($meses);
        $due->day(min($dia, $due->daysInMonth));

        // avanzar hasta que quede en el futuro
        $maxIter = 120;
        for ($i = 0; $i < $maxIter; $i++) {
            if ($due->gt($hoy)) return $due;

            $due = $due->copy()->addMonthsNoOverflow($meses);
            $due->day(min($dia, $due->daysInMonth));
        }

        return $due;
    }
}
