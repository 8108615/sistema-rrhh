<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Retroactivo extends Model
{
    use HasFactory;

    protected $table = 'retroactivos';

    protected $fillable = [
        'empleado_id',
        'gestion',
        'porcentaje',
        'sueldo_anterior',
        'sueldo_nuevo',
        'diferencia_mensual',
        'meses_aplicados',
        'monto_pagar',
        'estado',
        'fecha_pago',
        'observaciones',
    ];

    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class);
    }
}