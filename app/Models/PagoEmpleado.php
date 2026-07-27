<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagoEmpleado extends Model
{
    use HasFactory;

    protected $table = 'pago_empleados';

    protected $fillable = [
        'empleado_id',
        'mes',
        'anio',
        'fecha_pago',
        'salario_base',
        'bonos',
        'descuento_afp',
        'anticipos',
        'otros_descuentos',
        'total_pagar',
        'metodo_pago',
        'nro_comprobante',
        'observaciones',
    ];

    // Relación con el empleado
    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }
}
