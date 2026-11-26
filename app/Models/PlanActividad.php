<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class PlanActividad extends Model
{
    protected $table = 'plan_actividades';

    public const MESES = [
        1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio',
        7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre',
    ];

    public const ESTADOS = ['pendiente', 'en_progreso', 'finalizado'];

    protected $fillable = [
        'plan_trabajo_id',
        'codigo',
        'seccion',
        'actividad',
        'objetivo',
        'frecuencia',
        'responsable',
        'mes_previsto',
        'fecha_ejecucion',
        'metrica_exito',
        'estado',
        'observaciones'
    ];

    public function plan()
    {
        return $this->belongsTo(PlanTrabajo::class, 'plan_trabajo_id');
    }

    public function ejecuciones()
    {
        return $this->hasMany(ActividadEjecucion::class, 'plan_actividad_id')
            ->orderBy('created_at', 'desc');
    }

    public function bitacoras()
    {
        return $this->hasMany(BitacoraActividad::class, 'plan_actividad_id')
            ->orderBy('fecha', 'desc');
    }

    /**
     * Suma de avances (tope 100). Usa withSum('ejecuciones', 'avance') si se
     * cargó, para no consultar las ejecuciones de cada actividad por separado.
     */
    public function getProgresoAttribute(): int
    {
        if (array_key_exists('ejecuciones_sum_avance', $this->attributes)) {
            return min((int) $this->attributes['ejecuciones_sum_avance'], 100);
        }

        return min((int) $this->ejecuciones->sum('avance'), 100);
    }

    public function getRestanteAttribute(): int
    {
        return 100 - $this->progreso;
    }

    /** Número de mes (1-12) del texto de mes_previsto, o null si no es un mes. */
    public function getMesPrevistoNumeroAttribute(): ?int
    {
        if (!$this->mes_previsto) {
            return null;
        }

        $mes = array_search(mb_strtolower(trim($this->mes_previsto)), array_map('mb_strtolower', self::MESES), true);

        return $mes === false ? null : $mes;
    }

    /** Pasó el mes previsto (del año del plan) y no llegó al 100%. */
    public function getAtrasadaAttribute(): bool
    {
        $mes = $this->mes_previsto_numero;
        $anio = $this->plan?->anio;

        if (!$mes || !$anio || $this->progreso >= 100) {
            return false;
        }

        return Carbon::create((int) $anio, $mes, 1)->endOfMonth()->isPast();
    }

    /** Estado coherente con el progreso: no se edita a mano. */
    public static function estadoSegunProgreso(int $progreso): string
    {
        return match (true) {
            $progreso >= 100 => 'finalizado',
            $progreso > 0 => 'en_progreso',
            default => 'pendiente',
        };
    }
}
