<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BitacoraActividad extends Model
{
    use HasFactory;

    protected $table = 'bitacora_actividades';

    protected $fillable = [
        'user_id',
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
}
