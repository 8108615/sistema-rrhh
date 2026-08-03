<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Empleado;
use App\Models\Contrato;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalEmpleados = Empleado::where('estado', 1)->count();
        $masaSalarial = Empleado::where('estado', 1)->sum('salario');

        $totalContratosActivos = Contrato::where('estado', 1)->count();
        if ($totalContratosActivos == 0) {
            $totalContratosActivos = $totalEmpleados;
        }

        // 1. Cumpleañeros del mes actual (Contador y Lista)
        $mesActual = Carbon::now()->month;

        $cumpleañerosQuery = Empleado::whereMonth('fecha_nacimiento', $mesActual)
                                    ->where('estado', 1);

        $cumpleañeros = $cumpleañerosQuery->count();

        // Obtenemos el listado detallado para mostrar los nombres en la vista
        $listaCumpleañeros = $cumpleañerosQuery->orderByRaw('DAY(fecha_nacimiento) ASC')->get();

        // 2. Contratos que vencen en los próximos 30 días
        $hoy = Carbon::now();
        $fechaLimite = Carbon::now()->addDays(30);

        $contratosPorVencer = Contrato::with('empleado')
            ->where('estado', 1)
            ->whereNotNull('fecha_fin')
            ->whereBetween('fecha_fin', [$hoy, $fechaLimite])
            ->orderBy('fecha_fin', 'asc')
            ->get();

        return view('admin.dashboard', compact(
            'totalEmpleados',
            'masaSalarial',
            'totalContratosActivos',
            'cumpleañeros',
            'listaCumpleañeros',
            'contratosPorVencer'
        ));
    }
}
