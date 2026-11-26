<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BitacoraActividad extends Model
{
    use HasFactory;

    protected $table = 'bitacora_actividades';

    public const ESTADOS = ['pendiente' => 'Pendiente', 'en_proceso' => 'En proceso', 'resuelto' => 'Resuelto'];

    public const PRIORIDADES = ['baja' => 'Baja', 'media' => 'Media', 'alta' => 'Alta', 'critica' => 'Crítica'];

    protected $fillable = [
        'user_id',
        'plan_actividad_id',
        'titulo',
        'descripcion',
        'equipo_afectado',
        'tipo_falla',
        'ubicacion',
        'estado',
        'solucionado',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'solucion_aplicada',
        'observaciones',
        'evidencia',
        'prioridad',
        'tiempo_empleado_minutos',
    ];

    protected $casts = [
        'solucionado' => 'boolean',
        'fecha' => 'date',
        'hora_inicio' => 'datetime:H:i',
        'hora_fin' => 'datetime:H:i',
    ];

    /**
     * Usuario responsable de la actividad.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** Actividad del Plan de Trabajo a la que corresponde (opcional). */
    public function planActividad()
    {
        return $this->belongsTo(PlanActividad::class, 'plan_actividad_id');
    }

    /** Avance que esta bitácora registró en el plan, si registró alguno. */
    public function ejecucionPlan()
    {
        return $this->hasOne(ActividadEjecucion::class, 'bitacora_actividad_id');
    }

    /** Filtros del listado; las claves vacías o "todos" se ignoran. */
    public function scopeFiltrar(Builder $query, array $f): Builder
    {
        $valor = fn (string $clave) => in_array($f[$clave] ?? null, [null, '', 'todos', 'todas'], true) ? null : $f[$clave];

        return $query
            ->when($valor('buscar'), function ($q, $texto) {
                $q->where(function ($q) use ($texto) {
                    foreach (['titulo', 'descripcion', 'equipo_afectado', 'ubicacion', 'solucion_aplicada'] as $campo) {
                        $q->orWhere($campo, 'like', "%{$texto}%");
                    }
                });
            })
            ->when($valor('estado'), fn ($q, $v) => $q->where('estado', $v))
            ->when($valor('prioridad'), fn ($q, $v) => $q->where('prioridad', $v))
            ->when($valor('tipo_falla'), fn ($q, $v) => $q->where('tipo_falla', $v))
            ->when($valor('ubicacion'), fn ($q, $v) => $q->where('ubicacion', $v))
            ->when($valor('tecnico'), fn ($q, $v) => $q->where('user_id', $v))
            ->when($valor('desde'), fn ($q, $v) => $q->whereDate('fecha', '>=', $v))
            ->when($valor('hasta'), fn ($q, $v) => $q->whereDate('fecha', '<=', $v))
            ->when($valor('plan_actividad_id'), fn ($q, $v) => $q->where('plan_actividad_id', $v))
            ->when($valor('plan') === 'con', fn ($q) => $q->whereNotNull('plan_actividad_id'))
            ->when($valor('plan') === 'sin', fn ($q) => $q->whereNull('plan_actividad_id'));
    }

    /**
     * Minutos entre dos horas "H:i". Null si falta alguna o si la hora de fin
     * no es posterior (el formulario no permite cruzar la medianoche).
     */
    public static function calcularMinutos(?string $inicio, ?string $fin): ?int
    {
        if (!$inicio || !$fin) {
            return null;
        }

        $minutos = (int) round((strtotime($fin) - strtotime($inicio)) / 60);

        return $minutos > 0 ? $minutos : null;
    }
}
