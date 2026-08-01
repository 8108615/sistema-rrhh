<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Planilla;
use App\Models\DetallePlanilla;
use App\Models\Empleado;
use App\Models\Ajuste;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class PlanillaController extends Controller
{
    // Mostrar listado de planillas históricas
    public function index(Request $request)
    {
        $buscar = $request->get('buscar');

        $planillas = Planilla::when($buscar, function ($query, $buscar) {
                return $query->where('mes', 'like', "%{$buscar}%")
                           ->orWhere('anio', 'like', "%{$buscar}%")
                           ->orWhere('estado', 'like', "%{$buscar}%");
            })
            ->orderBy('anio', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(10);

        $ajuste = Ajuste::first(); // <-- Añadido aquí

        return view('admin.planillas.index', compact('planillas', 'buscar', 'ajuste')); // <-- Añadido aquí
    }

    // Vista para crear/generar una nueva planilla
    public function create()
    {
        $ajuste = Ajuste::first();
        return view('admin.planillas.create', compact('ajuste'));
    }

    // Guardar y generar automáticamente la planilla con los empleados activos
    public function store(Request $request)
    {
        $request->validate([
            'mes' => 'required|string|max:50',
            'anio' => 'required|digits:4|integer|min:2000|max:2100',
        ]);

        // Validar si ya existe una planilla para ese mes y año
        $existe = Planilla::where('mes', $request->mes)->where('anio', $request->anio)->exists();
        if ($existe) {
            return back()->withErrors(['mes' => 'Ya existe una planilla registrada para este mes y año.'])->withInput();
        }

        // Obtener todos los empleados activos
        $empleadosActivos = Empleado::where('estado', true)->get();

        if ($empleadosActivos->isEmpty()) {
            return back()->withErrors(['error' => 'No hay empleados activos registrados para generar la planilla.'])->withInput();
        }

        DB::transaction(function () use ($request, $empleadosActivos) {
            // 1. Crear la cabecera de la planilla
            $planilla = Planilla::create([
                'mes' => $request->mes,
                'anio' => $request->anio,
                'total_pagado' => 0,
                'estado' => 'Pendiente',
            ]);

            $totalGeneral = 0;

            // 2. Recorrer empleados activos y registrar el detalle inicial con cálculo de AFP (12.71%)
            foreach ($empleadosActivos as $empleado) {
                $salarioBase = $empleado->salario ?? 0;
                $bonos = 0;          // Puedes ajustarlo si manejas bonos adicionales

                // Cálculo automático del descuento de AFP laboral en Bolivia (12.71%)
                $descuentos = $salarioBase * 0.1271;

                $liquidoPagable = ($salarioBase + $bonos) - $descuentos;

                DetallePlanilla::create([
                    'planilla_id' => $planilla->id,
                    'empleado_id' => $empleado->id,
                    'salario_base' => $salarioBase,
                    'bonos' => $bonos,
                    'descuentos' => $descuentos,
                    'liquido_pagable' => $liquidoPagable,
                ]);

                $totalGeneral += $liquidoPagable;
            }

            // 3. Actualizar el monto total pagado en la cabecera
            $planilla->update(['total_pagado' => $totalGeneral]);
        });

        return redirect()->route('admin.planillas.index')->with('success', 'Planilla generada correctamente con el cálculo de AFP aplicado.');
    }

    // Mostrar el detalle completo de una planilla
    public function show($id)
    {
        $planilla = Planilla::with('detalles.empleado')->findOrFail($id);
        $ajuste = Ajuste::first(); // O la lógica que uses para obtener los ajustes generales

        return view('admin.planillas.show', compact('planilla', 'ajuste'));
    }

    // Eliminar una planilla y sus detalles en cascada
    public function destroy($id)
    {
        $planilla = Planilla::findOrFail($id);
        $planilla->delete(); // Gracias a onDelete('cascade') en la migración, se borran los detalles automáticamente

        return redirect()->route('admin.planillas.index')->with('success', 'Planilla eliminada correctamente.');
    }

    public function pdf($id)
    {
        // Cargamos la planilla con sus detalles, empleado y el departamento del empleado
        $planilla = Planilla::with('detalles.empleado.departamento')->findOrFail($id);
        $ajuste = Ajuste::first();

        // Generamos el PDF utilizando la vista
        $pdf = Pdf::loadView('admin.planillas.pdf', compact('planilla', 'ajuste'));

        // Opcional: configurar tamaño carta y orientación horizontal ('landscape') si la tabla es muy ancha,
        // o 'portrait' (vertical) si prefieres.
        $pdf->setPaper('letter', 'portrait');

        // Muestra el PDF directamente en el navegador (puedes cambiar 'stream' por 'download' si prefieres que se descargue)
        return $pdf->stream('planilla-' . $planilla->mes . '-' . $planilla->anio . '.pdf');
    }
    public function marcarComoPagado(Planilla $planilla)
    {
        $planilla->update([
            'estado' => 'Pagado'
        ]);

        return redirect()->route('admin.planillas.index')
            ->with('success', 'La planilla del mes de ' . $planilla->mes . ' de ' . $planilla->anio . ' ha sido marcada como Pagada exitosamente.');
    }
}
