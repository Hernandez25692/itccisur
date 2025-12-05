<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanRevision extends Model
{
    protected $fillable = [
        'plan_trabajo_id',
        'user_id',
        'accion',
        'comentario'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function plan()
    {
        return $this->belongsTo(PlanTrabajo::class, 'plan_trabajo_id');
    }
}
