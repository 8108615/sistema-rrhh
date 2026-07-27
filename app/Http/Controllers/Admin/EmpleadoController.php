<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Empleado;
use App\Models\Departamento;
use App\Models\Area;
use Illuminate\Http\Request;

class EmpleadoController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->get('buscar');

        $empleados = Empleado::with(['departamento', 'area'])
            ->when($buscar, function ($query, $buscar) {
                return $query->where('nombre', 'like', "%{$buscar}%")
                           ->orWhere('apellido', 'like', "%{$buscar}%")
                           ->orWhere('ci', 'like', "%{$buscar}%")
                           ->orWhereHas('area', function ($q) use ($buscar) {
                               $q->where('nombre', 'like', "%{$buscar}%");
                           });
            })
            ->latest()
            ->paginate(10);

        return view('admin.empleados.index', compact('empleados', 'buscar'));
    }

    public function create()
    {
        $departamentos = Departamento::where('estado', 1)->get();
        $areas = Area::where('estado', 1)->get();

        return view('admin.empleados.create', compact('departamentos', 'areas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'departamento_id' => 'required|exists:departamentos,id',
            'area_id' => 'required|exists:areas,id',
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'ci' => 'required|string|max:255|unique:empleados,ci',
            'fecha_nacimiento' => 'nullable|date',
            'fecha_ingreso' => 'nullable|date',
            'genero' => 'nullable|in:MASCULINO,FEMENINO',
            'telefono' => 'nullable|string|max:255',
            'direccion' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'nro_cuenta' => 'nullable|string|max:255',
            'banco' => 'nullable|string|max:255',
            'celular_referencia' => 'nullable|string|max:255',
            'parentesco_referencia' => 'nullable|string|max:255',
            'salario' => 'required|numeric|min:0',
            'estado' => 'required|in:0,1',
        ]);

        Empleado::create([
            'departamento_id' => $request->departamento_id,
            'area_id' => $request->area_id,
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'ci' => $request->ci,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'fecha_ingreso' => $request->fecha_ingreso,
            'genero' => $request->genero,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'email' => $request->email,
            'nro_cuenta' => $request->nro_cuenta,
            'banco' => $request->banco,
            'celular_referencia' => $request->celular_referencia,
            'parentesco_referencia' => $request->parentesco_referencia,
            'salario' => $request->salario,
            'estado' => $request->estado,
        ]);

        return redirect()->route('admin.empleados.index')->with('success', 'Empleado registrado correctamente.');
    }
    public function show($id)
    {
        $empleado = Empleado::with(['departamento', 'area'])->findOrFail($id);

        return view('admin.empleados.show', compact('empleado'));
    }

    public function edit(Empleado $empleado)
    {
        $departamentos = Departamento::where('estado', 1)->get();
        $areas = Area::where('estado', 1)->get();

        return view('admin.empleados.edit', compact('empleado', 'departamentos', 'areas'));
    }

    public function update(Request $request, Empleado $empleado)
    {
        $request->validate([
            'departamento_id' => 'required|exists:departamentos,id',
            'area_id' => 'required|exists:areas,id',
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'ci' => 'required|string|max:255|unique:empleados,ci,' . $empleado->id,
            'fecha_nacimiento' => 'nullable|date',
            'fecha_ingreso' => 'nullable|date',
            'genero' => 'nullable|in:MASCULINO,FEMENINO',
            'telefono' => 'nullable|string|max:255',
            'direccion' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'nro_cuenta' => 'nullable|string|max:255',
            'banco' => 'nullable|string|max:255',
            'celular_referencia' => 'nullable|string|max:255',
            'parentesco_referencia' => 'nullable|string|max:255',
            'salario' => 'required|numeric|min:0',
            'estado' => 'required|in:0,1',
        ]);

        $empleado->update([
            'departamento_id' => $request->departamento_id,
            'area_id' => $request->area_id,
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'ci' => $request->ci,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'fecha_ingreso' => $request->fecha_ingreso,
            'genero' => $request->genero,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'email' => $request->email,
            'nro_cuenta' => $request->nro_cuenta,
            'banco' => $request->banco,
            'celular_referencia' => $request->celular_referencia,
            'parentesco_referencia' => $request->parentesco_referencia,
            'salario' => $request->salario,
            'estado' => $request->estado,
        ]);

        return redirect()->route('admin.empleados.index')->with('success', 'Empleado actualizado correctamente.');
    }

    public function destroy(Empleado $empleado)
    {
        $empleado->delete();

        return redirect()->route('admin.empleados.index')->with('success', 'Empleado eliminado correctamente.');
    }
}
