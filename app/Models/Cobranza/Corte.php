<?php

namespace App\Models\Cobranza;

use Illuminate\Database\Eloquent\Model;

class Corte extends Model
{
    protected $table = 'cs_cortes';

    protected $fillable = [
        'dia_corte',
        'nombre',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];
}
