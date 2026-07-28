<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Finiquito extends Model
{
    use HasFactory;

    protected $table = 'finiquitos';

    protected $fillable = [
        'empleado_id',
        'fecha_ingreso',
        'fecha_retiro',
        'causal_retiro',
        'ultimo_salario',
        'promedio_tres_salarios',
        'anos_servicio',
        'monto_indemnizacion',
        'monto_desahucio',
        'monto_vacacion',
        'monto_aguinaldo',
        'total_beneficios',
        'observaciones',
    ];

    /**
     * Relación con el modelo Empleado.
     */
    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
}
