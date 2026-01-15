<?php

namespace App\Models\Cobranza;

use Illuminate\Database\Eloquent\Model;

class TipoEmpresa extends Model
{
    protected $table = 'cs_tipos_empresa';
    protected $fillable = ['nombre', 'activo'];
    protected $casts = ['activo' => 'boolean'];
}
