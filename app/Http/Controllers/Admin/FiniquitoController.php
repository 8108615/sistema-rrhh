<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ajuste;
use App\Models\Finiquito;
use App\Models\Empleado;
use App\Models\Permiso;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FiniquitoController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->get('buscar');

        $finiquitos = Finiquito::with('empleado')
            ->when($buscar, function ($query, $buscar) {
                return $query->whereHas('empleado', function ($q) use ($buscar) {
                    $q->where('nombre', 'like', "%{$buscar}%")
                      ->orWhere('apellido', 'like', "%{$buscar}%")
                      ->orWhere('ci', 'like', "%{$buscar}%");
                })->orWhere('causal_retiro', 'like', "%{$buscar}%");
            })
            ->latest()
            ->paginate(10);

        return view('admin.finiquitos.index', compact('finiquitos', 'buscar'));
    }

    public function create()
    {
        $empleados = Empleado::all();
        
        $anioActual = Carbon::now()->year;
        
        foreach ($empleados as $empleado) {
            $fechaIngreso = Carbon::parse($empleado->fecha_ingreso);
            $aniosAntiguedad = $fechaIngreso->diffInYears(Carbon::now());
            
            $diasVacacionAnual = 15;
            if ($aniosAntiguedad >= 10) {
                $diasVacacionAnual = 30;
            } elseif ($aniosAntiguedad >= 5) {
                $diasVacacionAnual = 20;
            }
            
            $diasTomados = Permiso::where('empleado_id', $empleado->id)
                ->where('estado', 'Aprobado')
                ->where('tipo', 'Vacaciones')
                ->whereYear('fecha_inicio', $anioActual)
                ->sum('dias_solicitados');
            
            $hoy = Carbon::now();
            $mesesTrabajadosAnio = ($fechaIngreso->year == $anioActual) 
                ? $fechaIngreso->diffInMonths($hoy) 
                : $fechaIngreso->copy()->startOfYear()->diffInMonths($hoy);
            
            $diasAcumuladosProporcionales = ($diasVacacionAnual / 12) * $mesesTrabajadosAnio;

            $empleado->vacaciones_disponibles = max(0, round($diasAcumuladosProporcionales - $diasTomados, 2));
        }

        return view('admin.finiquitos.create', compact('empleados'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'empleado_id' => 'required|exists:empleados,id',
            'fecha_ingreso' => 'required|date',
            'fecha_retiro' => 'required|date|after_or_equal:fecha_ingreso',
            'causal_retiro' => 'required|string',
            'ultimo_salario' => 'nullable|numeric|min:0',
            'promedio_tres_salarios' => 'nullable|numeric|min:0',
            'anos_servicio' => 'nullable|numeric|min:0',
            'monto_indemnizacion' => 'nullable|numeric|min:0',
            'monto_desahucio' => 'nullable|numeric|min:0',
            'monto_aguinaldo' => 'nullable|numeric|min:0',
            'monto_vacacion' => 'nullable|numeric|min:0',
            'observaciones' => 'nullable|string',
        ]);

        $empleado = Empleado::findOrFail($request->empleado_id);
        $ultimo = $request->filled('ultimo_salario') ? $request->ultimo_salario : $empleado->salario;
        $promedio = $request->filled('promedio_tres_salarios') ? $request->promedio_tres_salarios : $empleado->salario;

        // Tomamos los valores exactos enviados desde la interfaz (calculados por JS)
        $anosServicioDecimal = $request->filled('anos_servicio') ? $request->anos_servicio : 0;
        $montoIndemnizacion = $request->filled('monto_indemnizacion') ? $request->monto_indemnizacion : 0;
        $montoDesahucio = $request->filled('monto_desahucio') ? $request->monto_desahucio : 0;
        $montoAguinaldo = $request->filled('monto_aguinaldo') ? $request->monto_aguinaldo : 0;
        $montoVacacion = $request->filled('monto_vacacion') ? $request->monto_vacacion : 0;

        // Sumatoria total exacta de la vista
        $totalBeneficios = $montoIndemnizacion + $montoDesahucio + $montoAguinaldo + $montoVacacion;

        // Guardar en base de datos con los valores sincronizados
        Finiquito::create([
            'empleado_id' => $request->empleado_id,
            'fecha_ingreso' => $request->fecha_ingreso,
            'fecha_retiro' => $request->fecha_retiro,
            'causal_retiro' => $request->causal_retiro,
            'ultimo_salario' => $ultimo,
            'promedio_tres_salarios' => $promedio,
            'anos_servicio' => round($anosServicioDecimal, 4),
            'monto_indemnizacion' => round($montoIndemnizacion, 2),
            'monto_desahucio' => round($montoDesahucio, 2),
            'monto_vacacion' => round($montoVacacion, 2),
            'monto_aguinaldo' => round($montoAguinaldo, 2),
            'total_beneficios' => round($totalBeneficios, 2),
            'observaciones' => $request->observaciones,
        ]);

        return redirect()->route('admin.finiquitos.index')->with([
            'mensaje' => 'Cálculo de finiquito registrado con éxito.',
            'icono' => 'success'
        ]);
    }

    public function show($id)
    {
        $finiquito = Finiquito::with('empleado.departamento', 'empleado.area')->findOrFail($id);
        return view('admin.finiquitos.show', compact('finiquito'));
    }

    public function destroy($id)
    {
        $finiquito = Finiquito::findOrFail($id);
        $finiquito->delete();

        return redirect()->route('admin.finiquitos.index')->with([
            'mensaje' => 'Registro de finiquito eliminado correctamente.',
            'icono' => 'success'
        ]);
    }

    public function print($id)
    {
        $finiquito = Finiquito::with(['empleado.area'])->findOrFail($id);
        $ajuste = Ajuste::first();
        $simboloMoneda = $ajuste->divisa ?? 'Bs.';

        return view('admin.finiquitos.print', compact('finiquito', 'ajuste', 'simboloMoneda'));
    }
}