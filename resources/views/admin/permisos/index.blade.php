<x-layouts::app title="Gestión de Permisos y Vacaciones">
    <div class="relative mb-6 w-full flex justify-between items-center">
        <div>
            <flux:heading size="xl" level="1">Permisos y Vacaciones</flux:heading>
            <flux:subheading>Historial de solicitudes del personal.</flux:subheading>
        </div>
        <div>
            <a href="{{ route('admin.permisos.create') }}">
                <flux:button variant="primary" icon="plus" color="blue">Nuevo Permiso</flux:button>
            </a>
        </div>
    </div>

    <!-- Buscador -->
    <div class="flex gap-4 mb-6">
        <div class="flex-1">
            <form action="{{ route('admin.permisos.index') }}" method="GET" class="flex gap-2 w-1/2">
                <div class="flex-1">
                    <flux:input name="buscar" type="text" icon="magnifying-glass" placeholder="Buscar por empleado, tipo o estado..."
                        value="{{ $buscar ?? '' }}" />
                </div>
                <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition flex items-center gap-2 cursor-pointer">
                    <i class="fas fa-search"></i> Buscar
                </button>
                @if (isset($buscar) && $buscar != '')
                    <a href="{{ route('admin.permisos.index') }}" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                        <i class="fas fa-trash"></i> Limpiar
                    </a>
                @endif
            </form>
        </div>
    </div>

    <flux:separator variant="subtle" class="mb-6" />

    <!-- Tabla -->
    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 shadow-sm">
        <table class="min-w-full border-collapse">
            <thead class="bg-gray-50 dark:bg-zinc-900 text-center">
                <tr>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 uppercase">Nro</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 uppercase">Empleado</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 uppercase">Área</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 uppercase">Tipo Solicitud</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 uppercase">Días</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 uppercase">Fecha Inicio</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 uppercase">Fecha Fin</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 uppercase">Retorno a Trabajar</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 uppercase">Estado</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-zinc-800 text-center">
                @forelse ($permisos as $permiso)
                    <tr class="hover:bg-blue-50 dark:hover:bg-zinc-700/50 transition">
                        <td class="px-3 py-3 border border-gray-200 dark:border-zinc-700 text-sm">{{ ($permisos->currentPage() - 1) * $permisos->perPage() + $loop->iteration }}</td>
                        <td class="px-4 py-3 border border-gray-200 dark:border-zinc-700 text-sm text-left font-semibold">
                            {{ $permiso->empleado->nombre ?? 'N/A' }} {{ $permiso->empleado->apellido ?? '' }}
                            <div class="text-xs text-gray-500 font-normal">CI: {{ $permiso->empleado->ci ?? 'N/A' }}</div>
                        </td>
                        <td class="px-4 py-3 border border-gray-200 dark:border-zinc-700 text-sm text-gray-600 dark:text-zinc-400">
                            {{ $permiso->empleado->area->nombre ?? $permiso->empleado->area ?? 'N/A' }}
                        </td>
                        <td class="px-4 py-3 border border-gray-200 dark:border-zinc-700 text-sm font-medium">
                            {{ $permiso->tipo }}
                        </td>
                        <td class="px-4 py-3 border border-gray-200 dark:border-zinc-700 text-sm font-bold">
                            {{ $permiso->dias_solicitados }}
                        </td>
                        <td class="px-4 py-3 border border-gray-200 dark:border-zinc-700 text-sm">
                            {{ \Carbon\Carbon::parse($permiso->fecha_inicio)->format('d/m/Y') }}
                        </td>
                        <td class="px-4 py-3 border border-gray-200 dark:border-zinc-700 text-sm">
                            {{ \Carbon\Carbon::parse($permiso->fecha_fin)->format('d/m/Y') }}
                        </td>
                        <td class="px-4 py-3 border border-gray-200 dark:border-zinc-700 text-sm font-medium text-blue-600 dark:text-blue-400">
                            {{ $permiso->fecha_retorno ? \Carbon\Carbon::parse($permiso->fecha_retorno)->format('d/m/Y') : 'N/A' }}
                        </td>
                        <td class="px-4 py-3 border border-gray-200 dark:border-zinc-700 text-sm">
                            @if($permiso->estado == 'Aprobado')
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300">Aprobado</span>
                            @elseif($permiso->estado == 'Rechazado')
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300">Rechazado</span>
                            @else
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300">Pendiente</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 border border-gray-200 dark:border-zinc-700">
                            <div class="flex justify-center items-center gap-1.5">
                                <!-- Editar -->
                                <a href="{{ route('admin.permisos.edit', $permiso->id) }}" class="px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-medium rounded-md shadow-sm transition flex items-center">
                                    <i class="fas fa-edit mr-1"></i> Editar
                                </a>

                                <!-- Eliminar con confirmación de SweetAlert -->
                                <form action="{{ route('admin.permisos.destroy', $permiso->id) }}" method="POST" class="inline-flex" id="formEliminarPermiso{{ $permiso->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs font-medium rounded-md shadow-sm transition cursor-pointer flex items-center" onclick="confirmarEliminacionPermiso{{ $permiso->id }}(event)">
                                        <i class="fas fa-trash-alt mr-1"></i> Eliminar
                                    </button>
                                </form>
                                <script>
                                    function confirmarEliminacionPermiso{{ $permiso->id }}(e) {
                                        e.preventDefault();
                                        Swal.fire({
                                            title: '¿Eliminar solicitud de permiso?',
                                            text: "¡No podrás revertir esta acción!",
                                            icon: 'warning',
                                            showCancelButton: true,
                                            confirmButtonColor: '#d33',
                                            cancelButtonColor: '#3085d6',
                                            confirmButtonText: 'Sí, eliminar',
                                            cancelButtonText: 'Cancelar'
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                document.getElementById('formEliminarPermiso{{ $permiso->id }}').submit();
                                            }
                                        });
                                    }
                                </script>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="px-6 py-6 text-center text-sm text-gray-500 dark:text-zinc-400">No hay solicitudes de permisos registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($permisos->hasPages())
        <div class="mt-4">{{ $permisos->links() }}</div>
    @endif
</x-layouts::app>
