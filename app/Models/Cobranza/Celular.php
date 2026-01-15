<?php

namespace App\Models\Cobranza;

use Illuminate\Database\Eloquent\Model;

class Celular extends Model
{
    protected $table = 'cs_celulares';
    protected $fillable = ['empresa_id', 'celular'];
}
