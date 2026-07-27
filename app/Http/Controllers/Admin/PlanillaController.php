<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Planilla;
use App\Models\DetallePlanilla;
use App\Models\Empleado;
use App\Models\Ajuste;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        return view('admin.planillas.index', compact('planillas', 'buscar'));
    }

    // Vista para crear/generar una nueva planilla
    public function create()
    {
        return view('admin.planillas.create');
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
        $planilla = Planilla::with('detalles.empleado')->findOrFail($id);
        $ajuste = Ajuste::first();

        // tu lógica de PDF...
        return view('admin.planillas.pdf', compact('planilla', 'ajuste'));
    }
}
