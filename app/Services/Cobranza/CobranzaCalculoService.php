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

        /**
         * =====================================================
         * 1) FECHA ÚLTIMO PAGO (sistema o histórico)
         * =====================================================
         */
        $ultimoPagoSistema = $empresa->pagos()
            ->orderBy('fecha_pago', 'desc')
            ->first();

        if ($ultimoPagoSistema) {
            $empresa->fecha_ultimo_pago = $ultimoPagoSistema->fecha_pago;
        } else {
            $empresa->fecha_ultimo_pago = $empresa->fecha_ultimo_pago_historico;
        }

        /**
         * =====================================================
         * 2) ✅ AUTO-GENERAR CARGOS VENCIDOS (SIN BOTÓN)
         * =====================================================
         * Genera cargos solo hasta HOY (vencidos o que vencen hoy),
         * sin duplicar, y sin depender de vistas.
         */
        $this->autogenerarCargosHastaHoy($empresa, $hoy);

        /**
         * =====================================================
         * 3) PRÓXIMA FECHA DE COBRO (conservador)
         * =====================================================
         */
        $pendiente = $empresa->cargos()
            ->where('estado', 'pendiente')
            ->orderBy('fecha_vencimiento', 'asc')
            ->first();

        if ($pendiente) {
            $empresa->proxima_fecha_cobro = $pendiente->fecha_vencimiento;
        } else {
            $empresa->proxima_fecha_cobro = $this->calcularProximoCorte($empresa, $hoy);
        }

        /**
         * =====================================================
         * 4) MORA REAL (por vencimientos de corte)
         * =====================================================
         */
        $cuotasVencidas = 0;
        $valorMora = 0;

        if ($empresa->fecha_ultimo_pago) {

            // 🔹 Cantidad de CUOTAS vencidas (no meses)
            $cuotasVencidas = $this->contarCuotasVencidasDesdeUltimoPago($empresa, $hoy);

            // 🔹 Cuota mensual
            $cuotaMensual = $empresa->cuota_especial ?? $empresa->cuota_base;

            // 🔹 Meses reales por tipo de pago
            $mesesPorCuota = match ($empresa->tipo_pago) {
                'mensual'   => 1,
                'bimensual' => 2,
                'trimestral' => 3,
                'semestral' => 6,
                'anual'     => 12,
                default     => 1,
            };

            // ✅ MORA REAL = cuotas vencidas × meses reales × cuota mensual
            $valorMora = $cuotasVencidas * $mesesPorCuota * (float)$cuotaMensual;
        }

        // 👉 Este campo ahora representa CUOTAS vencidas
        $empresa->meses_mora = max(0, (int)$cuotasVencidas);
        $empresa->valor_mora = max(0, (float)$valorMora);


        /**
         * =====================================================
         * 5) ESTATUS DE COBRANZA
         * =====================================================
         */
        if ($empresa->meses_mora > 0) {
            $empresa->estatus_cobranza = 'en_mora';
        } else {
            $empresa->estatus_cobranza = 'al_dia';
            $empresa->valor_mora = 0;
        }

        /**
         * =====================================================
         * 6) OBSERVACIÓN
         * =====================================================
         */
        $empresa->observacion_cobro = ($empresa->meses_mora >= 18)
            ? 'incobrable'
            : 'cobrable';

        /**
         * =====================================================
         * 7) GUARDAR
         * =====================================================
         */
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

        // Evitar loops infinitos: nadie debe 500 años 😄
        $maxIter = 120; // 10 años mensual

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
                    // Si no hay fecha base, no autogeneramos hacia atrás
                    // (evita inventar historiales). Aquí solo generas desde este mes.
                    $inicio = $hoy->copy()->startOfMonth();
                }
            }

            $fin = $inicio->copy()->addMonthsNoOverflow($meses)->subDay();

            $diaCorte = (int)$empresa->corte->dia_corte;
            $venc = Carbon::create($fin->year, $fin->month, 1)
                ->day(min($diaCorte, $fin->daysInMonth));

            // ✅ Si el siguiente cargo vencería en el futuro, paramos
            if ($venc->gt($hoy)) {
                break;
            }

            // ✅ Evitar duplicados por período (pendiente/pagado)
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
