<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Periodo laboral de vacaciones: del aniversario $numero_periodo al siguiente.
 * Se crean con VacacionesService::sincronizarEmpleado(); los totales guardados
 * son solo de referencia y se recalculan con cada movimiento.
 */
class RrhhVacacionesPeriodo extends Model
{
    use HasFactory;

    protected $table = 'rrhh_vacaciones_periodos';

    protected $fillable = [
        'empleado_id',
        'anio',
        'numero_periodo',
        'fecha_inicio',
        'fecha_fin',
        'fecha_vencimiento',
        'dias_correspondientes',
        'acumulado_anterior',
        'dias_descontados',
        'total_disponible',
        'total_tomado',
        'dias_vencidos',
        'dias_pendientes',
        'nota',
        'cerrado',
    ];

    protected $casts = [
        'numero_periodo' => 'integer',
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'fecha_vencimiento' => 'date',
        'dias_correspondientes' => 'decimal:2',
        'acumulado_anterior' => 'decimal:2',
        'dias_descontados' => 'decimal:2',
        'total_disponible' => 'decimal:2',
        'total_tomado' => 'decimal:2',
        'dias_vencidos' => 'decimal:2',
        'dias_pendientes' => 'decimal:2',
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
