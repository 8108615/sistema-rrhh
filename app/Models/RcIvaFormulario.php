<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RcIvaFormulario extends Model
{
    use HasFactory;

    protected $table = 'rc_iva_formularios';

    protected $fillable = [
        'empleado_id',
        'periodo_mes',
        'sueldo_neto',
        'dos_salarios_minimos',
        'impuesto_rc_iva',
        'saldo_fisco_periodo_anterior',
        'total_facturas_presentadas',
        'credito_fiscal_facturas',
        'saldo_a_favor_dependiente',
        'impuesto_retenido_fisco',
        'estado',
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
}
