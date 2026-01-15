<?php

namespace App\Models\Cobranza;

use Illuminate\Database\Eloquent\Model;

class Correo extends Model
{
    protected $table = 'cs_correos';
    protected $fillable = ['empresa_id', 'correo'];
}
