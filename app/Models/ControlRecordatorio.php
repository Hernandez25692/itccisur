<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ControlRecordatorio extends Model
{
    use HasFactory;

    protected $table = 'control_recordatorios';

    protected $fillable = [
        'user_id',
        'actividad',
        'tipo',
        'descripcion',
        'fecha_ejecucion',
        'fecha_vencimiento',
        'dias_recordatorio',
        'notificar',
        'atendido',
    ];

    protected $casts = [
        'fecha_ejecucion'   => 'date',
        'fecha_vencimiento' => 'date',
        'notificar'         => 'boolean',
        'atendido'          => 'boolean',
    ];

    // Relación con usuario (quien creó el control)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Estado calculado según fechas (vigente / por_vencer / vencido)
    public function getEstadoActualAttribute()
    {
        $hoy = Carbon::today();

        if ($this->fecha_vencimiento->isPast() && !$this->fecha_vencimiento->isToday()) {
            return 'vencido';
        }

        // Rango de alerta: dentro de dias_recordatorio
        if (
            $this->fecha_vencimiento->diffInDays($hoy, false) <= $this->dias_recordatorio &&
            $this->fecha_vencimiento->greaterThanOrEqualTo($hoy)
        ) {
            return 'por_vencer';
        }

        return 'vigente';
    }
}
