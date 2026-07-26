<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Departamento;
use Illuminate\Http\Request;

class DepartamentoController extends Controller
{
    public function index()
    {
        $departamentos = Departamento::latest()->paginate(10);
        return view('admin.departamentos.index', compact('departamentos'));
    }

   

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:departamentos,nombre',
            'sigla' => 'nullable|string|max:10',
        ]);

        Departamento::create([
            'nombre' => $request->nombre,
            'sigla' => $request->sigla,
            'estado' => $request->has('estado'),
        ]);

        return redirect()->route('admin.departamentos.index')
            ->with('mensaje', 'Departamento creado correctamente.')
            ->with('icono', 'success');
    }

    public function update(Request $request, Departamento $departamento)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:departamentos,nombre,' . $departamento->id,
            'sigla' => 'nullable|string|max:10',
        ]);

        $departamento->update([
            'nombre' => $request->nombre,
            'sigla' => $request->sigla,
            'estado' => $request->has('estado'),
        ]);

        return redirect()->route('admin.departamentos.index')->with('mensaje', 'Departamento actualizado correctamente.');
    }

    public function destroy(Departamento $departamento)
    {
        $departamento->delete();
        return redirect()->route('admin.departamentos.index')->with('mensaje', 'Departamento eliminado correctamente.');
    }
}