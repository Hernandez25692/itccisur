<?php

namespace App\Models\Cobranza;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'cs_categorias';
    protected $fillable = ['nombre', 'descripcion', 'activo'];
    protected $casts = ['activo' => 'boolean'];
}
