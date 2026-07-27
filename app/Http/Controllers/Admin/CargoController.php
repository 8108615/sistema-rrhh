<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cargo;
use App\Models\Area;
use Illuminate\Http\Request;

class CargoController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->get('buscar');

        $cargos = Cargo::with('area')
            ->when($buscar, function ($query, $buscar) {
                return $query->where('nombre', 'like', "%{$buscar}%")
                             ->orWhereHas('area', function ($q) use ($buscar) {
                                 $q->where('nombre', 'like', "%{$buscar}%");
                             });
            })
            ->latest()
            ->paginate(10);

        $areas = Area::where('estado', 1)->get(); // Para los selects de los modales

        return view('admin.cargos.index', compact('cargos', 'areas', 'buscar'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'area_id' => 'required|exists:areas,id',
            'nombre' => 'required|string|max:255',
        ]);

        Cargo::create([
            'area_id' => $request->area_id,
            'nombre' => $request->nombre,
            'estado' => $request->has('estado') ? 1 : 0,
        ]);

        return redirect()->route('admin.cargos.index')->with('success', 'Cargo creado correctamente.');
    }

    public function update(Request $request, Cargo $cargo)
    {
        $request->validate([
            'area_id' => 'required|exists:areas,id',
            'nombre' => 'required|string|max:255',
        ]);

        $cargo->update([
            'area_id' => $request->area_id,
            'nombre' => $request->nombre,
            'estado' => $request->has('estado') ? 1 : 0,
        ]);

        return redirect()->route('admin.cargos.index')->with('success', 'Cargo actualizado correctamente.');
    }

    public function destroy(Cargo $cargo)
    {
        $cargo->delete();

        return redirect()->route('admin.cargos.index')->with('success', 'Cargo eliminado correctamente.');
    }

}
