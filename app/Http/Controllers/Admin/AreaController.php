<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->get('buscar');

        $areas = Area::when($buscar, function ($query, $buscar) {
                return $query->where('nombre', 'like', "%{$buscar}%");
            })
            ->oldest()
            ->paginate(10);

        return view('admin.areas.index', compact('areas', 'buscar'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:areas,nombre',
        ]);

        Area::create([
            'nombre' => $request->nombre,
            'estado' => $request->has('estado') ? 1 : 0,
        ]);

        return redirect()->route('admin.areas.index')->with('success', 'Área creada correctamente.');
    }

    public function update(Request $request, Area $area)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:areas,nombre,' . $area->id,
        ]);

        $area->update([
            'nombre' => $request->nombre,
            'estado' => $request->has('estado') ? 1 : 0,
        ]);

        return redirect()->route('admin.areas.index')->with('success', 'Área actualizada correctamente.');
    }

    public function destroy(Area $area)
    {
        $area->delete();

        return redirect()->route('admin.areas.index')->with('success', 'Área eliminada correctamente.');
    }
}
