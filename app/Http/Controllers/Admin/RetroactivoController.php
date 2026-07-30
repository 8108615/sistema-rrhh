<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Retroactivo;
use App\Models\Empleado;
use App\Models\Ajuste;
use Illuminate\Http\Request;

class RetroactivoController extends Controller
{
    public function index(Request $request)
    {
        $gestion = $request->get('gestion', date('Y'));

        $retroactivos = Retroactivo::with(['empleado.area'])
            ->where('gestion', $gestion)
            ->paginate(10)
            ->withQueryString();

        $simboloMoneda = Ajuste::first()->divisa ?? 'Bs.';

        return view('admin.retroactivos.index', compact('retroactivos', 'gestion', 'simboloMoneda'));
    }

    public function create()
    {
        $empleados = Empleado::where('estado', true)->get();
        $simboloMoneda = Ajuste::first()->divisa ?? 'Bs.';

        return view('admin.retroactivos.create', compact('empleados', 'simboloMoneda'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'empleado_id' => 'required|exists:empleados,id',
            'gestion' => 'required|digits:4',
            'sueldo_anterior' => 'required|numeric|min:0',
            'sueldo_nuevo' => 'required|numeric|min:0',
            'diferencia_mensual' => 'required|numeric|min:0',
            'meses_aplicados' => 'required|integer|min:1|max:12',
            'monto_pagar' => 'required|numeric|min:0',
            'estado' => 'required|in:Pendiente,Pagado',
            'fecha_pago' => 'nullable|date',
            'observaciones' => 'nullable|string',
        ]);

        $empleado = Empleado::findOrFail($request->empleado_id);
        $sueldoAnterior = $empleado->salario ?? ($empleado->sueldo_base ?? 0);

        $porcentaje = $request->porcentaje;
        $sueldoNuevo = $sueldoAnterior + ($sueldoAnterior * ($porcentaje / 100));
        $diferenciaMensual = $sueldoNuevo - $sueldoAnterior;
        $mesesAplicados = $request->meses_aplicados;
        $montoPagar = $diferenciaMensual * $mesesAplicados;

        Retroactivo::create([
            'empleado_id' => $request->empleado_id,
            'gestion' => $request->gestion,
            'sueldo_anterior' => $sueldoAnterior,
            'sueldo_nuevo' => $sueldoNuevo,
            'diferencia_mensual' => $diferenciaMensual,
            'meses_aplicados' => $mesesAplicados,
            'monto_pagar' => round($montoPagar, 2),
            'estado' => $request->estado,
            'fecha_pago' => $request->fecha_pago,
            'observaciones' => $request->observaciones,
        ]);

        return redirect()->route('admin.retroactivos.index', ['gestion' => $request->gestion])
            ->with('mensaje', 'Registro retroactivo creado correctamente.')
            ->with('icono', 'success');
    }

    public function calcularMasivo(Request $request)
    {
        $request->validate([
            'gestion' => 'required|digits:4|integer',
            'porcentaje' => 'required|numeric|min:0|max:100',
            'meses_aplicados' => 'required|integer|min:1|max:12',
        ]);

        $gestion = $request->gestion;
        $porcentaje = $request->porcentaje;
        $mesesAplicados = $request->meses_aplicados;

        $empleados = Empleado::where('estado', true)->get();

        foreach ($empleados as $empleado) {
            $sueldoAnterior = $empleado->salario ?? ($empleado->sueldo_base ?? 0);
            if ($sueldoAnterior <= 0) continue;

            $sueldoNuevo = $sueldoAnterior + ($sueldoAnterior * ($porcentaje / 100));
            $diferenciaMensual = $sueldoNuevo - $sueldoAnterior;
            $montoPagar = $diferenciaMensual * $mesesAplicados;

            Retroactivo::updateOrCreate(
                [
                    'empleado_id' => $empleado->id,
                    'gestion' => $gestion
                ],
                [
                    'sueldo_anterior' => $sueldoAnterior,
                    'sueldo_nuevo' => round($sueldoNuevo, 2),
                    'diferencia_mensual' => round($diferenciaMensual, 2),
                    'meses_aplicados' => $mesesAplicados,
                    'monto_pagar' => round($montoPagar, 2),
                    'estado' => 'Pendiente'
                ]
            );
        }

        return redirect()->route('admin.retroactivos.index', ['gestion' => $gestion])
            ->with('mensaje', "Planilla masiva de retroactivos para la gestión $gestion calculada con éxito.")
            ->with('icono', 'success');
    }

    public function show($id)
    {
        $retroactivo = Retroactivo::with(['empleado.area'])->findOrFail($id);
        $ajuste = Ajuste::first();
        $simboloMoneda = $ajuste->divisa ?? 'Bs.';

        return view('admin.retroactivos.show', compact('retroactivo', 'ajuste', 'simboloMoneda'));
    }

    public function edit($id)
    {
        $retroactivo = Retroactivo::with(['empleado'])->findOrFail($id);
        $empleados = Empleado::where('estado', true)->get();
        $ajuste = Ajuste::first();
        $simboloMoneda = $ajuste->divisa ?? 'Bs.';

        return view('admin.retroactivos.edit', compact('retroactivo', 'empleados', 'simboloMoneda'));
    }

    public function update(Request $request, $id)
    {
        $retroactivo = Retroactivo::findOrFail($id);

        $request->validate([
            'empleado_id' => 'required|exists:empleados,id',
            'gestion' => 'required|digits:4|integer',
            'sueldo_anterior' => 'required|numeric|min:0',
            'sueldo_nuevo' => 'required|numeric|min:0',
            'meses_aplicados' => 'required|integer|min:1|max:12',
            'monto_pagar' => 'required|numeric|min:0',
            'estado' => 'required|in:Pendiente,Pagado',
            'fecha_pago' => 'nullable|date',
            'observaciones' => 'nullable|string|max:500',
        ]);

        $diferenciaMensual = $request->sueldo_nuevo - $request->sueldo_anterior;

        $retroactivo->update([
            'empleado_id' => $request->empleado_id,
            'gestion' => $request->gestion,
            'sueldo_anterior' => $request->sueldo_anterior,
            'sueldo_nuevo' => $request->sueldo_nuevo,
            'diferencia_mensual' => $diferenciaMensual,
            'meses_aplicados' => $request->meses_aplicados,
            'monto_pagar' => $request->monto_pagar,
            'estado' => $request->estado,
            'fecha_pago' => $request->fecha_pago,
            'observaciones' => $request->observaciones,
        ]);

        return redirect()->route('admin.retroactivos.index', ['gestion' => $retroactivo->gestion])
            ->with('mensaje', 'Registro retroactivo actualizado correctamente.')
            ->with('icono', 'success');
    }

    public function destroy($id)
    {
        $retroactivo = Retroactivo::findOrFail($id);
        $gestion = $retroactivo->gestion;
        $retroactivo->delete();

        return redirect()->route('admin.retroactivos.index', ['gestion' => $gestion])
            ->with('mensaje', 'Registro retroactivo eliminado correctamente.')
            ->with('icono', 'success');
    }

    public function print($id)
    {
        $retroactivo = Retroactivo::with(['empleado.area'])->findOrFail($id);
        $ajuste = Ajuste::first();
        $simboloMoneda = $ajuste->divisa ?? 'Bs.';

        return view('admin.retroactivos.print', compact('retroactivo', 'ajuste', 'simboloMoneda'));
    }

    public function printGeneral(Request $request)
    {
        $gestion = $request->get('gestion', date('Y'));

        $retroactivos = Retroactivo::with(['empleado.area'])
            ->where('gestion', $gestion)
            ->get();

        $ajuste = Ajuste::first();
        $simboloMoneda = $ajuste->divisa ?? 'Bs.';

        return view('admin.retroactivos.print-general', compact('retroactivos', 'gestion', 'ajuste', 'simboloMoneda'));
    }
}
