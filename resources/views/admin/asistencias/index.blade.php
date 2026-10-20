<x-layouts::app title="Control de Asistencias">
    <div class="relative mb-6 w-full flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <flux:heading size="xl" level="1">Control de Asistencias</flux:heading>
            <flux:subheading>Registro, importación de marcaciones y control de horas trabajadas.</flux:subheading>
        </div>

        <!-- Formulario para importar archivo del biométrico -->
        <form action="{{ route('admin.asistencias.importar') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2">
            @csrf
            <input type="file"
                   name="archivo"
                   accept=".txt,.csv"
                   required
                   class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-zinc-700 dark:file:text-gray-200 cursor-pointer" />

            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm transition flex items-center gap-2 whitespace-nowrap cursor-pointer">
                <i class="fas fa-file-import"></i> Importar Marcaciones
            </button>
        </form>
    </div>

    <!-- Barra de Filtros Avanzados (Nombre/C.I., Fecha Inicio, Fecha Fin) -->
    <div class="mb-6 p-4 rounded-lg bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 shadow-sm">
        <form action="{{ route('admin.asistencias.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
            <!-- Filtro por nombre o C.I. -->
            <div class="flex-1 min-w-[220px]">
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Empleado o C.I.</label>
                <input type="text"
                       name="empleado"
                       value="{{ request('empleado') }}"
                       placeholder="Buscar por Nombre, Apellido o C.I..."
                       class="w-full rounded-lg bg-gray-50 dark:bg-zinc-900 border-gray-300 dark:border-zinc-700 text-gray-900 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2 px-3">
            </div>

            <!-- Fecha Desde -->
            <div class="w-full sm:w-auto">
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Fecha Desde</label>
                <input type="date"
                       name="fecha_inicio"
                       value="{{ request('fecha_inicio') }}"
                       class="w-full rounded-lg bg-gray-50 dark:bg-zinc-900 border-gray-300 dark:border-zinc-700 text-gray-900 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2 px-3">
            </div>

            <div class="hidden sm:flex items-center pt-5">
                <span class="text-gray-400 text-sm">a</span>
            </div>

            <!-- Fecha Hasta -->
            <div class="w-full sm:w-auto">
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Fecha Hasta</label>
                <input type="date"
                       name="fecha_fin"
                       value="{{ request('fecha_fin') }}"
                       class="w-full rounded-lg bg-gray-50 dark:bg-zinc-900 border-gray-300 dark:border-zinc-700 text-gray-900 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2 px-3">
            </div>

            <!-- Botones de Acción -->
            <div class="flex items-center gap-2 pt-5">
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-lg transition flex items-center gap-2 cursor-pointer shadow-sm">
                    <i class="fas fa-filter"></i> Filtrar
                </button>

                @if(request()->hasAny(['empleado', 'fecha_inicio', 'fecha_fin']))
                    <a href="{{ route('admin.asistencias.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-zinc-700 hover:bg-gray-300 dark:hover:bg-zinc-600 text-gray-700 dark:text-gray-200 text-sm font-medium rounded-lg transition">
                        Limpiar Filtros
                    </a>
                @endif
            </div>
        </form>
    </div>
    <!-- Tarjetas de Resumen / Total Acumulado -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <!-- Total Días Registrados -->
        <div class="p-4 rounded-lg bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Días Registrados</p>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalDias }} días</h3>
            </div>
            <div class="p-3 bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 rounded-full">
                <i class="fas fa-calendar-check text-xl"></i>
            </div>
        </div>

        <!-- Total Horas Trabajadas -->
        <div class="p-4 rounded-lg bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Horas Trabajadas</p>
                <h3 class="text-2xl font-bold text-indigo-600 dark:text-indigo-400 mt-1">{{ number_format($totalHoras, 2) }} hrs</h3>
            </div>
            <div class="p-3 bg-indigo-100 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 rounded-full">
                <i class="fas fa-clock text-xl"></i>
            </div>
        </div>

        <!-- Promedio Diario -->
        <div class="p-4 rounded-lg bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Promedio Diario</p>
                <h3 class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 mt-1">{{ number_format($promedioDiario, 2) }} hrs/día</h3>
            </div>
            <div class="p-3 bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 rounded-full">
                <i class="fas fa-chart-line text-xl"></i>
            </div>
        </div>
    </div>

    <flux:separator variant="subtle" class="mb-6" />

    <!-- Tabla de Asistencias -->
    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 shadow-sm">
        <table class="min-w-full border-collapse">
            <thead class="bg-gray-50 dark:bg-zinc-900 text-center">
                <tr>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nro</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Empleado</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">C.I.</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fecha</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">1ra Entrada</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Última Salida</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Horas Trabajadas</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estado</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-zinc-800 text-center">
                @forelse ($asistencias as $asistencia)
                    <tr class="even:bg-slate-50 odd:bg-white dark:even:bg-zinc-700/20 dark:odd:bg-zinc-800 hover:bg-blue-50 dark:hover:bg-zinc-700/50 transition">
                        <td class="px-3 py-3 border border-gray-200 dark:border-zinc-700 text-sm text-gray-900 dark:text-gray-100">
                            {{ ($asistencias->currentPage() - 1) * $asistencias->perPage() + $loop->iteration }}
                        </td>
                        <td class="px-4 py-3 border border-gray-200 dark:border-zinc-700 text-sm font-medium text-gray-900 dark:text-white text-left">
                            {{ $asistencia->empleado->nombre ?? 'N/A' }} {{ $asistencia->empleado->apellido ?? '' }}
                        </td>
                        <td class="px-4 py-3 border border-gray-200 dark:border-zinc-700 text-sm text-gray-900 dark:text-gray-100">
                            {{ $asistencia->empleado->ci ?? 'N/A' }}
                        </td>
                        <td class="px-4 py-3 border border-gray-200 dark:border-zinc-700 text-sm text-gray-600 dark:text-gray-300">
                            {{ $asistencia->fecha }}
                        </td>
                        <td class="px-4 py-3 border border-gray-200 dark:border-zinc-700 text-sm text-emerald-600 dark:text-emerald-400 font-semibold">
                            {{ $asistencia->primera_entrada ?? '--:--' }}
                        </td>
                        <td class="px-4 py-3 border border-gray-200 dark:border-zinc-700 text-sm text-sky-600 dark:text-sky-400 font-semibold">
                            {{ $asistencia->ultima_salida ?? '--:--' }}
                        </td>
                        <td class="px-4 py-3 border border-gray-200 dark:border-zinc-700 text-sm font-bold text-indigo-600 dark:text-indigo-400">
                            {{ $asistencia->horas_trabajadas }} hrs
                        </td>
                        <td class="px-4 py-3 border border-gray-200 dark:border-zinc-700 text-center text-sm">
                            @if ($asistencia->estado == 'PRESENTE')
                                <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-emerald-500 text-white">Presente</span>
                            @elseif ($asistencia->estado == 'MEDIO_TIEMPO')
                                <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-amber-500 text-white">Medio Tiempo</span>
                            @else
                                <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-red-500 text-white">Incompleto</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                            No se encontraron registros de asistencia para los criterios seleccionados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($asistencias->hasPages())
        <div class="px-3 mt-4 flex justify-between items-center">
            <div class="text-gray-600 dark:text-gray-400 text-sm">
                Mostrando <span class="font-semibold">{{ $asistencias->firstItem() }}</span> al <span class="font-semibold">{{ $asistencias->lastItem() }}</span> de <span class="font-semibold">{{ $asistencias->total() }}</span> resultados.
            </div>
            <div>
                {{ $asistencias->links() }}
            </div>
        </div>
    @endif

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#3b82f6',
                timer: 3000,
                timerProgressBar: true
            });
        </script>
    @endif
</x-layouts::app>
