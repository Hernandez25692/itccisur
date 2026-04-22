<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ValidacionQr extends Model
{
    protected $table = 'validaciones_qr';

    protected $fillable = [
        'nombre_formacion',
        'fecha_formacion',
        'anio',
        'token',
        'activo',
    ];
}
