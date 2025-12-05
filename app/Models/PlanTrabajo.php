<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanTrabajo extends Model
{
    use HasFactory;

    protected $table = 'plan_trabajos';

    protected $fillable = [
        'anio',
        'estado',
        'aprobado_por',
        'fecha_aprobacion',
        'descripcion_general',
        'comentario_gerencia',
    ];

    // 👇 Aquí está la clave del error
    protected $casts = [
        'fecha_aprobacion' => 'date',
    ];

    // Relación: un plan tiene muchas actividades
    public function actividades()
    {
        return $this->hasMany(PlanActividad::class, 'plan_trabajo_id');
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
}
