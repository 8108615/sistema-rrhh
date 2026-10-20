<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asistencia;
use App\Models\Marcacion;
use App\Models\Empleado;
use App\Services\AsistenciaService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AsistenciaController extends Controller
{
    /**
     * Muestra el resumen general de asistencias del personal.
     */
    public function index(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio');
        $fechaFin    = $request->get('fecha_fin');
        $search      = $request->get('empleado');

        $query = Asistencia::with('empleado');

        // Filtro por rango de fechas
        if ($fechaInicio && $fechaFin) {
            $query->whereBetween('fecha', [$fechaInicio, $fechaFin]);
        } elseif ($fechaInicio) {
            $query->whereDate('fecha', '>=', $fechaInicio);
        } elseif ($fechaFin) {
            $query->whereDate('fecha', '<=', $fechaFin);
        } else {
            // Por defecto, día actual o fecha más reciente
            $query->whereDate('fecha', now()->format('Y-m-d'));
        }

        // Filtro por nombre, apellido o C.I.
        if ($search) {
            $query->whereHas('empleado', function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('apellido', 'like', "%{$search}%")
                  ->orWhere('ci', 'like', "%{$search}%");
            });
        }

        // --- CÁLCULO DE TOTALES DENTRO DEL FILTRO APLICADO ---
        $totalHoras     = round((clone $query)->sum('horas_trabajadas'), 2);
        $totalDias      = (clone $query)->count();
        $promedioDiario = $totalDias > 0 ? round($totalHoras / $totalDias, 2) : 0;

        $asistencias = $query->orderBy('fecha', 'desc')->paginate(20)->withQueryString();

        return view('admin.asistencias.index', compact(
            'asistencias',
            'fechaInicio',
            'fechaFin',
            'search',
            'totalHoras',
            'totalDias',
            'promedioDiario'
        ));
    }

    /**
     * Procesa e importa el archivo de marcaciones del biométrico (CSV / TXT).
     */
    public function importar(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:txt,csv|max:10240',
        ]);

        $file = $request->file('archivo');
        $lines = file($file->getRealPath(), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        $fechasYEmpleadosProcesar = [];

        foreach ($lines as $line) {
            // Reemplazamos tabulaciones o comas por un separador estándar
            $data = preg_split('/[\t,;]+/', trim($line));

            // Formato esperado típico de biométrico: [0] => C.I./ID_Biometrico, [1] => Fecha/Hora
            if (count($data) >= 2) {
                $ciOrId = trim($data[0]);
                $fechaHoraStr = trim($data[1]);

                try {
                    // Carbon::parse interpreta automáticamente formatos como '19/08/2026 10:15', '2026-08-19 10:15:00', etc.
                    $fechaHora = Carbon::parse(str_replace('/', '-', $fechaHoraStr));
                } catch (\Exception $e) {
                    continue; // Omite líneas inválidas
                }

                // Buscamos al empleado por C.I.
                $empleado = Empleado::where('ci', $ciOrId)->first();

                if ($empleado) {
                    // Evitamos marcaciones duplicadas idénticas
                    $existe = Marcacion::where('empleado_id', $empleado->id)
                        ->where('fecha_hora', $fechaHora)
                        ->exists();

                    if (!$existe) {
                        Marcacion::create([
                            'empleado_id' => $empleado->id,
                            'fecha_hora'  => $fechaHora,
                            'origen'      => 'IMPORTACION',
                        ]);

                        // Guardamos para recalcular el resumen del día después
                        $fechasYEmpleadosProcesar[] = [
                            'empleado_id' => $empleado->id,
                            'fecha'       => $fechaHora->format('Y-m-d'),
                        ];
                    }
                }
            }
        }

        // Recalcular la asistencia diaria para todos los registros procesados
        foreach (array_unique($fechasYEmpleadosProcesar, SORT_REGULAR) as $item) {
            AsistenciaService::procesarAsistenciaDia($item['empleado_id'], $item['fecha']);
        }

        return redirect()->route('admin.asistencias.index')
            ->with('success', 'Archivo de marcaciones importado y procesado correctamente.');
    }
}
