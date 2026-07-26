<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');
        $roles = Role::query();

        $roles = Role::where('name', 'like', '%' . $buscar . '%')
                  ->paginate(10)
                  ->withQueryString();

        return view('admin.roles.index', compact('roles', 'buscar'));
    }

    public function create()
    {
        return view('admin.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
        ]);

        $rol = new Role();
        $rol->name = $request->name;
        $rol->save();

        return redirect()->route('admin.roles.index')
            ->with('mensaje', 'Rol guardado correctamente')
            ->with('icono', 'success');
    }

    public function show(string $id)
    {
        $rol = Role::find($id);
        return view('admin.roles.show', compact('rol'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $rol = Role::find($id);
        return view('admin.roles.edit', compact('rol'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
        ]);

        $rol = Role::find($id);
        $rol->name = $request->name;
        $rol->save();

        return redirect()->route('admin.roles.index')
            ->with('mensaje', 'Rol actualizado correctamente')
            ->with('icono', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $rol = Role::find($id);
        $rol->delete();

        return redirect()->route('admin.roles.index')
            ->with('mensaje', 'Rol eliminado correctamente')
            ->with('icono', 'success');
    }
}
