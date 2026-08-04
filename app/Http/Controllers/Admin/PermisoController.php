<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permiso;
use App\Models\Empleado;
use Carbon\Carbon;

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
            ->oldest()
            ->paginate(10);

        return view('admin.permisos.index', compact('permisos', 'buscar'));
    }

    public function create()
    {
        $empleados = Empleado::all(); 
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

        if ($request->tipo === 'Vacaciones') {
            $empleado = Empleado::findOrFail($request->empleado_id);
            $disponibles = $this->calcularDiasDisponibles($empleado);
            
            if ($request->dias_solicitados > $disponibles) {
                return back()->withInput()->withErrors([
                    'dias_solicitados' => "El empleado solo cuenta con {$disponibles} días de vacaciones disponibles."
                ]);
            }
        }

        Permiso::create($request->all());

        return redirect()->route('admin.permisos.index')->with([
            'mensaje' => 'Solicitud de permiso registrada con éxito.',
            'icono' => 'success'
        ]);
    }

    private function calcularDiasDisponibles(Empleado $empleado)
    {
        if (!$empleado->fecha_ingreso) {
            return 15; 
        }

        $ingreso = Carbon::parse($empleado->fecha_ingreso);
        $hoy = Carbon::now();

        $anosServicio = $ingreso->diffInYears($hoy);

        if ($anosServicio < 1) {
            return 0; 
        }

        $diasAnuales = 15;
        if ($anosServicio >= 5 && $anosServicio < 10) {
            $diasAnuales = 20;
        } elseif ($anosServicio >= 10) {
            $diasAnuales = 30;
        }

        $totalGanado = $diasAnuales * $anosServicio;

        $diasUsados = Permiso::where('empleado_id', $empleado->id)
            ->where('tipo', 'Vacaciones')
            ->where('estado', 'Aprobado')
            ->sum('dias_solicitados');

        $disponibles = $totalGanado - $diasUsados;

        // Redondeado a entero exacto
        return max(0, round($disponibles));
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

    // --- NUEVO MÉTODO API PARA CONSULTAR VACACIONES VIA AJAX ---
    public function getVacacionesEmpleado($id)
    {
        $empleado = Empleado::findOrFail($id);
        $diasDisponibles = $this->calcularDiasDisponibles($empleado);

        return response()->json([
            'dias_disponibles' => $diasDisponibles,
            'fecha_ingreso' => $empleado->fecha_ingreso
        ]);
    }

    
}