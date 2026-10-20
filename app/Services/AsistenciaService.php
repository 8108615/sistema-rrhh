<?php

namespace App\Services;

use App\Models\Marcacion;
use App\Models\Asistencia;
use Carbon\Carbon;

class AsistenciaService
{
    /**
     * Procesa y calcula el resumen diario de asistencia para un empleado en una fecha dada.
     */
    public static function procesarAsistenciaDia($empleadoId, $fecha)
    {
        // Obtener todas las marcaciones del empleado en el día ordenadas por hora
        $marcaciones = Marcacion::where('empleado_id', $empleadoId)
            ->whereDate('fecha_hora', $fecha)
            ->orderBy('fecha_hora', 'asc')
            ->get();

        if ($marcaciones->isEmpty()) {
            return;
        }

        $primeraEntrada = $marcaciones->first()->fecha_hora->format('H:i:s');
        $ultimaSalida = $marcaciones->last()->fecha_hora->format('H:i:s');

        $minutosTrabajados = 0;
        $totalMarcaciones = $marcaciones->count();

        // Si solo tiene una marcación en el día
        if ($totalMarcaciones == 1) {
            Asistencia::updateOrCreate(
                ['empleado_id' => $empleadoId, 'fecha' => $fecha],
                [
                    'primera_entrada' => $primeraEntrada,
                    'ultima_salida'   => $primeraEntrada,
                    'horas_trabajadas' => 0,
                    'estado'           => 'INCOMPLETO',
                ]
            );
            return;
        }

        // Procesar en pares o bloques de tiempo
        for ($i = 0; $i < $totalMarcaciones - 1; $i += 2) {
            $entrada = $marcaciones[$i]->fecha_hora;
            $salida  = $marcaciones[$i + 1]->fecha_hora;

            // Diferencia en minutos entre entrada y salida del bloque
            $diferenciaBloque = $entrada->diffInMinutes($salida);

            // Verificar la pausa con el siguiente bloque (si existe)
            if (isset($marcaciones[$i + 2])) {
                $siguienteEntrada = $marcaciones[$i + 2]->fecha_hora;
                $horasPausa = $salida->diffInHours($siguienteEntrada);

                // Si la pausa es de 5 horas o más, consideramos que la jornada terminó en $salida
                if ($horasPausa >= 5) {
                    $minutosTrabajados += $diferenciaBloque;
                    $ultimaSalida = $salida->format('H:i:s');
                    break;
                }
            }

            $minutosTrabajados += $diferenciaBloque;
        }

        // Convertir minutos a horas decimales (ej. 8.50 horas)
        $horasTrabajadas = round($minutosTrabajados / 60, 2);

        // Determinar estado según horas trabajadas
        $estado = 'PRESENTE';
        if ($horasTrabajadas < 4 && $horasTrabajadas > 0) {
            $estado = 'MEDIO_TIEMPO';
        } elseif ($horasTrabajadas == 0) {
            $estado = 'INCOMPLETO';
        }

        Asistencia::updateOrCreate(
            ['empleado_id' => $empleadoId, 'fecha' => $fecha],
            [
                'primera_entrada'  => $primeraEntrada,
                'ultima_salida'    => $ultimaSalida,
                'horas_trabajadas' => $horasTrabajadas,
                'estado'           => $estado,
            ]
        );
    }
}
