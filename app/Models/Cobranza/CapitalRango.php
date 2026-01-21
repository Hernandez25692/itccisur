<?php

namespace App\Models\Cobranza;

use Illuminate\Database\Eloquent\Model;
use App\Models\Cobranza\TipoEmpresa;

class CapitalRango extends Model
{
    protected $table = 'cs_capital_rangos';

    protected $fillable = [
        'tipo_empresa_id',
        'capital_min',
        'capital_max',
        'cuota_mensual',
        'inscripcion',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean'
    ];

    public function tipoEmpresa()
    {
        return $this->belongsTo(TipoEmpresa::class, 'tipo_empresa_id');
    }
}
