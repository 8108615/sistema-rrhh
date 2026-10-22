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
     * Listado general de viajes y viáticos con buscador y filtros de fecha.
     */
    public function index(Request $request)
    {
        $query = Viaje::with('empleados');

        // Búsqueda por destino, origen, estadio o motivo
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('destino', 'like', "%{$search}%")
                ->orWhere('origen', 'like', "%{$search}%")
                ->orWhere('estadio', 'like', "%{$search}%")
                ->orWhere('motivo', 'like', "%{$search}%");
            });
        }

        // Filtro por Fecha de Salida (Inicio)
        if ($request->filled('fecha_inicio')) {
            $query->whereDate('fecha_inicio', '>=', $request->fecha_inicio);
        }

        // Filtro por Fecha de Retorno (Fin)
        if ($request->filled('fecha_fin')) {
            $query->whereDate('fecha_fin', '<=', $request->fecha_fin);
        }

        // Cantidad de registros por página (por defecto 10, soporta 5, 10, 20, 50)
        $perPage = $request->input('perPage', 10);

        $viajes = $query->orderBy('created_at', 'desc')->paginate($perPage)->withQueryString();

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

        $inicio = Carbon::parse($request->fecha_inicio);
        $fin    = Carbon::parse($request->fecha_fin);
        $diasTotales = $inicio->diffInDays($fin) + 1;

        $montoDiario      = $request->monto_diario;
        $totalPorPersona  = $diasTotales * $montoDiario;
        $cantidadPersonas = count($request->empleados);
        $montoTotalViaje  = $totalPorPersona * $cantidadPersonas;

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
     * Muestra el formulario para editar un viaje.
     */
    public function edit($id)
    {
        $viaje = Viaje::with('empleados')->findOrFail($id);
        $empleados = Empleado::orderBy('nombre', 'asc')->get();
        return view('admin.viaticos.edit', compact('viaje', 'empleados'));
    }

    /**
     * Actualiza el registro del viaje y los viáticos.
     */
    public function update(Request $request, $id)
    {
        $viaje = Viaje::findOrFail($id);

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

        $inicio = Carbon::parse($request->fecha_inicio);
        $fin    = Carbon::parse($request->fecha_fin);
        $diasTotales = $inicio->diffInDays($fin) + 1;

        $montoDiario      = $request->monto_diario;
        $totalPorPersona  = $diasTotales * $montoDiario;
        $cantidadPersonas = count($request->empleados);
        $montoTotalViaje  = $totalPorPersona * $cantidadPersonas;

        $viaje->update([
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
        ]);

        $syncData = [];
        foreach ($request->empleados as $empleadoId) {
            $syncData[$empleadoId] = ['monto_viatico' => $totalPorPersona];
        }
        $viaje->empleados()->sync($syncData);

        return redirect()->route('admin.viaticos.index')
            ->with('success', 'El registro de viaje y viáticos fue actualizado exitosamente.');
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

    public function actualizarEstado(Request $request, $id)
    {
        $viaje = Viaje::findOrFail($id);

        $request->validate([
            'estado' => 'required|in:PENDIENTE,PAGADO,ANULADO',
        ]);

        $viaje->update([
            'estado' => $request->estado,
        ]);

        return redirect()->back()->with('success', 'El estado del viaje #' . $viaje->id . ' fue actualizado a ' . $request->estado . '.');
    }

    public function pdf($id)
    {
        $viaje = Viaje::with('empleados')->findOrFail($id);

        // Verificación de seguridad: solo se puede emitir boleta si está PAGADO
        if ($viaje->estado !== 'PAGADO') {
            return redirect()->route('admin.viaticos.index')
                ->with('error', 'Solo se puede generar el comprobante PDF de viajes en estado PAGADO.');
        }

        return view('admin.viaticos.pdf', compact('viaje'));
    }
}
