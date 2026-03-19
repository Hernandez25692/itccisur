<?php

namespace App\Models\Cobranza;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Pago extends Model
{
    protected $table = 'cs_pagos';

    protected $fillable = [
        'empresa_id',
        'fecha_pago',
        'monto',
        'metodo',
        'referencia',
        'comentario',
        'gestor_id',
        'created_by'
    ];

    protected $casts = [
        'fecha_pago' => 'date',
        'monto' => 'decimal:2',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

  



    public function usuario()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }


    public function gestor()
    {
        return $this->belongsTo(User::class, 'gestor_id');
    }

    public function cargos()
    {
        return $this->belongsToMany(Cargo::class, 'cs_pago_cargo')
            ->withPivot('monto_aplicado');
    }
}
