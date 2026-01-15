<?php

namespace App\Services\Cobranza;

use App\Models\Cobranza\Empresa;
use App\Models\Cobranza\Cargo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CargosGeneratorService
{
    /**
     * Genera el siguiente cargo real de una empresa.
     *
     * REGLAS CLAVE:
     * - La cuota SIEMPRE es mensual
     * - El tipo de pago solo agrupa meses
     * - El total del cargo = cuota mensual × meses del período
     * - No depende de botones
     * - Ignora cargos anulados
     */
    public function generarCargoSiguiente(Empresa $empresa): Cargo
    {
        /**
         * =====================================================
         * 1️⃣ MESES DEL PERÍODO SEGÚN TIPO DE PAGO
         * =====================================================
         */
        $mesesPorPeriodo = match ($empresa->tipo_pago) {
            'mensual'    => 1,
            'bimensual'  => 2,
            'trimestral' => 3,
            'semestral'  => 6,
            'anual'      => 12,
            default      => 1,
        };

        /**
         * =====================================================
         * 2️⃣ ÚLTIMO CARGO VÁLIDO (SIN ANULADOS)
         * =====================================================
         */
        $ultimo = $empresa->cargos()
            ->whereIn('estado', ['pendiente', 'pagado'])
            ->orderBy('periodo_fin', 'desc')
            ->first();

        if ($ultimo) {
            $inicio = Carbon::parse($ultimo->periodo_fin)->addDay();
        } else {
            if ($empresa->fecha_ultimo_pago) {
                $inicio = Carbon::parse($empresa->fecha_ultimo_pago)->addDay();
            } else {
                // Base conservadora: inicio del mes actual
                $inicio = Carbon::today()->startOfMonth();
            }
        }

        /**
         * =====================================================
         * 3️⃣ PERÍODO DEL CARGO
         * =====================================================
         */
        $fin = $inicio->copy()
            ->addMonthsNoOverflow($mesesPorPeriodo)
            ->subDay();

        /**
         * =====================================================
         * 4️⃣ FECHA DE VENCIMIENTO (DÍA DE CORTE)
         * =====================================================
         */
        $empresa->loadMissing('corte');

        $diaCorte = (int) ($empresa->corte?->dia_corte ?? 1);

        $vencimiento = Carbon::create(
            $fin->year,
            $fin->month,
            1
        )->day(min($diaCorte, $fin->daysInMonth));

        /**
         * =====================================================
         * 5️⃣ MONTO REAL DEL CARGO
         * =====================================================
         * ⚠️ La cuota siempre es MENSUAL
         */
        $cuotaMensual = (float) ($empresa->cuota_especial ?? $empresa->cuota_base);

        // ✅ TOTAL CORRECTO DEL PERÍODO
        $total = $cuotaMensual * $mesesPorPeriodo;

        /**
         * =====================================================
         * 6️⃣ CREAR CARGO
         * =====================================================
         */
        return Cargo::create([
            'empresa_id'        => $empresa->id,
            'periodo_inicio'    => $inicio->toDateString(),
            'periodo_fin'       => $fin->toDateString(),
            'fecha_vencimiento' => $vencimiento->toDateString(),

            // Referencias monetarias
            'monto_cuota'       => $cuotaMensual, // cuota mensual
            'monto_mora'        => 0,
            'total'             => $total,        // TOTAL REAL

            'estado'            => 'pendiente',
            'created_by'        => Auth::id(),
        ]);
    }
}
