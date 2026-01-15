<?php

namespace App\Models\Cobranza;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RutaEmpresa extends Model
{
    protected $table = 'cs_ruta_empresas';

    protected $fillable = [
        'ruta_id',
        'empresa_id',
        'orden',
        'dist_km_desde_anterior',
        'estado_visita',
        'nota_visita',
    ];

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function ruta(): BelongsTo
    {
        return $this->belongsTo(Ruta::class, 'ruta_id');
    }
}
