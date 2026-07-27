<x-layouts::app title="Detalle de Planilla">
    @php
        // Obtenemos el símbolo o texto de la divisa desde ajustes (por defecto 'Bs.' si no existe)
        $simboloMoneda = $ajuste->divisa ?? 'Bs.';
    @endphp

    <div class="relative mb-6 w-full flex justify-between items-center">
        <div>
            <flux:heading size="xl" level="1">Planilla: {{ $planilla->mes }} de {{ $planilla->anio }}</flux:heading>
            <flux:subheading>Detalle de pagos y sueldos correspondientes al personal.</flux:subheading>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.planillas.pdf', $planilla->id) }}" target="_blank">
                <flux:button variant="primary" icon="printer">Imprimir / PDF</flux:button>
            </a>
            <a href="{{ route('admin.planillas.index') }}">
                <flux:button variant="subtle" icon="arrow-left">Volver al listado</flux:button>
            </a>
        </div>
    </div>

    <flux:separator variant="subtle" class="mb-6" />

    <!-- Tarjeta de Resumen -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl p-6 shadow-sm">
            <span class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Periodo</span>
            <p class="text-lg font-bold text-gray-900 dark:text-white mt-1">{{ $planilla->mes }} / {{ $planilla->anio }}</p>
        </div>
        <div class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl p-6 shadow-sm">
            <span class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Total General a Pagar</span>
            <p class="text-lg font-bold text-emerald-600 dark:text-emerald-400 mt-1">{{ number_format($planilla->total_pagado, 2, ',', '.') }} {{ $simboloMoneda }}</p>
        </div>
        <div class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl p-6 shadow-sm">
            <span class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Estado Actual</span>
            <div class="mt-1">
                @if ($planilla->estado == 'Pagado')
                    <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-emerald-500 text-white">Pagado</span>
                @else
                    <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-amber-500 text-white">Pendiente</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Tabla de Detalles por Empleado -->
    <div class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-200 dark:border-zinc-700 font-semibold text-gray-800 dark:text-gray-200 flex items-center gap-2">
            <flux:icon name="users" class="h-5 w-5 text-indigo-500" />
            <span>Empleados Incluidos en la Planilla ({{ $planilla->detalles->count() }})</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-900/50 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        <th class="py-3.5 px-6">Empleado</th>
                        <th class="py-3.5 px-6">CI</th>
                        <th class="py-3.5 px-6">Departamento / Área</th>
                        <th class="py-3.5 px-6 text-right">Salario Base</th>
                        <th class="py-3.5 px-6 text-right">Bonos</th>
                        <th class="py-3.5 px-6 text-right">Descuentos AFP</th>
                        <th class="py-3.5 px-6 text-right">Líquido Pagable</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-zinc-700 text-sm text-gray-700 dark:text-gray-300">
                    @forelse ($planilla->detalles as $detalle)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-zinc-700/50 transition">
                            <td class="py-4 px-6 font-semibold text-gray-900 dark:text-white">
                                {{ $detalle->empleado->nombre }} {{ $detalle->empleado->apellido }}
                            </td>
                            <td class="py-4 px-6 text-gray-500 dark:text-gray-400">
                                {{ $detalle->empleado->ci }}
                            </td>
                            <td class="py-4 px-6 text-gray-500 dark:text-gray-400">
                                {{ $detalle->empleado->departamento->nombre ?? 'N/A' }} / {{ $detalle->empleado->area->nombre ?? 'N/A' }}
                            </td>
                            <td class="py-4 px-6 text-right font-medium">
                                {{ number_format($detalle->salario_base, 2, ',', '.') }} {{ $simboloMoneda }}
                            </td>
                            <td class="py-4 px-6 text-right text-emerald-600 dark:text-emerald-400 font-medium">
                                + {{ number_format($detalle->bonos, 2, ',', '.') }} {{ $simboloMoneda }}
                            </td>
                            <td class="py-4 px-6 text-right text-red-600 dark:text-red-400 font-medium">
                                - {{ number_format($detalle->descuentos, 2, ',', '.') }} {{ $simboloMoneda }}
                            </td>
                            <td class="py-4 px-6 text-right font-bold text-gray-900 dark:text-white">
                                {{ number_format($detalle->liquido_pagable, 2, ',', '.') }} {{ $simboloMoneda }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-gray-500 dark:text-gray-400">
                                No hay empleados registrados en esta planilla.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts::app>
