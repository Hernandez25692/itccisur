<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanActividadEjecucion extends Model
{
    protected $table = 'plan_actividad_ejecuciones';

    protected $fillable = [
        'plan_actividad_id',
        'fecha_ejecucion',
        'avance',
        'comentarios',
        'evidencia'
    ];

    public function actividad()
    {
        return $this->belongsTo(PlanActividad::class, 'plan_actividad_id');
    }
}
