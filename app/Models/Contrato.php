<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contrato extends Model
{
    use HasFactory;

    protected $table = 'contratos';

    protected $fillable = [
        'empleado_id',
        'tipo_contrato',
        'fecha_inicio',
        'fecha_fin',
        'salario_mensual',
        'cargo_contrato',
        'archivo_pdf',
        'estado',
        'observaciones',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'salario_mensual' => 'decimal:2',
    ];

    // Relación: Un contrato pertenece a un empleado
    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }
}
