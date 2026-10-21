<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Viaje extends Model
{
    use HasFactory;

    protected $table = 'viajes';

    protected $fillable = [
        'origen',
        'destino',
        'estadio',
        'motivo',
        'fecha_inicio',
        'fecha_fin',
        'dias_totales',
        'monto_diario',
        'total_por_persona',
        'monto_total_viaje',
        'estado',
    ];

    /**
     * Relación muchos a muchos con Empleados a través de la tabla pivote empleado_viaje.
     */
    public function empleados()
    {
        return $this->belongsToMany(Empleado::class, 'empleado_viaje', 'viaje_id', 'empleado_id')
                    ->withPivot('monto_viatico')
                    ->withTimestamps();
    }
}
