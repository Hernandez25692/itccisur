<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RrhhVacacionesBitacora extends Model
{
    use HasFactory;

    protected $table = 'rrhh_vacaciones_bitacora';

    protected $fillable = [
        'empleado_id',
        'movimiento_id',
        'accion',
        'detalle',
        'usuario',
        'fecha_evento'
    ];

    public function empleado()
    {
        return $this->belongsTo(RrhhEmpleado::class);
    }

    public function movimiento()
    {
        return $this->belongsTo(RrhhVacacionesMovimiento::class);
    }
}
