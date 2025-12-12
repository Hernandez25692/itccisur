<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarioEditorial extends Model
{
    use HasFactory;

    protected $table = 'calendario_editorials';

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

    /* =========================
     |  RELACIONES
     ========================= */

    public function creador()
    {
        return $this->belongsTo(User::class, 'creado_por');
    }

    public function publicadoPor()
    {
        return $this->belongsTo(User::class, 'publicado_por');
    }

    /* =========================
     |  SCOPES (para filtros)
     ========================= */

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

    /* =========================
     |  HELPERS
     ========================= */

    public function esPublicado(): bool
    {
        return $this->estado === 'publicado';
    }
}
