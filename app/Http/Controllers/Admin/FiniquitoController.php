<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ajuste;
use App\Models\Finiquito;
use App\Models\Empleado;
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
            'observaciones' => 'nullable|string',
        ]);

        // Buscamos al empleado para asegurar un respaldo directo de su campo 'salario'
        $empleado = Empleado::findOrFail($request->empleado_id);

        // Si el formulario no envió los sueldos, usamos por defecto el salario del empleado
        $ultimo = $request->filled('ultimo_salario') ? $request->ultimo_salario : $empleado->salario;
        $promedio = $request->filled('promedio_tres_salarios') ? $request->promedio_tres_salarios : $empleado->salario;

        $ingreso = Carbon::parse($request->fecha_ingreso);
        $retiro = Carbon::parse($request->fecha_retiro);

        // 1. Cálculo del tiempo de servicios (Años, Meses, Días)
        $diff = $ingreso->diff($retiro);
        $anios = $diff->y;
        $meses = $diff->m;
        $dias = $diff->d;

        // Años en formato decimal para la indemnización (1 sueldo por año, 1/12 por mes, 1/360 por día)
        $anosServicioDecimal = $anios + ($meses / 12) + ($dias / 360);

        // 2. Indemnización (Sobre el promedio indemnizable de los últimos 3 meses)
        $montoIndemnizacion = $promedio * $anosServicioDecimal;

        // 3. Desahucio (3 meses de sueldo solo si es Despido Injustificado)
        $montoDesahucio = ($request->causal_retiro === 'Despido Injustificado') ? ($ultimo * 3) : 0;

        // 4. Aguinaldo proporcional (Meses y días trabajados en el año actual hasta la fecha de retiro)
        $inicioAnio = Carbon::create($retiro->year, 1, 1);
        $fechaCalculoAguinaldo = $ingreso->gt($inicioAnio) ? $ingreso : $inicioAnio;

        $mesesAguinaldo = $fechaCalculoAguinaldo->diffInMonths($retiro);
        $diasAguinaldo = $fechaCalculoAguinaldo->copy()->addMonths($mesesAguinaldo)->diffInDays($retiro);

        $montoAguinaldo = ($ultimo / 12) * $mesesAguinaldo + (($ultimo / 12 / 30) * $diasAguinaldo);

        // 5. Vacación proporcional / pendiente (Escala Bolivia: 1-5 años = 15 días, 5-10 años = 20 días, +10 años = 30 días)
        $diasVacacionAnual = 15;
        if ($anios >= 10) {
            $diasVacacionAnual = 30;
        } elseif ($anios >= 5) {
            $diasVacacionAnual = 20;
        }

        // Cálculo de vacación correspondiente al período o proporción acumulada
        $montoVacacion = ($ultimo / 30) * (($diasVacacionAnual / 12) * $meses + (($diasVacacionAnual / 12 / 30) * $dias));

        // Total general de beneficios sociales
        $totalBeneficios = $montoIndemnizacion + $montoDesahucio + $montoAguinaldo + $montoVacacion;

        // Guardar en la base de datos
        Finiquito::create([
            'empleado_id' => $request->empleado_id,
            'fecha_ingreso' => $request->fecha_ingreso,
            'fecha_retiro' => $request->fecha_retiro,
            'causal_retiro' => $request->causal_retiro,
            'ultimo_salario' => $ultimo,
            'promedio_tres_salarios' => $promedio,
            'anos_servicio' => round($anosServicioDecimal, 2),
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
