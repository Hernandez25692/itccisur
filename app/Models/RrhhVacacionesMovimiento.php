<?php

namespace App\Models;

use App\Services\Rrhh\VacacionesService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RrhhVacacionesMovimiento extends Model
{
    use HasFactory;

    protected $table = 'rrhh_vacaciones_movimientos';

    /**
     * Tipos de movimiento. En ajuste_manual, dias_equivalentes guarda el efecto
     * sobre el saldo: positivo suma días, negativo resta.
     */
    public const TIPOS = [
        'vacacion_dias' => 'Vacaciones por días',
        'vacacion_horas' => 'Vacaciones por horas',
        'permiso_especial' => 'Permiso especial (no descuenta)',
        'ajuste_manual' => 'Ajuste administrativo',
    ];

    public const ESTADOS = [
        'pendiente' => 'Pendiente',
        'aprobado' => 'Aprobado',
        'rechazado' => 'Rechazado',
        'anulado' => 'Anulado',
    ];

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
        'es_adelanto',
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
        'dias_equivalentes' => 'decimal:2',
        'horas_equivalentes' => 'decimal:2',
        'es_adelanto' => 'boolean',
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

    /*
    |--------------------------------------------------------------------------
    | AYUDAS
    |--------------------------------------------------------------------------
    */

    public function tipoTexto(): string
    {
        return self::TIPOS[$this->tipo_movimiento] ?? $this->tipo_movimiento;
    }

    public function esPorHoras(): bool
    {
        return $this->hora_inicio && $this->hora_fin
            && in_array($this->tipo_movimiento, ['vacacion_horas', 'permiso_especial'], true);
    }

    public function horaInicioCorta(): ?string
    {
        return $this->hora_inicio ? substr($this->hora_inicio, 0, 5) : null;
    }

    public function horaFinCorta(): ?string
    {
        return $this->hora_fin ? substr($this->hora_fin, 0, 5) : null;
    }

    /**
     * Texto para el historial, ej.: "Vacaciones del 05-01-2027 al 09-01-2027 (5 días)".
     */
    public function descripcion(): string
    {
        $inicio = $this->fecha_inicio?->format('d-m-Y');
        $fin = $this->fecha_fin && ! $this->fecha_fin->equalTo($this->fecha_inicio)
            ? $this->fecha_fin->format('d-m-Y')
            : null;
        $rango = $fin ? "del {$inicio} al {$fin}" : "del {$inicio}";
        $horario = $this->esPorHoras() ? ' de ' . $this->horaInicioCorta() . ' a ' . $this->horaFinCorta() : '';
        $cantidad = $this->esPorHoras()
            ? VacacionesService::numero($this->horas_equivalentes) . ' h'
            : VacacionesService::numero(abs((float) $this->dias_equivalentes)) . ' días';

        return match ($this->tipo_movimiento) {
            'vacacion_dias' => "Vacaciones {$rango} ({$cantidad})",
            'vacacion_horas' => "Vacaciones por horas {$rango}{$horario} ({$cantidad})",
            'permiso_especial' => "Permiso especial {$rango}{$horario} ({$cantidad}, no descuenta)",
            'ajuste_manual' => ((float) $this->dias_equivalentes >= 0 ? 'Ajuste a favor' : 'Ajuste en contra')
                . " ({$cantidad})" . ($this->motivo ? ': ' . $this->motivo : ''),
            default => $this->tipoTexto() . " {$rango}",
        };
    }
}
