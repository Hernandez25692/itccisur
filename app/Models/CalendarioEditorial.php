<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarioEditorial extends Model
{
    use HasFactory;

    protected $table = 'calendario_editorials';

    public const ESTADOS = ['pendiente', 'publicado', 'reprogramado', 'cancelado'];

    /** Estados que no cuentan para el cumplimiento (no se esperaba publicarlos). */
    public const ESTADOS_FUERA_DE_META = ['reprogramado', 'cancelado'];

    public const DIAS = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];

    public const TIPOS_CONTENIDO = ['EN VIVO', 'IMAGEN', 'CARRUSEL', 'HISTORIA', 'VIDEO'];

    public const REDES = ['FACEBOOK', 'INSTAGRAM', 'X', 'LINKEDIN', 'YOUTUBE', 'WHATSAPP', 'TIKTOK', 'OTRA'];

    public const AREAS = [
        'Día festivo', 'Calendario', 'Bienvenida socios', 'Post capacitación', 'Vacantes',
        'Servicio empresarial', 'Comunicado', 'Nota de duelo', 'Nota de prensa', 'Evento',
        'Empresa afiliada Ofrece', 'Frase motivacional', 'Campaña muevete y emprende',
        'Campaña Consejo laboral - video', 'Video resumen capacitación', 'Video resumen evento CCISUR',
        'Servicios de Intermediación laboral', 'Alquiler de salón de eventos', 'Espacio de coworking',
        'Alquiler de espacio para zona bancaria', 'Campaña de registro', 'Campaña de afiliación',
        'Campaña informativa CAS - consejos',
    ];

    /** Color por estado, igual en la tabla, el calendario y el dashboard. */
    public const COLORES = [
        'publicado' => '#10B981',
        'pendiente' => '#F97316',
        'reprogramado' => '#EAB308',
        'cancelado' => '#EF4444',
    ];

    /**
     * Campos que se pueden llenar masivamente
     */
    protected $fillable = [
        'semana',
        'dia',
        'fecha_publicacion',
        'hora',
        'tema',
        'area',
        'encabezado',
        'contenido',
        'publicar_en',
        'etiquetas',
        'comentario',
        'enlace',
        'adjunto_path',
        'adjunto_nombre',
        'estado',
        'fecha_publicado',
        'publicado_por',
        'nota_admin',
        'creado_por',
    ];

    /**
     * Casts automáticos
     */
    protected $casts = [
        'contenido'       => 'array',   // EN VIVO, IMAGEN, VIDEO...
        'publicar_en'     => 'array',   // FACEBOOK, INSTAGRAM...
        'fecha_publicado' => 'datetime',
        'fecha_publicacion' => 'date',
    ];

    /**
     * Valores por defecto
     */
    protected $attributes = [
        'estado' => 'pendiente',
    ];

    protected static function booted(): void
    {
        // El día siempre sale de la fecha; la semana (ISO, 1-53) solo si no se indicó
        static::saving(function (self $registro) {
            if ($registro->fecha_publicacion) {
                $fecha = Carbon::parse($registro->fecha_publicacion);
                $registro->dia = self::DIAS[$fecha->dayOfWeekIso - 1];
                $registro->semana = $registro->semana ?: $fecha->isoWeek();
            }
        });
    }

    public function creador()
    {
        return $this->belongsTo(User::class, 'creado_por');
    }

    public function publicadoPor()
    {
        return $this->belongsTo(User::class, 'publicado_por');
    }

    public function adjuntos()
    {
        return $this->hasMany(CalendarioEditorialAdjunto::class, 'calendario_editorial_id');
    }

    /**
     * admin_ti y gerencia editan cualquiera; el rol calendario solo lo que
     * creó; usuario puede editar (pero no cambia estado, enlace ni nota admin).
     */
    public function puedeEditar(?User $user): bool
    {
        if (!$user) {
            return false;
        }

        if ($user->hasAnyRole(['admin_ti', 'gerencia'])) {
            return true;
        }

        if ($user->hasRole('calendario')) {
            return (int) $this->creado_por === (int) $user->id;
        }

        return $user->hasRole('usuario');
    }

    public function scopeSemana($query, $semana)
    {
        if ($semana) {
            $query->where('semana', $semana);
        }
    }

    public function scopeEstado($query, $estado)
    {
        if ($estado) {
            $query->where('estado', $estado);
        }
    }

    public function scopeMes($query, $mes)
    {
        if ($mes) {
            $query->whereMonth('fecha_publicacion', $mes);
        }
    }

    public function scopeAnio($query, $anio)
    {
        if ($anio) {
            $query->whereYear('fecha_publicacion', $anio);
        }
    }

    /** Filtros comunes del listado, el calendario y el dashboard. */
    public function scopeFiltrar(Builder $query, array $f): Builder
    {
        return $query
            ->when($f['buscar'] ?? null, function ($q, $texto) {
                $q->where(function ($q) use ($texto) {
                    $q->where('tema', 'like', "%{$texto}%")
                        ->orWhere('encabezado', 'like', "%{$texto}%")
                        ->orWhere('etiquetas', 'like', "%{$texto}%");
                });
            })
            ->when($f['area'] ?? null, fn ($q, $v) => $q->where('area', $v))
            ->when($f['red'] ?? null, fn ($q, $v) => $q->whereJsonContains('publicar_en', $v))
            ->when($f['tipo'] ?? null, fn ($q, $v) => $q->whereJsonContains('contenido', $v))
            ->when($f['creado_por'] ?? null, fn ($q, $v) => $q->where('creado_por', $v));
    }

    /** Pendientes cuya fecha ya pasó: quedaron sin publicar. */
    public function scopeVencidas(Builder $query): Builder
    {
        return $query->where('estado', 'pendiente')
            ->whereDate('fecha_publicacion', '<', now()->toDateString());
    }

    public function esPublicado(): bool
    {
        return $this->estado === 'publicado';
    }

    public function getColorAttribute(): string
    {
        return self::COLORES[$this->estado] ?? '#6B7280';
    }
}
