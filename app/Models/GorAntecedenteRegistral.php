<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GorAntecedenteRegistral extends Model
{
    use HasFactory;

    protected $table = 'gor_antecedentes_registrales';

    protected $fillable = [
        'centro',
        'circunscripcion',
        'fecha_recepcion',
        'solicitante_nombre',
        'solicitante_direccion',
        'numero_exequatur',
        'asiento_tomo_matricula',
        'tipo_libro',
        'motivo',
        'created_by',
        'updated_by',
    ];
}
