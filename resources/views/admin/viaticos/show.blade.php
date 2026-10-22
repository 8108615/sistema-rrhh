<x-layouts::app title="Detalle de Viáticos">
    <!-- Encabezado con origen -> destino y acciones -->
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    <i class="fas fa-map-marker-alt text-blue-500 mr-1"></i> {{ $viaje->origen }}
                    <span class="text-gray-400 mx-1">➔</span>
                    <i class="fas fa-map-marker-alt text-red-500 mr-1"></i> {{ $viaje->destino }}
                </h1>
                <!-- Badge de Estado -->
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                    {{ $viaje->estado == 'APROBADO' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300' : '' }}
                    {{ $viaje->estado == 'PENDIENTE' ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300' : '' }}
                    {{ $viaje->estado == 'PAGADO' ? 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/50 dark:text-indigo-300' : '' }}">
                    {{ $viaje->estado ?? 'PENDIENTE' }}
                </span>
            </div>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Resumen y desglose de viáticos asignados al personal comisionado.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.viaticos.edit', $viaje->id) }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm rounded-lg shadow transition flex items-center gap-2">
                <i class="fas fa-edit"></i> Editar Viaje
            </a>
            <a href="{{ route('admin.viaticos.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-zinc-700 text-gray-800 dark:text-gray-200 font-semibold text-sm rounded-lg hover:bg-gray-300 dark:hover:bg-zinc-600 transition">
                Volver al Listado
            </a>
        </div>
    </div>

    <!-- Tarjetas de Métricas Principales -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-zinc-800 p-4 rounded-lg border border-gray-200 dark:border-zinc-700 shadow-sm">
            <span class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Fechas del Viaje</span>
            <div class="text-base font-bold text-gray-900 dark:text-white mt-1">
                {{ \Carbon\Carbon::parse($viaje->fecha_inicio)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($viaje->fecha_fin)->format('d/m/Y') }}
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-800 p-4 rounded-lg border border-gray-200 dark:border-zinc-700 shadow-sm">
            <span class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Días Totales</span>
            <div class="text-lg font-bold text-blue-600 dark:text-blue-400 mt-1">
                {{ $viaje->dias_totales }} {{ $viaje->dias_totales == 1 ? 'Día' : 'Días' }}
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-800 p-4 rounded-lg border border-gray-200 dark:border-zinc-700 shadow-sm">
            <span class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Viático por Persona</span>
            <div class="text-lg font-bold text-emerald-600 dark:text-emerald-400 mt-1">
                Bs. {{ number_format($viaje->total_por_persona, 2) }}
                <span class="text-xs text-gray-400 font-normal block">(Bs. {{ number_format($viaje->monto_diario, 2) }} / día)</span>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-800 p-4 rounded-lg border border-gray-200 dark:border-zinc-700 shadow-sm">
            <span class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Total Desembolso Viaje</span>
            <div class="text-2xl font-black text-indigo-600 dark:text-indigo-400 mt-1">
                Bs. {{ number_format($viaje->monto_total_viaje, 2) }}
            </div>
        </div>
    </div>

    <!-- Detalles del Evento / Estadio -->
    <div class="bg-white dark:bg-zinc-800 p-5 rounded-lg border border-gray-200 dark:border-zinc-700 shadow-sm mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <span class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 block mb-1">Motivo / Evento / Transmisión</span>
            <span class="text-sm font-semibold text-gray-900 dark:text-white">
                {{ $viaje->motivo ?: 'Sin descripción registrada' }}
            </span>
        </div>
        <div>
            <span class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 block mb-1">Estadio / Recinto Deportivo</span>
            <span class="text-sm font-semibold text-gray-900 dark:text-white">
                <i class="fas fa-stadium text-indigo-500 mr-1"></i> {{ $viaje->estadio ?: 'No especificado' }}
            </span>
        </div>
    </div>

    <!-- Tabla del Personal Comisionado -->
    <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 shadow-sm p-5 space-y-4">
        <div class="flex justify-between items-center border-b border-gray-200 dark:border-zinc-700 pb-3">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                Personal Comisionado ({{ $viaje->empleados->count() }} personas)
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                <thead class="bg-gray-50 dark:bg-zinc-900/60 text-xs font-semibold uppercase text-gray-600 dark:text-gray-400">
                    <tr>
                        <th class="py-3 px-4 text-left">Nro</th>
                        <th class="py-3 px-4 text-left">Empleado</th>
                        <th class="py-3 px-4 text-center">C.I.</th>
                        <th class="py-3 px-4 text-right">Monto Asignado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-zinc-700 text-sm">
                    @forelse ($viaje->empleados as $empleado)
                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/40 transition">
                            <td class="py-3 px-4 font-bold text-gray-500 dark:text-gray-400">{{ $loop->iteration }}</td>
                            <td class="py-3 px-4 font-semibold text-gray-900 dark:text-white">
                                {{ $empleado->nombre }} {{ $empleado->apellido }}
                            </td>
                            <td class="py-3 px-4 text-center text-gray-600 dark:text-gray-300">
                                {{ $empleado->ci }}
                            </td>
                            <td class="py-3 px-4 text-right font-bold text-emerald-600 dark:text-emerald-400">
                                Bs. {{ number_format($empleado->pivot->monto_viatico ?? $viaje->total_por_persona, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-4 text-center text-gray-500">No hay personal asignado a este viaje.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if(request()->has('print'))
        <script>
            window.addEventListener('load', function() {
                window.print();
            });
        </script>
    @endif
</x-layouts::app>
