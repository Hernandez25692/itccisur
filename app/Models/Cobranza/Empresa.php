<?php

namespace App\Models\Cobranza;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Empresa extends Model
{
    protected $table = 'cs_empresas';

    protected $fillable = [
        'nombre_empresa',
        'estado_empresa',
        'rtn_empresa',
        'rubro_actividad',
        'categoria_id',
        'tipo_empresa_id',
        'capital_declarado',
        'capital_rango_id',
        'cuota_base',
        'inscripcion_base',
        'cuota_especial',
        'corte_id',
        'tipo_pago',
        'direccion',
        'ciudad',
        'barrio_colonia',
        'latitud',
        'longitud',
        'gerente_adm',
        'gerente_rrhh',
        'gerente_contabilidad',
        'fecha_ultimo_pago',
        'proxima_fecha_cobro',
        'estatus_cobranza',
        'meses_mora',
        'valor_mora',
        'observacion_cobro',
        'fecha_ultimo_pago_historico',
        'comentario'
    ];

    protected $casts = [
        'fecha_ultimo_pago' => 'date',
        'proxima_fecha_cobro' => 'date',
        'capital_declarado' => 'decimal:2',
        'cuota_base' => 'decimal:2',
        'inscripcion_base' => 'decimal:2',
        'cuota_especial' => 'decimal:2',
        'valor_mora' => 'decimal:2',
        'latitud' => 'decimal:7',
        'longitud' => 'decimal:7',
    ];

    public function corte()
    {
        return $this->belongsTo(Corte::class, 'corte_id');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function tipoEmpresa()
    {
        return $this->belongsTo(TipoEmpresa::class, 'tipo_empresa_id');
    }

    public function capitalRango()
    {
        return $this->belongsTo(CapitalRango::class, 'capital_rango_id');
    }

    public function propietarios(): HasMany
    {
        return $this->hasMany(Propietario::class, 'empresa_id');
    }

    public function telefonosFijos(): HasMany
    {
        return $this->hasMany(TelefonoFijo::class, 'empresa_id');
    }

    public function celulares(): HasMany
    {
        return $this->hasMany(Celular::class, 'empresa_id');
    }

    public function correos(): HasMany
    {
        return $this->hasMany(Correo::class, 'empresa_id');
    }

    public function cargos(): HasMany
    {
        return $this->hasMany(Cargo::class, 'empresa_id');
    }

    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class, 'empresa_id');
    }

    public function getCuotaAplicadaAttribute(): float
    {
        return (float) ($this->cuota_especial ?? $this->cuota_base);
    }
}
