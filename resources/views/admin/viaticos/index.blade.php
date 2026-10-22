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

    <!-- Barra de Búsqueda, Filtros por Fecha y Paginación por Selector -->
    <div class="mb-6 bg-white dark:bg-zinc-800 p-4 rounded-lg border border-gray-200 dark:border-zinc-700 shadow-sm">
        <form method="GET" action="{{ route('admin.viaticos.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
            <div>
                <label class="block text-xs font-semibold uppercase text-gray-600 dark:text-gray-400 mb-1">Buscar</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Destino, motivo..." class="w-full text-sm rounded-lg border-gray-300 dark:border-zinc-700 dark:bg-zinc-900 text-gray-900 dark:text-white p-2">
            </div>
            <div>
                <label class="block text-xs font-semibold uppercase text-gray-600 dark:text-gray-400 mb-1">Desde (Salida)</label>
                <input type="date" name="fecha_inicio" value="{{ request('fecha_inicio') }}" class="w-full text-sm rounded-lg border-gray-300 dark:border-zinc-700 dark:bg-zinc-900 text-gray-900 dark:text-white p-2">
            </div>
            <div>
                <label class="block text-xs font-semibold uppercase text-gray-600 dark:text-gray-400 mb-1">Hasta (Retorno)</label>
                <input type="date" name="fecha_fin" value="{{ request('fecha_fin') }}" class="w-full text-sm rounded-lg border-gray-300 dark:border-zinc-700 dark:bg-zinc-900 text-gray-900 dark:text-white p-2">
            </div>
            <div>
                <label class="block text-xs font-semibold uppercase text-gray-600 dark:text-gray-400 mb-1">Mostrar</label>
                <select name="perPage" onchange="this.form.submit()" class="w-full text-sm rounded-lg border-gray-300 dark:border-zinc-700 dark:bg-zinc-900 text-gray-900 dark:text-white p-2">
                    <option value="5" {{ request('perPage') == 5 ? 'selected' : '' }}>5 por página</option>
                    <option value="10" {{ request('perPage', 10) == 10 ? 'selected' : '' }}>10 por página</option>
                    <option value="20" {{ request('perPage') == 20 ? 'selected' : '' }}>20 por página</option>
                    <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50 por página</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow transition">
                    <i class="fas fa-search mr-1"></i> Filtrar
                </button>
                <a href="{{ route('admin.viaticos.index') }}" class="px-3 py-2 bg-gray-200 dark:bg-zinc-700 hover:bg-gray-300 dark:hover:bg-zinc-600 text-gray-800 dark:text-gray-200 text-sm font-semibold rounded-lg transition" title="Limpiar Filtros">
                    <i class="fas fa-redo"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Tabla de Viajes Registrados -->
    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 shadow-sm">
        <table class="min-w-full border-collapse">
            <thead class="bg-gray-50 dark:bg-zinc-900 text-center text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-zinc-700">
                <tr>
                    <th class="py-3 px-3">Nro</th>
                    <th class="py-3 px-4">Origen</th>
                    <th class="py-3 px-4">Destino</th>
                    <th class="py-3 px-4">Fecha Salida</th>
                    <th class="py-3 px-4">Fecha Retorno</th>
                    <th class="py-3 px-3">Días</th>
                    <th class="py-3 px-3">Personal</th>
                    <th class="py-3 px-4">Monto / Día</th>
                    <th class="py-3 px-4">Viático p/p</th>
                    <th class="py-3 px-4">Total Viaje</th>
                    <th class="py-3 px-4">Estado</th>
                    <th class="py-3 px-4 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-zinc-700 text-sm text-center text-gray-800 dark:text-gray-200">
                @forelse ($viajes as $viaje)
                    <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition">
                        <td class="py-3 px-3 font-bold text-gray-600 dark:text-gray-400">
                            {{ $loop->iteration + ($viajes->currentPage() - 1) * $viajes->perPage() }}
                        </td>
                        <td class="py-3 px-4 font-medium text-gray-700 dark:text-gray-300">
                            <i class="fas fa-map-marker-alt text-blue-500 mr-1"></i> {{ $viaje->origen }}
                        </td>
                        <td class="py-3 px-4 font-semibold text-gray-900 dark:text-white">
                            <i class="fas fa-map-marker-alt text-red-500 mr-1"></i> {{ $viaje->destino }}
                        </td>
                        <td class="py-3 px-4 text-gray-600 dark:text-gray-300">
                            {{ \Carbon\Carbon::parse($viaje->fecha_inicio)->format('d/m/Y') }}
                        </td>
                        <td class="py-3 px-4 text-gray-600 dark:text-gray-300">
                            {{ \Carbon\Carbon::parse($viaje->fecha_fin)->format('d/m/Y') }}
                        </td>
                        <td class="py-3 px-3">
                            <span class="px-2 py-1 rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300 text-xs font-bold">
                                {{ $viaje->dias_totales }} d
                            </span>
                        </td>
                        <td class="py-3 px-3">
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

                        <!-- Columna de Estado con Selector Directo -->
                        <td class="py-3 px-4">
                            <form action="{{ route('admin.viaticos.estado', $viaje->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <select name="estado" onchange="this.form.submit()" class="text-xs font-bold uppercase rounded-lg px-2.5 py-1 border transition cursor-pointer
                                    {{ $viaje->estado == 'PAGADO' ? 'bg-emerald-100 text-emerald-800 border-emerald-300 dark:bg-emerald-900/50 dark:text-emerald-300 dark:border-emerald-700' : '' }}
                                    {{ $viaje->estado == 'PENDIENTE' ? 'bg-amber-100 text-amber-800 border-amber-300 dark:bg-amber-900/50 dark:text-amber-300 dark:border-amber-700' : '' }}
                                    {{ $viaje->estado == 'ANULADO' ? 'bg-red-100 text-red-800 border-red-300 dark:bg-red-900/50 dark:text-red-300 dark:border-red-700' : '' }}">
                                    <option value="PENDIENTE" {{ $viaje->estado == 'PENDIENTE' ? 'selected' : '' }}>PENDIENTE</option>
                                    <option value="PAGADO" {{ $viaje->estado == 'PAGADO' ? 'selected' : '' }}>PAGADO</option>
                                    <option value="ANULADO" {{ $viaje->estado == 'ANULADO' ? 'selected' : '' }}>ANULADO</option>
                                </select>
                            </form>
                        </td>

                        <!-- Acciones Estilizadas -->
                        <td class="py-3 px-4">
                            <div class="flex items-center justify-center gap-1.5 flex-wrap">
                                <!-- Botón Ver -->
                                <a href="{{ route('admin.viaticos.show', $viaje->id) }}" class="inline-flex items-center px-2.5 py-1.5 bg-sky-500 hover:bg-sky-600 text-white text-xs font-semibold rounded shadow transition" title="Ver Detalle">
                                    <i class="fas fa-eye mr-1"></i> Ver
                                </a>

                                <!-- Botón PDF (Habilitado sólo si el estado es PAGADO) -->
                                @if($viaje->estado == 'PAGADO')
                                    <a href="{{ route('admin.viaticos.pdf', $viaje->id) }}" target="_blank" class="inline-flex items-center px-2.5 py-1.5 bg-purple-600 hover:bg-purple-700 text-white text-xs font-semibold rounded shadow transition" title="Generar PDF / Comprobante">
                                        <i class="fas fa-file-pdf mr-1"></i> PDF
                                    </a>
                                @endif

                                <!-- Botón Editar -->
                                <a href="{{ route('admin.viaticos.edit', $viaje->id) }}" class="inline-flex items-center px-2.5 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-semibold rounded shadow transition" title="Editar">
                                    <i class="fas fa-edit mr-1"></i> Editar
                                </a>

                                <!-- Botón Eliminar -->
                                <form action="{{ route('admin.viaticos.destroy', $viaje->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este viaje?')" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-2.5 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold rounded shadow transition" title="Eliminar">
                                        <i class="fas fa-trash mr-1"></i> Eliminar
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="12" class="py-8 text-center text-gray-500 dark:text-gray-400">
                            No hay viajes ni viáticos registrados con los filtros seleccionados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación y Contador de Registros Estilizado -->
    <div class="mt-4 flex flex-col md:flex-row items-center justify-between gap-4 px-2">
        <div class="text-sm text-gray-500 dark:text-gray-400">
            Mostrando <span class="font-semibold text-gray-800 dark:text-white">{{ $viajes->lastItem() ?? 0 }}</span>
            de <span class="font-semibold text-gray-800 dark:text-white">{{ $viajes->total() }}</span>
            resultados
        </div>

        <div>
            {{ $viajes->links() }}
        </div>
    </div>
</x-layouts::app>
