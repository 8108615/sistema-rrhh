<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Empleado;
use App\Models\Contrato;
use App\Models\Permiso; // Ajusta si tu modelo de permisos tiene otro nombre
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Estadísticas Clave
        $totalEmpleados = Empleado::where('estado', 'Activo')->count();
        $masaSalarial = Contrato::where('estado', 'Activo')->sum('salario_mensual');
        $totalContratosActivos = Contrato::where('estado', 'Activo')->count();

        // Cumpleañeros del mes actual
        $mesActual = Carbon::now()->month;
        $cumpleañeros = Empleado::whereMonth('fecha_nacimiento', $mesActual)
                                ->where('estado', 'Activo')
                                ->count();

        // 2. Contratos que vencen en los próximos 30 días
        $hoy = Carbon::now();
        $fechaLimite = Carbon::now()->addDays(30);

        $contratosPorVencer = Contrato::with('empleado')
            ->where('estado', 'Activo')
            ->whereNotNull('fecha_fin')
            ->whereBetween('fecha_fin', [$hoy, $fechaLimite])
            ->orderBy('fecha_fin', 'asc')
            ->get();

        return view('admin.dashboard', compact(
            'totalEmpleados',
            'masaSalarial',
            'totalContratosActivos',
            'cumpleañeros',
            'contratosPorVencer'
        ));
    }
}
