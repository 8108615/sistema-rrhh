<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    protected $fillable = [
        'departamento_id',
        'area_id',
        'nombre',
        'apellido',
        'ci',
        'fecha_nacimiento',
        'fecha_ingreso',
        'genero',
        'telefono',
        'direccion',
        'email',
        'nro_cuenta',
        'banco',
        'celular_referencia',
        'parentesco_referencia',
        'salario',
        'estado',
    ];

    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }
    public function pagos()
    {
        return $this->hasMany(PagoEmpleado::class, 'empleado_id');
    }
}
