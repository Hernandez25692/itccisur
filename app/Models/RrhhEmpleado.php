<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

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
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONES
    |--------------------------------------------------------------------------
    */

    public function periodosVacaciones()
    {
        return $this->hasMany(RrhhVacacionesPeriodo::class, 'empleado_id');
    }

    public function movimientosVacaciones()
    {
        return $this->hasMany(RrhhVacacionesMovimiento::class, 'empleado_id');
    }

    /*
    |--------------------------------------------------------------------------
    | FUNCIONES DE NEGOCIO
    |--------------------------------------------------------------------------
    */

    public function calcularAntiguedad()
    {
        return Carbon::parse($this->fecha_contratacion)->diffInYears(now());
    }

    public function diasVacacionesPorAntiguedad()
    {
        $anios = $this->calcularAntiguedad();

        if ($anios >= 4) {
            return 20;
        }

        if ($anios == 3) {
            return 15;
        }

        if ($anios == 2) {
            return 12;
        }

        if ($anios == 1) {
            return 10;
        }

        return 0;
    }

    public function vacacionesPorLey()
    {
        $inicio = Carbon::parse($this->fecha_contratacion);
        $hoy = Carbon::now();

        $anios = $inicio->diffInYears($hoy);

        if ($anios >= 4) {
            return 20;
        }

        if ($anios >= 3) {
            return 15;
        }

        if ($anios >= 2) {
            return 12;
        }

        if ($anios >= 1) {
            return 10;
        }

        return 0;
    }
    public function saldoVacacionesActual()
    {
        $vacacionesLey = $this->vacacionesPorLey();

        $tomado = $this->movimientosVacaciones()
            ->sum('dias_equivalentes');

        return $vacacionesLey - $tomado;
    }
}
