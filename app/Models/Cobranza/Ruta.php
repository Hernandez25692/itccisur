<?php

namespace App\Models\Cobranza;

use Illuminate\Database\Eloquent\Model;

class Ruta extends Model
{
    protected $table = 'cs_rutas';

    protected $fillable = [
        'nombre',
        'fecha_ruta',
        'gestor_id',
        'estado',
        'comentario'
    ];

    protected $casts = [
        'fecha_ruta' => 'date',
    ];

    public function empresas()
    {
        return $this->belongsToMany(Empresa::class, 'cs_ruta_empresas', 'ruta_id', 'empresa_id')
            ->withPivot(['orden', 'estado_visita', 'nota_visita', 'checked_at'])
            ->withTimestamps()
            ->orderBy('cs_ruta_empresas.orden');
    }
}
