<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contrato;
use App\Models\Empleado;
use Illuminate\Http\Request;
use App\Models\Area;
use App\Models\Ajuste;
use Illuminate\Support\Facades\Storage;

class ContratoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $estado = $request->get('estado');

        $contratos = Contrato::with('empleado')
            ->when($search, function ($query, $search) {
                return $query->whereHas('empleado', function ($q) use ($search) {
                    $q->where('nombre', 'like', "%{$search}%")
                      ->orWhere('apellido', 'like', "%{$search}%")
                      ->orWhere('ci', 'like', "%{$search}%");
                })->orWhere('cargo_contrato', 'like', "%{$search}%");
            })
            ->when($estado, function ($query, $estado) {
                return $query->where('estado', $estado);
            })
            ->latest()
            ->paginate(10);

        return view('admin.contratos.index', compact('contratos', 'search', 'estado'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $empleados = Empleado::all();
        $areas = Area::where('estado', true)->get(); // Opcional: solo áreas activas
        return view('admin.contratos.create', compact('empleados', 'areas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validaciones...
        $validated = $request->validate([
            'empleado_id' => 'required|exists:empleados,id',
            'tipo_contrato' => 'required|string',
            'cargo_contrato' => 'required|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date',
            'salario_mensual' => 'required|numeric',
            'estado' => 'required|string',
            'archivo_pdf' => 'nullable|mimes:pdf|max:5120',
        ]);

        // Guardar archivo PDF si se adjunta...
        if ($request->hasFile('archivo_pdf')) {
            $validated['archivo_pdf'] = $request->file('archivo_pdf')->store('contratos', 'public');
        }

        $contrato = Contrato::create($validated);

        // Redirigir directamente a la vista de impresión del contrato generado
        return redirect()->route('admin.contratos.index', $contrato->id)
                        ->with('mensaje', 'Contrato registrado con éxito. Ya puedes imprimirlo.')
                        ->with('icono', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $contrato = Contrato::with('empleado')->findOrFail($id);
        return view('admin.contratos.show', compact('contrato'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $contrato = Contrato::findOrFail($id);
        $empleados = Empleado::all();
        $areas = Area::where('estado', true)->get();
        return view('admin.contratos.edit', compact('contrato', 'empleados', 'areas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $contrato = Contrato::findOrFail($id);

        $request->validate([
            'empleado_id' => 'required|exists:empleados,id',
            'tipo_contrato' => 'required|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'salario_mensual' => 'required|numeric|min:0',
            'cargo_contrato' => 'required|string|max:255',
            'archivo_pdf' => 'nullable|mimes:pdf|max:5120',
            'estado' => 'required|string',
            'observaciones' => 'nullable|string',
        ]);

        $data = $request->all();

        // Si se sube un nuevo PDF, reemplazamos el anterior
        if ($request->hasFile('archivo_pdf')) {
            if ($contrato->archivo_pdf && Storage::disk('public')->exists($contrato->archivo_pdf)) {
                Storage::disk('public')->delete($contrato->archivo_pdf);
            }

            $file = $request->file('archivo_pdf');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('contratos', $filename, 'public');
            $data['archivo_pdf'] = $path;
        }

        $contrato->update($data);

        return redirect()->route('admin.contratos.index')
                ->with('mensaje', 'Contrato actualizado exitosamente.')
                ->with('icono', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $contrato = Contrato::findOrFail($id);

        // Eliminar archivo PDF asociado si existe
        if ($contrato->archivo_pdf && Storage::disk('public')->exists($contrato->archivo_pdf)) {
            Storage::disk('public')->delete($contrato->archivo_pdf);
        }

        $contrato->delete();

        return redirect()->route('admin.contratos.index')
                ->with('mensaje', 'Contrato eliminado correctamente.')
                ->with('icono', 'success');
    }

    /**
     * Descargar o ver el archivo PDF del contrato.
     */
    public function downloadPdf($id)
    {
        $contrato = Contrato::findOrFail($id);

        if (!$contrato->archivo_pdf || !Storage::disk('public')->exists($contrato->archivo_pdf)) {
            return back()->with('error', 'El archivo PDF no está disponible.');
        }

        return Storage::disk('public')->download($contrato->archivo_pdf);
    }

    public function imprimir($id)
    {
        $contrato = Contrato::with(['empleado'])->findOrFail($id);
        $ajuste = Ajuste::first(); 
        
        return view('admin.contratos.documento-impresion', compact('contrato', 'ajuste'));
    }
}
