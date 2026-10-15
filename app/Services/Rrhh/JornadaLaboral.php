<?php

namespace App\Services\Rrhh;

use Carbon\CarbonImmutable;
use DateTimeInterface;

/**
 * Reglas de la jornada de referencia de CCISUR (config/rrhh.php):
 * 8:00 a 12:00 y 13:00 a 17:00 => 8 horas = 1 día.
 */
class JornadaLaboral
{
    /**
     * Días a descontar entre dos fechas (ambas incluidas).
     * Los domingos no se descuentan; los sábados sí.
     */
    public function diasDescontables(DateTimeInterface|string $inicio, DateTimeInterface|string $fin): int
    {
        $dia = CarbonImmutable::parse($inicio)->startOfDay();
        $fin = CarbonImmutable::parse($fin)->startOfDay();
        $noDescontables = config('rrhh.jornada.dias_no_descontables', [0]);

        $dias = 0;

        while ($dia->lte($fin)) {
            if (! in_array($dia->dayOfWeek, $noDescontables, true)) {
                $dias++;
            }

            $dia = $dia->addDay();
        }

        return $dias;
    }

    /**
     * Horas a descontar entre dos horas del mismo día. Solo cuentan las horas
     * dentro de la jornada; la hora de almuerzo se excluye.
     * Ej.: 10:00 a 14:00 => 3 horas.
     */
    public function horasDescontables(string $horaInicio, string $horaFin): float
    {
        $inicio = $this->minutos($horaInicio);
        $fin = $this->minutos($horaFin);

        $minutos = 0;

        foreach ($this->bloques() as [$desde, $hasta]) {
            $minutos += max(0, min($fin, $hasta) - max($inicio, $desde));
        }

        return round($minutos / 60, 2);
    }

    public function horasPorDia(): float
    {
        $minutos = 0;

        foreach ($this->bloques() as [$desde, $hasta]) {
            $minutos += $hasta - $desde;
        }

        return $minutos / 60;
    }

    /**
     * Bloques de trabajo del día en minutos: [[entrada, almuerzo], [regreso, salida]].
     */
    public function bloques(): array
    {
        $jornada = config('rrhh.jornada');

        return [
            [$this->minutos($jornada['entrada']), $this->minutos($jornada['almuerzo_inicio'])],
            [$this->minutos($jornada['almuerzo_fin']), $this->minutos($jornada['salida'])],
        ];
    }

    /**
     * Domingo de Resurrección del año (algoritmo gregoriano anónimo).
     * Semana Santa en CCISUR: de miércoles a viernes antes de esta fecha.
     */
    public function domingoDeResurreccion(int $anio): CarbonImmutable
    {
        $a = $anio % 19;
        $b = intdiv($anio, 100);
        $c = $anio % 100;
        $d = intdiv($b, 4);
        $e = $b % 4;
        $f = intdiv($b + 8, 25);
        $g = intdiv($b - $f + 1, 3);
        $h = (19 * $a + $b - $d - $g + 15) % 30;
        $i = intdiv($c, 4);
        $k = $c % 4;
        $l = (32 + 2 * $e + 2 * $i - $h - $k) % 7;
        $m = intdiv($a + 11 * $h + 22 * $l, 451);
        $mes = intdiv($h + $l - 7 * $m + 114, 31);
        $dia = (($h + $l - 7 * $m + 114) % 31) + 1;

        return CarbonImmutable::create($anio, $mes, $dia)->startOfDay();
    }

    private function minutos(string $hora): int
    {
        $partes = explode(':', $hora);

        return ((int) $partes[0]) * 60 + (int) ($partes[1] ?? 0);
    }
}
