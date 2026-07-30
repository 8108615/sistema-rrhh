<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aguinaldo extends Model
{
    use HasFactory;

    protected $table = 'aguinaldos';

    protected $fillable = [
        'empleado_id',
        'gestion',
        'tipo',
        'ultimo_salario',
        'promedio_tres_meses',
        'base_calculo',
        'meses_trabajados',
        'dias_trabajados',
        'monto_pagar',
        'estado',
        'fecha_pago',
        'nro_comprobante',
        'observaciones',
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
}
