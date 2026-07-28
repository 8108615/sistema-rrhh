<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    use HasFactory;

    protected $table = 'permisos';

    protected $fillable = [
        'empleado_id',
        'tipo',
        'fecha_inicio',
        'fecha_fin',
        'fecha_retorno',
        'dias_solicitados',
        'motivo',
        'estado',
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }
}
