<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permiso;
use App\Models\Empleado;

class PermisoController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->get('buscar');

        $permisos = Permiso::with(['empleado.area', 'empleado'])
            ->when($buscar, function ($query, $buscar) {
                return $query->where('tipo', 'like', "%{$buscar}%")
                             ->orWhere('estado', 'like', "%{$buscar}%")
                             ->orWhereHas('empleado', function ($q) use ($buscar) {
                                 $q->where('nombre', 'like', "%{$buscar}%")
                                   ->orWhere('apellido', 'like', "%{$buscar}%")
                                   ->orWhere('ci', 'like', "%{$buscar}%");
                             });
            })
            ->oldest() // Orden ascendente como lo preferiste en tus otros módulos
            ->paginate(10);

        return view('admin.permisos.index', compact('permisos', 'buscar'));
    }

    public function create()
    {
        $empleados = Empleado::all(); // Para seleccionar a qué empleado corresponde el permiso
        return view('admin.permisos.create', compact('empleados'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'empleado_id' => 'required|exists:empleados,id',
            'tipo' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'fecha_retorno' => 'nullable|date|after_or_equal:fecha_fin',
            'dias_solicitados' => 'required|numeric|min:0.5',
            'motivo' => 'nullable|string',
            'estado' => 'required|in:Pendiente,Aprobado,Rechazado',
        ]);

        Permiso::create($request->all());

        return redirect()->route('admin.permisos.index')->with([
            'mensaje' => 'Solicitud de permiso registrada con éxito.',
            'icono' => 'success'
        ]);
    }

    public function edit($id)
    {
        $permiso = Permiso::findOrFail($id);
        $empleados = Empleado::all();
        return view('admin.permisos.edit', compact('permiso', 'empleados'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'empleado_id' => 'required|exists:empleados,id',
            'tipo' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'fecha_retorno' => 'nullable|date|after_or_equal:fecha_fin',
            'dias_solicitados' => 'required|numeric|min:0.5',
            'motivo' => 'nullable|string',
            'estado' => 'required|in:Pendiente,Aprobado,Rechazado',
        ]);

        $permiso = Permiso::findOrFail($id);
        $permiso->update($request->all());

        return redirect()->route('admin.permisos.index')->with([
            'mensaje' => 'Solicitud de permiso actualizada con éxito.',
            'icono' => 'success'
        ]);
    }

    public function destroy($id)
    {
        $permiso = Permiso::findOrFail($id);
        $permiso->delete();

        return redirect()->route('admin.permisos.index')->with([
            'mensaje' => 'Solicitud de permiso eliminada con éxito.',
            'icono' => 'success'
        ]);
    }

    // Método opcional para cambiar estado directamente si lo requieres luego en un botón rápido
    public function cambiarEstado(Request $request, $id)
    {
        $permiso = Permiso::findOrFail($id);
        $permiso->update(['estado' => $request->estado]);

        return redirect()->back()->with('success', 'Estado del permiso actualizado.');
    }
}
