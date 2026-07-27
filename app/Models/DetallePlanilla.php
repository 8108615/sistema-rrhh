<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallePlanilla extends Model
{
    use HasFactory;

    // Si tu tabla en la base de datos se llama 'detalle_planillas'
    protected $table = 'detalle_planillas';

    protected $fillable = [
        'planilla_id',
        'empleado_id',
        'salario_base',
        'bonos',
        'descuentos',
        'liquido_pagable',
    ];

    // Pertenece a una cabecera de planilla
    public function planilla()
    {
        return $this->belongsTo(Planilla::class);
    }

    // Pertenece a un empleado
    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
}
