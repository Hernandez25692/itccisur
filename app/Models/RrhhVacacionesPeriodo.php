<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RrhhVacacionesPeriodo extends Model
{
    use HasFactory;

    protected $table = 'rrhh_vacaciones_periodos';

    protected $fillable = [
        'empleado_id',
        'anio',
        'dias_correspondientes',
        'acumulado_anterior',
        'dias_descontados',
        'total_disponible',
        'total_tomado',
        'dias_pendientes',
        'nota',
        'cerrado',
    ];

    protected $casts = [
        'cerrado' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONES
    |--------------------------------------------------------------------------
    */

    public function empleado()
    {
        return $this->belongsTo(RrhhEmpleado::class, 'empleado_id');
    }

    public function movimientos()
    {
        return $this->hasMany(RrhhVacacionesMovimiento::class, 'periodo_vacacion_id');
    }
}
