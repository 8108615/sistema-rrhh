<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RcIvaFormulario;
use App\Models\Empleado;
use Illuminate\Http\Request;

class RcIvaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $periodo = $request->input('periodo');

        $formularios = RcIvaFormulario::with('empleado')
            ->when($search, function ($query, $search) {
                $query->whereHas('empleado', function ($q) use ($search) {
                    $q->where('nombre', 'like', "%{$search}%")
                      ->orWhere('apellido', 'like', "%{$search}%")
                      ->orWhere('ci', 'like', "%{$search}%");
                });
            })
            ->when($periodo, function ($query, $periodo) {
                $query->where('periodo_mes', $periodo);
            })
            ->latest()
            ->paginate(10);

        return view('admin.rc_iva.index', compact('formularios', 'search', 'periodo'));
    }

    public function create()
    {
        $empleados = Empleado::where('estado', true)->get()->map(function ($emp) {
            // Obtenemos el saldo a favor del dependiente del último formulario registrado para este empleado
            $ultimoFormulario = RcIvaFormulario::where('empleado_id', $emp->id)->latest()->first();
            $emp->saldo_anterior = $ultimoFormulario ? $ultimoFormulario->saldo_a_favor_dependiente : 0;
            return $emp;
        });

        return view('admin.rc_iva.create', compact('empleados'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'empleado_id' => 'required|exists:empleados,id',
            'periodo_mes' => 'required|string',
            'sueldo_neto' => 'required|numeric|min:0',
            'dos_salarios_minimos' => 'required|numeric|min:0',
            'total_facturas_presentadas' => 'required|numeric|min:0',
            'saldo_fisco_periodo_anterior' => 'nullable|numeric|min:0',
        ]);

        // Lógica de cálculo contable RC-IVA Bolivia
        $sueldoNeto = $request->sueldo_neto;
        $dosMinimos = $request->dos_salarios_minimos;
        $totalFacturas = $request->total_facturas_presentadas;
        $saldoAnterior = $request->saldo_fisco_periodo_anterior ?? 0;

        // 1. Base imponible (Excedente de los 2 salarios mínimos)
        $baseImponible = max(0, $sueldoNeto - $dosMinimos);

        // 2. Impuesto RC-IVA bruto (13% del excedente)
        $impuestoRcIva = $baseImponible * 0.13;

        // 3. Crédito fiscal de las facturas presentadas (13% del total de facturas)
        $creditoFiscalFacturas = $totalFacturas * 0.13;

        // 4. Cálculo final (Impuesto - Crédito fiscal de facturas - Saldo anterior acumulado)
        $subtotal = $impuestoRcIva - $creditoFiscalFacturas - $saldoAnterior;

        $impuestoRetenido = 0;
        $saldoAFavorDependiente = 0;

        if ($subtotal > 0) {
            // Si sale positivo, el empleado aún debe pagar al fisco (se le descuenta en boleta)
            $impuestoRetenido = $subtotal;
        } else {
            // Si sale negativo o cero, tiene saldo a favor que pasa al siguiente mes
            $saldoAFavorDependiente = abs($subtotal);
        }

        RcIvaFormulario::create([
            'empleado_id' => $request->empleado_id,
            'periodo_mes' => $request->periodo_mes,
            'sueldo_neto' => $sueldoNeto,
            'dos_salarios_minimos' => $dosMinimos,
            'impuesto_rc_iva' => $impuestoRcIva,
            'saldo_fisco_periodo_anterior' => $saldoAnterior,
            'total_facturas_presentadas' => $totalFacturas,
            'credito_fiscal_facturas' => $creditoFiscalFacturas,
            'saldo_a_favor_dependiente' => $saldoAFavorDependiente,
            'impuesto_retenido_fisco' => $impuestoRetenido,
            'estado' => 'Procesado',
        ]);

        return redirect()->route('admin.rc_iva.index')->with('success', 'Formulario RC-IVA registrado y calculado correctamente.');
    }

    public function destroy(RcIvaFormulario $rcIva)
    {
        $rcIva->delete();
        return redirect()->route('admin.rc_iva.index')->with('success', 'Registro RC-IVA eliminado correctamente.');
    }
}
