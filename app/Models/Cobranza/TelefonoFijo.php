<?php

namespace App\Models\Cobranza;

use Illuminate\Database\Eloquent\Model;

class TelefonoFijo extends Model
{
    protected $table = 'cs_telefonos_fijos';
    protected $fillable = ['empresa_id', 'telefono'];
}
