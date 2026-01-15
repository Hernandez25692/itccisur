<?php

namespace App\Models\Cobranza;

use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    protected $table = 'cs_cargos';

    protected $fillable = [
        'empresa_id',
        'periodo_inicio',
        'periodo_fin',
        'fecha_vencimiento',
        'monto_cuota',
        'monto_mora',
        'total',
        'estado',
        'pagado_en',
        'created_by'
    ];

    protected $casts = [
        'periodo_inicio' => 'date',
        'periodo_fin' => 'date',
        'fecha_vencimiento' => 'date',
        'pagado_en' => 'datetime',
        'monto_cuota' => 'decimal:2',
        'monto_mora' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }
}
