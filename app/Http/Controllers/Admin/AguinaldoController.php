<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Aguinaldo;
use App\Models\Empleado;
use App\Models\Ajuste;
use Carbon\Carbon;

class AguinaldoController extends Controller
{
    public function index(Request $request)
    {
        $gestion = $request->get('gestion', date('Y'));
        $tipo = $request->get('tipo', 'Aguinaldo');

        $aguinaldos = Aguinaldo::with('empleado.area')
            ->where('gestion', $gestion)
            ->where('tipo', $tipo)
            ->get();

        return view('admin.aguinaldos.index', compact('aguinaldos', 'gestion', 'tipo'));
    }

    public function create()
    {
        $empleados = Empleado::where('estado', true)->get();

        // Obtenemos los ajustes para sacar el símbolo de moneda/divisa
        $ajuste = Ajuste::first();
        $simboloMoneda = $ajuste->divisa ?? 'Bs.';

        return view('admin.aguinaldos.create', compact('empleados', 'simboloMoneda'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'empleado_id' => 'required|exists:empleados,id',
            'gestion' => 'required|digits:4|integer',
            'tipo' => 'required|in:Aguinaldo,Doble Aguinaldo',
            'meses_trabajados' => 'required|integer|min:1|max:12',
            'dias_trabajados' => 'required|integer|min:0|max:360',
            'monto_pagar' => 'required|numeric|min:0',
            'estado' => 'required|in:Pendiente,Pagado',
        ]);

        $empleado = Empleado::findOrFail($request->empleado_id);
        $ultimoSalario = $empleado->salario ?? 0;

        Aguinaldo::create([
            'empleado_id' => $request->empleado_id,
            'gestion' => $request->gestion,
            'tipo' => $request->tipo,
            'ultimo_salario' => $ultimoSalario,
            'promedio_tres_meses' => $ultimoSalario,
            'base_calculo' => $ultimoSalario,
            'meses_trabajados' => $request->meses_trabajados,
            'dias_trabajados' => $request->dias_trabajados,
            'monto_pagar' => $request->monto_pagar,
            'estado' => $request->estado,
            'fecha_pago' => $request->fecha_pago,
            'observaciones' => $request->observaciones,
        ]);

        return redirect()->route('admin.aguinaldos.index', ['gestion' => $request->gestion, 'tipo' => $request->tipo])
                         ->with('mensaje', 'Registro creado correctamente.')
                         ->with('icono', 'success');
    }

    public function calcularMasivo(Request $request)
    {
        $gestion = $request->get('gestion', date('Y'));
        $tipo = $request->get('tipo', 'Aguinaldo');

        $empleados = Empleado::where('estado', true)->get();

        foreach ($empleados as $empleado) {
            $ultimoSalario = $empleado->salario ?? ($empleado->sueldo_base ?? 0);
            if ($ultimoSalario <= 0) continue;

            $fechaIngreso = Carbon::parse($empleado->fecha_ingreso ?? "$gestion-01-01");
            $inicioGestion = Carbon::create($gestion, 1, 1);
            $finGestion = Carbon::create($gestion, 12, 31);

            $fechaInicioCalculo = $fechaIngreso->gt($inicioGestion) ? $fechaIngreso : $inicioGestion;
            $fechaFinCalculo = $finGestion;

            if ($fechaInicioCalculo->lte($fechaFinCalculo)) {
                $meses = $fechaInicioCalculo->diffInMonths($fechaFinCalculo);
                $dias = $fechaInicioCalculo->copy()->addMonths($meses)->diffInDays($fechaFinCalculo);
            } else {
                $meses = 0;
                $dias = 0;
            }

            $diasTotalesAnio = ($meses * 30) + min($dias, 30);
            if ($diasTotalesAnio > 360) $diasTotalesAnio = 360;

            // Cálculo exclusivo de aguinaldo (proporcional a los días trabajados en el año / 360)
            $montoPagar = ($ultimoSalario / 360) * $diasTotalesAnio;

            Aguinaldo::updateOrCreate(
                [
                    'empleado_id' => $empleado->id,
                    'gestion' => $gestion,
                    'tipo' => $tipo
                ],
                [
                    'ultimo_salario' => $ultimoSalario,
                    'promedio_tres_meses' => $ultimoSalario,
                    'base_calculo' => $ultimoSalario,
                    'meses_trabajados' => $meses,
                    'dias_trabajados' => $diasTotalesAnio,
                    'monto_pagar' => round($montoPagar, 2),
                    'estado' => 'Pendiente'
                ]
            );
        }

        return redirect()->route('admin.aguinaldos.index', ['gestion' => $gestion, 'tipo' => $tipo])
                         ->with('mensaje', "Planilla masiva de [$tipo] para la gestión $gestion calculada con éxito.")
                         ->with('icono', 'success');
    }

    public function show($id)
    {
        $aguinaldo = Aguinaldo::with(['empleado.area'])->findOrFail($id);
        $ajuste = Ajuste::first();
        $simboloMoneda = $ajuste->divisa ?? 'Bs.';

        return view('admin.aguinaldos.show', compact('aguinaldo', 'ajuste', 'simboloMoneda'));
    }

    public function edit($id)
    {
        $aguinaldo = Aguinaldo::with(['empleado'])->findOrFail($id);
        $empleados = Empleado::where('estado', true)->get();
        $ajuste = Ajuste::first();
        $simboloMoneda = $ajuste->divisa ?? 'Bs.';

        return view('admin.aguinaldos.edit', compact('aguinaldo', 'empleados', 'simboloMoneda'));
    }

    public function update(Request $request, $id)
    {
        $aguinaldo = Aguinaldo::findOrFail($id);

        $request->validate([
            'empleado_id' => 'required|exists:empleados,id',
            'gestion' => 'required|digits:4|integer',
            'tipo' => 'required|in:Aguinaldo,Doble Aguinaldo',
            'meses_trabajados' => 'required|integer|min:1|max:12',
            'dias_trabajados' => 'required|integer|min:0|max:360',
            'monto_pagar' => 'required|numeric|min:0',
            'estado' => 'required|in:Pendiente,Pagado',
            'fecha_pago' => 'nullable|date',
            'observaciones' => 'nullable|string|max:500',
        ]);

        $empleado = Empleado::findOrFail($request->empleado_id);
        $ultimoSalario = $empleado->salario ?? ($empleado->sueldo_base ?? 0);

        $aguinaldo->update([
            'empleado_id' => $request->empleado_id,
            'gestion' => $request->gestion,
            'tipo' => $request->tipo,
            'ultimo_salario' => $ultimoSalario,
            'promedio_tres_meses' => $ultimoSalario,
            'base_calculo' => $ultimoSalario,
            'meses_trabajados' => $request->meses_trabajados,
            'dias_trabajados' => $request->dias_trabajados,
            'monto_pagar' => $request->monto_pagar,
            'estado' => $request->estado,
            'fecha_pago' => $request->fecha_pago,
            'observaciones' => $request->observaciones,
        ]);

        return redirect()->route('admin.aguinaldos.index', ['gestion' => $aguinaldo->gestion, 'tipo' => $aguinaldo->tipo])
                         ->with('mensaje', 'Registro actualizado correctamente.')
                         ->with('icono', 'success');
    }

    public function destroy($id)
    {
        $aguinaldo = Aguinaldo::findOrFail($id);
        $gestion = $aguinaldo->gestion;
        $tipo = $aguinaldo->tipo;
        $aguinaldo->delete();

        return redirect()->route('admin.aguinaldos.index', ['gestion' => $gestion, 'tipo' => $tipo])
                         ->with('mensaje', 'Registro eliminado correctamente.')
                         ->with('icono', 'success');
    }

    public function print($id)
    {
        $aguinaldo = Aguinaldo::with(['empleado.area'])->findOrFail($id);
        $ajuste = Ajuste::first();
        $simboloMoneda = $ajuste->divisa ?? 'Bs.';

        return view('admin.aguinaldos.print', compact('aguinaldo', 'ajuste', 'simboloMoneda'));
    }

    public function printGeneral(Request $request)
    {
        $gestion = $request->get('gestion', date('Y'));
        $tipo = $request->get('tipo', 'Aguinaldo');

        $aguinaldos = Aguinaldo::with('empleado.area')
            ->where('gestion', $gestion)
            ->where('tipo', $tipo)
            ->get();

        $ajuste = Ajuste::first();
        $simboloMoneda = $ajuste->divisa ?? 'Bs.';

        return view('admin.aguinaldos.print-general', compact('aguinaldos', 'gestion', 'tipo', 'ajuste', 'simboloMoneda'));
    }
}
