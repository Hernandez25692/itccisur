<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanActividad extends Model
{
    protected $table = 'plan_actividades';

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


    public function getAvanceTotalAttribute()
    {
        return $this->ejecuciones()->first()->avance ?? 0;
    }
    public function getProgresoAttribute()
    {
        $total = $this->ejecuciones->sum('avance');
        return min($total, 100);
    }
}
