<?php

namespace App\Models\Cobranza;

use Illuminate\Database\Eloquent\Model;

class CapitalRango extends Model
{
    protected $table = 'cs_capital_rangos';
    protected $fillable = ['capital_min', 'capital_max', 'cuota_mensual', 'inscripcion', 'activo'];
    protected $casts = ['activo' => 'boolean'];
}
