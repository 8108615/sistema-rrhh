<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Viaje;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ViaticoController extends Controller
{
    /**
     * Listado general de viajes y viáticos.
     */
    public function index()
    {
        $viajes = Viaje::with('empleados')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.viaticos.index', compact('viajes'));
    }

    /**
     * Formulario para registrar un nuevo viaje y calcular viáticos.
     */
    public function create()
    {
        $empleados = Empleado::orderBy('nombre', 'asc')->get();
        return view('admin.viaticos.create', compact('empleados'));
    }

    /**
     * Almacena el nuevo viaje en la BD y asigna los viáticos a los empleados.
     */
    public function store(Request $request)
    {
        $request->validate([
            'origen'         => 'required|string|max:255',
            'destino'        => 'required|string|max:255',
            'estadio'        => 'nullable|string|max:255',
            'motivo'         => 'nullable|string',
            'fecha_inicio'   => 'required|date',
            'fecha_fin'      => 'required|date|after_or_equal:fecha_inicio',
            'monto_diario'   => 'required|numeric|min:0',
            'empleados'      => 'required|array|min:1',
            'empleados.*'    => 'exists:empleados,id',
        ]);

        // Cálculo automático de días (diferencia inclusiva de días)
        $inicio = Carbon::parse($request->fecha_inicio);
        $fin    = Carbon::parse($request->fecha_fin);
        $diasTotales = $inicio->diffInDays($fin) + 1; // Incluye el día de salida y retorno

        $montoDiario      = $request->monto_diario;
        $totalPorPersona = $diasTotales * $montoDiario;
        $cantidadPersonas = count($request->empleados);
        $montoTotalViaje = $totalPorPersona * $cantidadPersonas;

        // Crear el registro principal del viaje
        $viaje = Viaje::create([
            'origen'            => $request->origen,
            'destino'           => $request->destino,
            'estadio'           => $request->estadio,
            'motivo'            => $request->motivo,
            'fecha_inicio'      => $request->fecha_inicio,
            'fecha_fin'         => $request->fecha_fin,
            'dias_totales'      => $diasTotales,
            'monto_diario'      => $montoDiario,
            'total_por_persona' => $totalPorPersona,
            'monto_total_viaje' => $montoTotalViaje,
            'estado'            => 'PENDIENTE',
        ]);

        // Asociar empleados en la tabla pivote con su viático correspondiente
        foreach ($request->empleados as $empleadoId) {
            $viaje->empleados()->attach($empleadoId, [
                'monto_viatico' => $totalPorPersona,
            ]);
        }

        return redirect()->route('admin.viaticos.index')
            ->with('success', 'Registro de viaje y cálculo de viáticos creado correctamente.');
    }

    /**
     * Muestra el detalle completo de un viaje.
     */
    public function show($id)
    {
        $viaje = Viaje::with('empleados')->findOrFail($id);
        return view('admin.viaticos.show', compact('viaje'));
    }

    /**
     * Elimina un registro de viaje.
     */
    public function destroy($id)
    {
        $viaje = Viaje::findOrFail($id);
        $viaje->delete();

        return redirect()->route('admin.viaticos.index')
            ->with('success', 'El viaje fue eliminado exitosamente.');
    }
}
