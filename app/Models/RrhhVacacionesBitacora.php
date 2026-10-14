<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    protected $casts = [
        'fecha_evento' => 'datetime',
    ];

    public function empleado()
    {
        return $this->belongsTo(RrhhEmpleado::class, 'empleado_id');
    }

    public function movimiento()
    {
        return $this->belongsTo(RrhhVacacionesMovimiento::class, 'movimiento_id');
    }

    public static function registrar(int $empleadoId, string $accion, string $detalle, ?int $movimientoId = null, ?string $usuario = null): self
    {
        return self::create([
            'empleado_id' => $empleadoId,
            'movimiento_id' => $movimientoId,
            'accion' => $accion,
            'detalle' => $detalle,
            'usuario' => $usuario ?? auth()->user()?->name ?? 'Sistema',
            'fecha_evento' => now(),
        ]);
    }
}
