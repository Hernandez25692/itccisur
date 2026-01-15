<?php

namespace App\Models\Cobranza;

use Illuminate\Database\Eloquent\Model;

class Propietario extends Model
{
    protected $table = 'cs_propietarios';
    protected $fillable = ['empresa_id', 'nombre', 'identidad', 'rtn'];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }
}
