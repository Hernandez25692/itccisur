<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarioEditorialAdjunto extends Model
{
    use HasFactory;

    protected $table = 'calendario_editorial_adjuntos';

    protected $fillable = [
        'calendario_editorial_id',
        'ruta',
        'nombre_original',
        'mime_type',
        'tamano',
    ];

    public function calendario()
    {
        return $this->belongsTo(CalendarioEditorial::class, 'calendario_editorial_id');
    }
}
