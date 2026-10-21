<x-layouts::app title="Control de Viáticos">
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Control y Cálculo de Viáticos</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Gestión de viajes, comisión de servicios y asignación de viáticos al personal.</p>
        </div>
        <div>
            <a href="{{ route('admin.viaticos.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm rounded-lg shadow transition flex items-center gap-2">
                <i class="fas fa-plane-departure"></i> Registrar Nuevo Viaje
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-6 p-4 bg-emerald-100 border border-emerald-300 text-emerald-800 rounded-lg dark:bg-emerald-900/40 dark:border-emerald-700 dark:text-emerald-300">
            {{ session('success') }}
        </div>
    @endif

    <!-- Tabla de Viajes Registrados -->
    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 shadow-sm">
        <table class="min-w-full border-collapse">
            <thead class="bg-gray-50 dark:bg-zinc-900 text-center text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-zinc-700">
                <tr>
                    <th class="py-3 px-4">Destino</th>
                    <th class="py-3 px-4">Fechas</th>
                    <th class="py-3 px-4">Días</th>
                    <th class="py-3 px-4">Personal</th>
                    <th class="py-3 px-4">Monto / Día</th>
                    <th class="py-3 px-4">Viático p/p</th>
                    <th class="py-3 px-4">Total Viaje</th>
                    <th class="py-3 px-4">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-zinc-700 text-sm text-center text-gray-800 dark:text-gray-200">
                @forelse ($viajes as $viaje)
                    <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition">
                        <td class="py-3 px-4 font-semibold text-gray-900 dark:text-white">
                            <i class="fas fa-map-marker-alt text-red-500 mr-1"></i> {{ $viaje->destino }}
                        </td>
                        <td class="py-3 px-4">
                            {{ \Carbon\Carbon::parse($viaje->fecha_inicio)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($viaje->fecha_fin)->format('d/m/Y') }}
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300 text-xs font-bold">
                                {{ $viaje->dias_totales }} días
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-300 text-xs font-bold">
                                {{ $viaje->empleados->count() }} pers.
                            </span>
                        </td>
                        <td class="py-3 px-4">Bs. {{ number_format($viaje->monto_diario, 2) }}</td>
                        <td class="py-3 px-4 font-semibold text-emerald-600 dark:text-emerald-400">
                            Bs. {{ number_format($viaje->total_por_persona, 2) }}
                        </td>
                        <td class="py-3 px-4 font-bold text-indigo-600 dark:text-indigo-400">
                            Bs. {{ number_format($viaje->monto_total_viaje, 2) }}
                        </td>
                        <td class="py-3 px-4 flex justify-center gap-2">
                            <a href="{{ route('admin.viaticos.show', $viaje->id) }}" class="p-2 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition" title="Ver Detalle">
                                <i class="fas fa-eye"></i>
                            </a>
                            <form action="{{ route('admin.viaticos.destroy', $viaje->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este viaje?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="py-6 text-center text-gray-500 dark:text-gray-400">
                            No hay viajes ni viáticos registrados aún.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $viajes->links() }}
    </div>
</x-layouts::app>
