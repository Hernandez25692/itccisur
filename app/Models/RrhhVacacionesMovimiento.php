<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RrhhVacacionesMovimiento extends Model
{
    use HasFactory;

    protected $table = 'rrhh_vacaciones_movimientos';

    protected $fillable = [
        'empleado_id',
        'periodo_vacacion_id',
        'tipo_movimiento',
        'estado',
        'fecha_inicio',
        'fecha_fin',
        'hora_inicio',
        'hora_fin',
        'dias_equivalentes',
        'horas_equivalentes',
        'mes_referencia',
        'motivo',
        'observacion',
        'archivo_comprobante',
        'aprobado_por',
        'fecha_aprobacion',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'fecha_aprobacion' => 'datetime',
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

    public function periodo()
    {
        return $this->belongsTo(RrhhVacacionesPeriodo::class, 'periodo_vacacion_id');
    }
}
