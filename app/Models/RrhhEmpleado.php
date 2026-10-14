<?php

namespace App\Models;

use App\Services\Rrhh\VacacionesService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RrhhEmpleado extends Model
{
    use HasFactory;

    protected $table = 'rrhh_empleados';

    protected $fillable = [
        'nombre_completo',
        'identidad',
        'correo',
        'telefono',
        'cargo',
        'area',
        'fecha_contratacion',
        'vacaciones_acumuladas',
        'estado',
        'fecha_salida',
        'nota_general',
    ];

    protected $casts = [
        'fecha_contratacion' => 'date',
        'fecha_salida' => 'date',
        'vacaciones_acumuladas' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONES
    |--------------------------------------------------------------------------
    */

    public function periodosVacaciones()
    {
        return $this->hasMany(RrhhVacacionesPeriodo::class, 'empleado_id')->orderBy('numero_periodo');
    }

    public function movimientosVacaciones()
    {
        return $this->hasMany(RrhhVacacionesMovimiento::class, 'empleado_id');
    }

    public function bitacoraVacaciones()
    {
        return $this->hasMany(RrhhVacacionesBitacora::class, 'empleado_id');
    }

    /*
    |--------------------------------------------------------------------------
    | FUNCIONES DE NEGOCIO (ver App\Services\Rrhh\VacacionesService)
    |--------------------------------------------------------------------------
    */

    /**
     * Años completos de servicio a la fecha.
     */
    public function aniosServicio(): int
    {
        return app(VacacionesService::class)->aniosServicio($this);
    }

    /**
     * Días que corresponden por ley según los años de servicio cumplidos.
     */
    public function vacacionesPorLey(): int
    {
        $servicio = app(VacacionesService::class);

        return $servicio->diasPorAntiguedad($servicio->aniosServicio($this));
    }
}
