<?php

namespace App\Services\Rrhh;

use Carbon\CarbonImmutable;

/**
 * Resultado del cálculo de vacaciones de un empleado (ver VacacionesService::estadoCuenta).
 * Todas las cantidades están en días; las horas se convierten con la jornada (8 h = 1 día).
 */
class EstadoCuentaVacaciones
{
    /**
     * @param  array  $kardex  Movimientos en orden cronológico con saldo corrido:
     *                         [fecha, tipo, concepto, credito, debito, vencido, saldo, programado, adelanto, movimiento_id]
     * @param  array  $bolsas  Cada acreditación con lo usado, vencido y restante:
     *                         [origen, etiqueta, fecha, vence, acreditado, usado, vencido, restante]
     * @param  array  $periodos  Resumen por periodo laboral, indexado por numero_periodo:
     *                           [numero, inicio, fin, vence, dias_ley, saldo_inicial, acreditado, tomado, vencido, saldo_final]
     */
    public function __construct(
        public readonly float $saldo,
        public readonly float $deuda,
        public readonly float $deudaGenerada,
        public readonly array $kardex,
        public readonly array $bolsas,
        public readonly array $periodos,
        public readonly ?array $periodoActual,
        public readonly ?array $acumuladoHistorico,
        public readonly ?array $proximoVencimiento,
    ) {}

    public function diasPorLeyActual(): float
    {
        return $this->periodoActual['dias_ley'] ?? 0;
    }

    public function tomadoPeriodoActual(): float
    {
        return $this->periodoActual['tomado'] ?? 0;
    }

    public function acumuladoRestante(): float
    {
        return $this->acumuladoHistorico['restante'] ?? 0;
    }

    /**
     * Días que vencen en los próximos $dias días (desde $hoy).
     */
    public function porVencerEn(int $dias, ?CarbonImmutable $hoy = null): float
    {
        $limite = ($hoy ?? CarbonImmutable::today())->addDays($dias);

        return round(collect($this->bolsas)
            ->filter(fn ($bolsa) => $bolsa['restante'] > 0 && $bolsa['vence']->lte($limite))
            ->sum('restante'), 2);
    }
}
