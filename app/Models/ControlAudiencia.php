<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ControlAudiencia extends Model
{
    use HasFactory;

    protected $table = 'control_audiencias';

    protected $fillable = [
        'nombre_solicitante',
        'fecha_recepcion',
        'fecha_hora_atencion',
        'motivo',
        'numero_documento',
        'dictamen',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'fecha_recepcion' => 'date',
        'fecha_hora_atencion' => 'datetime',
    ];

    public function creador()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
