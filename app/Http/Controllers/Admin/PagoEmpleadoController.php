<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PagoEmpleado;
use App\Models\Empleado;
use App\Models\Ajuste;
use Illuminate\Http\Request;

class PagoEmpleadoController extends Controller
{
    // Método privado para obtener el símbolo de la moneda actual
    private function getMoneda()
    {
        $ajuste = Ajuste::first();
        return $ajuste->divisa ?? 'Bs.';
    }

    public function index(Request $request)
    {
        $buscar = $request->get('buscar');
        $simboloMoneda = $this->getMoneda();

        $pagos = PagoEmpleado::with(['empleado.area', 'empleado']) // Ajusta 'empleado.area' según tu relación
            ->when($buscar, function ($query, $buscar) {
                return $query->where('mes', 'like', "%{$buscar}%")
                             ->orWhere('anio', 'like', "%{$buscar}%")
                             ->orWhere('nro_comprobante', 'like', "%{$buscar}%")
                             ->orWhereHas('empleado', function ($q) use ($buscar) {
                                 $q->where('nombre', 'like', "%{$buscar}%")
                                   ->orWhere('apellido', 'like', "%{$buscar}%")
                                   ->orWhere('ci', 'like', "%{$buscar}%");
                             });
            })
            ->oldest() // Orden ascendente (del más antiguo al más nuevo)
            ->paginate(10);

        return view('admin.pagos.index', compact('pagos', 'buscar', 'simboloMoneda'));
    }

    public function create()
    {
        // Solo traemos empleados activos
        $empleados = Empleado::where('estado', 1)->with('area')->get();
        $simboloMoneda = $this->getMoneda();

        return view('admin.pagos.create', compact('empleados', 'simboloMoneda'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'empleado_id' => 'required|exists:empleados,id',
            'mes' => 'required|string',
            'anio' => 'required|string|max:4',
            'fecha_pago' => 'required|date',
            'salario_base' => 'required|numeric|min:0',
            'bonos' => 'nullable|numeric|min:0',
            'descuento_afp' => 'nullable|numeric|min:0',
            'anticipos' => 'nullable|numeric|min:0',
            'otros_descuentos' => 'nullable|numeric|min:0',
            'metodo_pago' => 'required|string',
        ]);

        $salario_base = $request->salario_base;
        $bonos = $request->bonos ?? 0;
        $descuento_afp = $request->descuento_afp ?? 0;
        $anticipos = $request->anticipos ?? 0;
        $otros_descuentos = $request->otros_descuentos ?? 0;

        // Cálculo matemático del total líquido a pagar
        $total_pagar = ($salario_base + $bonos) - ($descuento_afp + $anticipos + $otros_descuentos);

        PagoEmpleado::create([
            'empleado_id' => $request->empleado_id,
            'mes' => $request->mes,
            'anio' => $request->anio,
            'fecha_pago' => $request->fecha_pago,
            'salario_base' => $salario_base,
            'bonos' => $bonos,
            'descuento_afp' => $descuento_afp,
            'anticipos' => $anticipos,
            'otros_descuentos' => $otros_descuentos,
            'total_pagar' => max(0, $total_pagar), // Evita valores negativos
            'metodo_pago' => $request->metodo_pago,
            'nro_comprobante' => $request->nro_comprobante,
            'observaciones' => $request->observaciones,
        ]);

        return redirect()->route('admin.pagos.index')->with([
            'mensaje' => 'Pago a empleado registrado correctamente.',
            'icono' => 'success'
        ]);
    }

    public function show($id)
    {
        $pago = PagoEmpleado::with(['empleado.departamento', 'empleado.area'])->findOrFail($id);
        $simboloMoneda = $this->getMoneda();

        return view('admin.pagos.show', compact('pago', 'simboloMoneda'));
    }

    public function edit($id)
    {
        $pago = PagoEmpleado::findOrFail($id);
        // Cargamos los empleados activos con su respectiva área
        $empleados = Empleado::where('estado', 1)->with('area')->get();
        $simboloMoneda = $this->getMoneda();

        return view('admin.pagos.edit', compact('pago', 'empleados', 'simboloMoneda'));
    }

    public function update(Request $request, $id)
    {
        $pago = PagoEmpleado::findOrFail($id);

        $request->validate([
            'empleado_id' => 'required|exists:empleados,id',
            'mes' => 'required|string',
            'anio' => 'required|string|max:4',
            'fecha_pago' => 'required|date',
            'salario_base' => 'required|numeric|min:0',
            'bonos' => 'nullable|numeric|min:0',
            'descuento_afp' => 'nullable|numeric|min:0',
            'anticipos' => 'nullable|numeric|min:0',
            'otros_descuentos' => 'nullable|numeric|min:0',
            'metodo_pago' => 'required|string',
        ]);

        $salario_base = $request->salario_base;
        $bonos = $request->bonos ?? 0;
        $descuento_afp = $request->descuento_afp ?? 0;
        $anticipos = $request->anticipos ?? 0;
        $otros_descuentos = $request->otros_descuentos ?? 0;

        $total_pagar = ($salario_base + $bonos) - ($descuento_afp + $anticipos + $otros_descuentos);

        $pago->update([
            'empleado_id' => $request->empleado_id,
            'mes' => $request->mes,
            'anio' => $request->anio,
            'fecha_pago' => $request->fecha_pago,
            'salario_base' => $salario_base,
            'bonos' => $bonos,
            'descuento_afp' => $descuento_afp,
            'anticipos' => $anticipos,
            'otros_descuentos' => $otros_descuentos,
            'total_pagar' => max(0, $total_pagar),
            'metodo_pago' => $request->metodo_pago,
            'nro_comprobante' => $request->nro_comprobante,
            'observaciones' => $request->observaciones,
        ]);

        return redirect()->route('admin.pagos.index')->with([
            'mensaje' => 'Registro de pago actualizado correctamente.',
            'icono' => 'success'
        ]);
    }

    public function destroy($id)
    {
        $pago = PagoEmpleado::findOrFail($id);
        $pago->delete();

        return redirect()->route('admin.pagos.index')->with([
            'mensaje' => 'Registro de pago eliminado correctamente.',
            'icono' => 'success'
        ]);
    }

    public function print($id)
    {
        $pago = PagoEmpleado::with(['empleado.area'])->findOrFail($id);
        $ajuste = Ajuste::first();
        $simboloMoneda = $ajuste->divisa ?? 'Bs.';

        return view('admin.pagos.print', compact('pago', 'ajuste', 'simboloMoneda'));
    }
}
