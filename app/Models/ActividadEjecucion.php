<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActividadEjecucion extends Model
{
    protected $table = 'actividad_ejecuciones';

    protected $fillable = [
        'plan_actividad_id',
        'avance',
        'comentario',
        'fecha',
        'evidencia',
        'user_id'
    ];

    public function actividad()
    {
        return $this->belongsTo(PlanActividad::class, 'plan_actividad_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
