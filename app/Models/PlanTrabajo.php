<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanTrabajo extends Model
{
    use HasFactory;

    protected $table = 'plan_trabajos';

    public const ESTADOS = ['borrador', 'enviado', 'aprobado', 'rechazado'];

    protected $fillable = [
        'anio',
        'estado',
        'aprobado_por',
        'fecha_aprobacion',
        'descripcion_general',
        'comentario_gerencia',
    ];

    protected $casts = [
        'fecha_aprobacion' => 'date',
    ];

    // Relación: un plan tiene muchas actividades (chaperone: cada actividad ya conoce su plan)
    public function actividades()
    {
        return $this->hasMany(PlanActividad::class, 'plan_trabajo_id')->chaperone('plan');
    }

    // Quién aprobó (usuario de gerencia)
    public function aprobador()
    {
        return $this->belongsTo(User::class, 'aprobado_por');
    }

    public function revisiones()
    {
        return $this->hasMany(PlanRevision::class)->orderBy('created_at', 'desc');
    }

    /** Solo en borrador o rechazado se puede cambiar el plan y sus actividades. */
    public function esEditable(): bool
    {
        return in_array($this->estado, ['borrador', 'rechazado'], true);
    }

    /**
     * Promedio del progreso de las actividades (0-100). Requiere las
     * actividades cargadas, idealmente con withSum('ejecuciones', 'avance').
     */
    public function getAvanceGlobalAttribute(): int
    {
        $actividades = $this->actividades;

        return $actividades->isEmpty() ? 0 : (int) round($actividades->avg(fn ($a) => $a->progreso));
    }

    /** Plan que se está ejecutando: el aprobado más reciente hasta el año dado. */
    public static function vigente(?int $anio = null): ?self
    {
        return static::where('estado', 'aprobado')
            ->where('anio', '<=', $anio ?? now()->year)
            ->orderByDesc('anio')
            ->first();
    }
}
