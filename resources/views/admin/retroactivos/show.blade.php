<x-layouts::app title="Detalles del Registro Retroactivo">
    <div class="relative mb-6 w-full flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <flux:heading size="xl" level="1">Detalles del Retroactivo</flux:heading>
            <flux:subheading>Información completa del incremento salarial registrado.</flux:subheading>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.retroactivos.print', $retroactivo->id) }}" target="_blank" class="px-4 py-2 bg-zinc-700 hover:bg-zinc-800 text-white font-semibold rounded-lg shadow-sm transition flex items-center gap-2 text-sm cursor-pointer">
                <i class="fas fa-print"></i> Imprimir Comprobante
            </a>
            
            <a href="{{ route('admin.retroactivos.index', ['gestion' => $retroactivo->gestion]) }}" class="inline-flex cursor-pointer">
                <flux:button variant="subtle" icon="arrow-left">Volver al listado</flux:button>
            </a>
        </div>
    </div>

    <!-- SECCIÓN 1: DATOS DEL EMPLEADO Y GESTIÓN -->
    <div class="rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-6 shadow-sm mb-6">
        <div class="flex items-center gap-2 mb-4 text-blue-600 dark:text-blue-400 font-semibold text-base border-b border-gray-100 dark:border-zinc-700 pb-2">
            <i class="fas fa-user-tie"></i>
            <span>Información del Empleado y Gestión</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <span class="block text-xs font-bold text-gray-400 uppercase">Nombre Completo</span>
                <span class="text-sm font-semibold text-gray-800 dark:text-zinc-200">
                    {{ $retroactivo->empleado->nombre ?? 'N/D' }} {{ $retroactivo->empleado->apellido ?? '' }}
                </span>
            </div>
            <div>
                <span class="block text-xs font-bold text-gray-400 uppercase">Cédula de Identidad (CI)</span>
                <span class="text-sm font-semibold text-gray-800 dark:text-zinc-200 font-mono">
                    {{ $retroactivo->empleado->ci ?? 'N/D' }}
                </span>
            </div>
            <div>
                <span class="block text-xs font-bold text-gray-400 uppercase">Área / Cargo</span>
                <span class="text-sm font-semibold text-gray-800 dark:text-zinc-200">
                    {{ $retroactivo->empleado->area->nombre ?? ($retroactivo->empleado->cargo ?? 'Sin Área') }}
                </span>
            </div>
            <div>
                <span class="block text-xs font-bold text-gray-400 uppercase">Gestión (Año)</span>
                <span class="text-sm font-semibold text-gray-800 dark:text-zinc-200 font-mono">
                    {{ $retroactivo->gestion }}
                </span>
            </div>
            <div>
                <span class="block text-xs font-bold text-gray-400 uppercase">Porcentaje de Incremento</span>
                <span class="text-sm font-bold text-blue-600 dark:text-blue-400 font-mono">
                    {{ number_format($retroactivo->porcentaje, 2, ',', '.') }}%
                </span>
            </div>
            <div>
                <span class="block text-xs font-bold text-gray-400 uppercase">Estado</span>
                <div class="mt-1">
                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $retroactivo->estado == 'Pagado' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300' }}">
                        {{ $retroactivo->estado }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- SECCIÓN 2: DETALLE FINANCIERO Y CÁLCULOS -->
    <div class="rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-6 shadow-sm mb-6">
        <div class="flex items-center gap-2 mb-4 text-emerald-600 dark:text-emerald-400 font-semibold text-base border-b border-gray-100 dark:border-zinc-700 pb-2">
            <i class="fas fa-calculator"></i>
            <span>Cálculos Salariales y Montos</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <span class="block text-xs font-bold text-gray-400 uppercase">Sueldo Anterior</span>
                <span class="text-sm font-mono font-semibold text-gray-800 dark:text-zinc-200">
                    {{ $simboloMoneda }} {{ number_format($retroactivo->sueldo_anterior, 2, ',', '.') }}
                </span>
            </div>
            <div>
                <span class="block text-xs font-bold text-gray-400 uppercase">Sueldo Nuevo</span>
                <span class="text-sm font-mono font-semibold text-gray-800 dark:text-zinc-200">
                    {{ $simboloMoneda }} {{ number_format($retroactivo->sueldo_nuevo, 2, ',', '.') }}
                </span>
            </div>
            <div>
                <span class="block text-xs font-bold text-gray-400 uppercase">Diferencia Mensual</span>
                <span class="text-sm font-mono font-semibold text-gray-800 dark:text-zinc-200">
                    {{ $simboloMoneda }} {{ number_format($retroactivo->diferencia_mensual, 2, ',', '.') }}
                </span>
            </div>
            <div>
                <span class="block text-xs font-bold text-gray-400 uppercase">Meses Aplicados</span>
                <span class="text-sm font-semibold text-gray-800 dark:text-zinc-200 font-mono">
                    {{ $retroactivo->meses_aplicados }} meses
                </span>
            </div>
            <div>
                <span class="block text-xs font-bold text-gray-400 uppercase">Monto Total a Pagar</span>
                <span class="text-base font-bold font-mono text-emerald-600 dark:text-emerald-400">
                    {{ $simboloMoneda }} {{ number_format($retroactivo->monto_pagar, 2, ',', '.') }}
                </span>
            </div>
            <div>
                <span class="block text-xs font-bold text-gray-400 uppercase">Fecha de Pago</span>
                <span class="text-sm font-semibold text-gray-800 dark:text-zinc-200">
                    @if($retroactivo->fecha_pago)
                        {{ \Carbon\Carbon::parse($retroactivo->fecha_pago)->format('d/m/Y') }}
                    @else
                        <span class="text-xs text-gray-400 italic">No registrada</span>
                    @endif
                </span>
            </div>
        </div>

        @if($retroactivo->observaciones)
            <div class="mt-6 pt-4 border-t border-gray-100 dark:border-zinc-700">
                <span class="block text-xs font-bold text-gray-400 uppercase mb-1">Observaciones</span>
                <p class="text-sm text-gray-600 dark:text-zinc-300">
                    {{ $retroactivo->observaciones }}
                </p>
            </div>
        @endif
    </div>
</x-layouts::app>
