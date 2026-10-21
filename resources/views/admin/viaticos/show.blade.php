<x-layouts::app title="Detalle de Viático">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detalle de Viaje: {{ $viaje->destino }}</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Resumen y desglose de viáticos asignados al personal comisionado.</p>
        </div>
        <a href="{{ route('admin.viaticos.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-zinc-700 text-gray-800 dark:text-gray-200 font-semibold text-sm rounded-lg hover:bg-gray-300 dark:hover:bg-zinc-600 transition">
            Volver al Listado
        </a>
    </div>

    <!-- Resumen General -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="p-4 rounded-lg bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 shadow-sm">
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Fechas del Viaje</p>
            <p class="text-sm font-bold text-gray-900 dark:text-white mt-1">
                {{ \Carbon\Carbon::parse($viaje->fecha_inicio)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($viaje->fecha_fin)->format('d/m/Y') }}
            </p>
        </div>
        <div class="p-4 rounded-lg bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 shadow-sm">
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Días Totales</p>
            <p class="text-lg font-bold text-blue-600 dark:text-blue-400 mt-1">{{ $viaje->dias_totales }} Días</p>
        </div>
        <div class="p-4 rounded-lg bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 shadow-sm">
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Viático por Persona</p>
            <p class="text-lg font-bold text-emerald-600 dark:text-emerald-400 mt-1">Bs. {{ number_format($viaje->total_por_persona, 2) }}</p>
        </div>
        <div class="p-4 rounded-lg bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 shadow-sm">
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Total Desembolso Viaje</p>
            <p class="text-xl font-black text-indigo-600 dark:text-indigo-400 mt-1">Bs. {{ number_format($viaje->monto_total_viaje, 2) }}</p>
        </div>
    </div>

    @if($viaje->motivo)
        <div class="mb-6 p-4 bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg shadow-sm">
            <h3 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Motivo / Descripción del Viaje</h3>
            <p class="text-sm text-gray-800 dark:text-gray-200">{{ $viaje->motivo }}</p>
        </div>
    @endif

    <!-- Lista de Empleados Asignados -->
    <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-200 dark:border-zinc-700 font-bold text-gray-900 dark:text-white">
            Personal Comisionado ({{ $viaje->empleados->count() }} personas)
        </div>
        <table class="min-w-full border-collapse">
            <thead class="bg-gray-50 dark:bg-zinc-900 text-xs font-semibold uppercase text-gray-700 dark:text-gray-300">
                <tr>
                    <th class="py-3 px-4 text-left">Empleado</th>
                    <th class="py-3 px-4 text-center">C.I.</th>
                    <th class="py-3 px-4 text-center">Monto Asignado</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-zinc-700 text-sm">
                @foreach ($viaje->empleados as $emp)
                    <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50">
                        <td class="py-3 px-4 font-medium text-gray-900 dark:text-white">
                            {{ $emp->nombre }} {{ $emp->apellido }}
                        </td>
                        <td class="py-3 px-4 text-center text-gray-600 dark:text-gray-400">{{ $emp->ci }}</td>
                        <td class="py-3 px-4 text-center font-bold text-emerald-600 dark:text-emerald-400">
                            Bs. {{ number_format($emp->pivot->monto_viatico, 2) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-layouts::app>
