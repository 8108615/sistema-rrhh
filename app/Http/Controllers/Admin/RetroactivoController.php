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
        // Validamos usando 'porcentaje'
        $request->validate([
            'empleado_id' => 'required|exists:empleados,id',
            'gestion' => 'required|digits:4',
            'porcentaje' => 'required|numeric|min:0',
            'meses_aplicados' => 'required|integer|min:1|max:12',
            'estado' => 'required|in:Pendiente,Pagado',
            'fecha_pago' => 'nullable|date',
            'observaciones' => 'nullable|string',
        ]);

        $empleado = Empleado::findOrFail($request->empleado_id);
        $sueldoAnterior = $empleado->salario ?? ($empleado->sueldo_base ?? 0);

        // Tomamos el porcentaje del formulario
        $porcentaje = $request->porcentaje;

        // Cálculos
        $sueldoNuevo = $sueldoAnterior + ($sueldoAnterior * ($porcentaje / 100));
        $diferenciaMensual = $sueldoNuevo - $sueldoAnterior;
        $mesesAplicados = $request->meses_aplicados;
        $montoPagar = $diferenciaMensual * $mesesAplicados;

        // Guardamos en la base de datos (¡Asegúrate de incluir 'porcentaje' aquí!)
        Retroactivo::create([
            'empleado_id' => $request->empleado_id,
            'gestion' => $request->gestion,
            'porcentaje' => $porcentaje,
            'sueldo_anterior' => round($sueldoAnterior, 2),
            'sueldo_nuevo' => round($sueldoNuevo, 2),
            'diferencia_mensual' => round($diferenciaMensual, 2),
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

        // 1. Obtener los IDs de los empleados que YA tienen un retroactivo en esta gestión
        $empleadosConRetroactivoIds = Retroactivo::where('gestion', $gestion)
            ->pluck('empleado_id')
            ->toArray();

        // 2. Traer únicamente a los empleados activos que NO están en la lista anterior
        $empleados = Empleado::where('estado', true)
            ->whereNotIn('id', $empleadosConRetroactivoIds)
            ->get();

        if ($empleados->isEmpty()) {
            return redirect()->route('admin.retroactivos.index', ['gestion' => $gestion])
                ->with('mensaje', "No hay nuevos empleados pendientes. Todos ya cuentan con su cálculo para la gestión $gestion.")
                ->with('icono', 'info');
        }

        // Usamos una transacción para asegurar que si algo falla, no se guarde a medias
        \DB::transaction(function () use ($empleados, $gestion, $porcentaje, $mesesAplicados, &$contadorNuevos) {
            $contadorNuevos = 0;

            foreach ($empleados as $empleado) {
                $sueldoAnterior = $empleado->salario ?? ($empleado->sueldo_base ?? 0);

                if ($sueldoAnterior <= 0) continue;

                $sueldoNuevo = $sueldoAnterior + ($sueldoAnterior * ($porcentaje / 100));
                $diferenciaMensual = $sueldoNuevo - $sueldoAnterior;
                $montoPagar = $diferenciaMensual * $mesesAplicados;

                // 3. Crear el registro de Retroactivo
                Retroactivo::create([
                    'empleado_id' => $empleado->id,
                    'gestion' => $gestion,
                    'porcentaje' => $porcentaje,
                    'sueldo_anterior' => round($sueldoAnterior, 2),
                    'sueldo_nuevo' => round($sueldoNuevo, 2),
                    'diferencia_mensual' => round($diferenciaMensual, 2),
                    'meses_aplicados' => $mesesAplicados,
                    'monto_pagar' => round($montoPagar, 2),
                    'estado' => 'Pendiente'
                ]);

                // 4. Actualizar automáticamente el salario base oficial en la tabla EMPLEADOS
                $empleado->update([
                    'salario' => round($sueldoNuevo, 2)
                ]);

                // * Nota: Ya no generamos registros automáticos en pago_empleados aquí. 
                // El pago de salarios se registrará estrictamente cuando corresponda procesar el pago.

                $contadorNuevos++;
            }
        });

        return redirect()->route('admin.retroactivos.index', ['gestion' => $gestion])
            ->with('mensaje', "Se procesaron $contadorNuevos registros de retroactivos y se actualizaron los sueldos de los empleados correctamente.")
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
            'porcentaje' => 'required|numeric|min:0', // <--- Añadido aquí
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
            'porcentaje' => $request->porcentaje, // <--- ¡Añadido aquí también!
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

        // Revertir el salario del empleado a su valor anterior
        $empleado = Empleado::find($retroactivo->empleado_id);
        if ($empleado) {
            $empleado->update([
                'salario' => $retroactivo->sueldo_anterior
            ]);
        }

        // Eliminar el registro retroactivo
        $retroactivo->delete();

        return redirect()->route('admin.retroactivos.index', ['gestion' => $gestion])
            ->with('mensaje', 'Registro retroactivo eliminado y salario del empleado restaurado correctamente.')
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