<?php

namespace App\Models\Cobranza;

use Illuminate\Database\Eloquent\Model;

class Ruta extends Model
{
    protected $table = 'cs_rutas';

    protected $fillable = [
        'fecha_ruta',
        'nombre',
        'gestor_id',
        'estado',
        'ventana_dias_cobro',
        'solo_con_coordenadas',
        'incluir_en_mora',
        'total_empresas',
        'observaciones',
        'creado_por',
        'confirmado_por',
        'confirmado_en',
    ];

    protected $casts = [
        'fecha_ruta' => 'date',
        'solo_con_coordenadas' => 'boolean',
        'incluir_en_mora' => 'boolean',
        'confirmado_en' => 'datetime',
    ];

    // =====================
    // RELACIONES
    // =====================

    public function gestor()
    {
        return $this->belongsTo(\App\Models\User::class, 'gestor_id');
    }

    public function empresas()
    {
        return $this->belongsToMany(
            Empresa::class,
            'cs_ruta_empresas',
            'ruta_id',
            'empresa_id'
        )->withPivot([
            'orden',
            'estado_visita',
            'nota_visita',
            'checked_at',
        ])->orderBy('pivot_orden');
    }
}
