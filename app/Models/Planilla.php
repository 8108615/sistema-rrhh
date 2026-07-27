<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Planilla extends Model
{
    use HasFactory;

    protected $fillable = [
        'mes',
        'anio',
        'total_pagado',
        'estado', // 'Pendiente' o 'Pagado'
    ];

    // Una planilla tiene muchos detalles (empleados asignados)
    public function detalles()
    {
        return $this->hasMany(DetallePlanilla::class);
    }
}
