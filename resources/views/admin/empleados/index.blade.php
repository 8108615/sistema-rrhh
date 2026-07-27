<x-layouts::app title="Gestión de Empleados">
    <div class="relative mb-6 w-full flex justify-between items-center">
        <div>
            <flux:heading size="xl" level="1">Empleados</flux:heading>
            <flux:subheading>Administración del personal, áreas, y datos laborales.</flux:subheading>
        </div>
        <!-- Botón para redireccionar a la vista de creación -->
        <a href="{{ route('admin.empleados.create') }}">
            <flux:button variant="primary" icon="plus" color="blue">Nuevo Empleado</flux:button>
        </a>
    </div>

    <div class="flex gap-4 mb-6">
        <div class="flex-1">
            <form action="{{ route('admin.empleados.index') }}" method="GET" class="flex gap-2 w-1/2">
                <div class="flex-1">
                    <flux:input name="buscar" type="text" icon="magnifying-glass" placeholder="Buscar por nombre, CI o área..."
                        value="{{ $buscar ?? '' }}" class="transition-all duration-200" />
                </div>
                <button type="submit"
                    class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg
                transition flex items-center gap-2 cursor-pointer">
                    <i class="fas fa-search"></i>
                    Buscar
                </button>
                @if (isset($buscar) && $buscar != '')
                    <a href="{{ route('admin.empleados.index') }}"
                        class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-semibold
                    rounded-lg transition
                    flex items-center gap-2">
                        <i class="fas fa-trash"></i> Limpiar
                    </a>
                @endif
            </form>
        </div>
    </div>

    <flux:separator variant="subtle" class="mb-6" />

    <!-- Tabla de Empleados -->
    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 shadow-sm">
        <table class="min-w-full border-collapse">
            <thead class="bg-gray-50 dark:bg-zinc-900 text-center">
                <tr>
                    <th class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Nro
                    </th>
                    <th class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Nombre Completo
                    </th>
                    <th class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        C.I.
                    </th>
                    <th class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Ubicación
                    </th>
                    <th class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Área
                    </th>
                    <th class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Salario (Bs.)
                    </th>
                    <th class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Estado
                    </th>
                    <th class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-zinc-800 text-center">
                @forelse ($empleados as $empleado)
                    <tr class="even:bg-slate-50 odd:bg-white dark:even:bg-zinc-700/20 dark:odd:bg-zinc-800 hover:bg-blue-50 dark:hover:bg-zinc-700/50 transition">
                        <td class="px-3 py-3 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                            {{ ($empleados->currentPage() - 1) * $empleados->perPage() + $loop->iteration }}
                        </td>
                        <td class="px-4 py-3 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white text-left">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 flex items-center justify-center font-bold text-xs">
                                    {{ substr($empleado->nombre, 0, 1) }}{{ substr($empleado->apellido, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-semibold">{{ $empleado->nombre }} {{ $empleado->apellido }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $empleado->email ?? 'Sin correo' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                            {{ $empleado->ci }}
                        </td>
                        <td class="px-4 py-3 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                            {{ $empleado->departamento->nombre ?? 'N/A' }}
                        </td>
                        <td class="px-4 py-3 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                            <span class="px-2.5 py-1 inline-flex text-xs font-medium rounded-md bg-indigo-100 dark:bg-indigo-900/40 text-indigo-800 dark:text-indigo-300">
                                {{ $empleado->area->nombre ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-sm font-medium text-emerald-600 dark:text-emerald-400">
                            {{ number_format($empleado->salario, 2, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-center text-sm">
                            @if ($empleado->estado)
                                <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-emerald-500 text-white border border-emerald-200">Activo</span>
                            @else
                                <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-red-500 text-white border border-red-200">Inactivo</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-center">
                            <div class="flex justify-center items-center gap-1.5">
                                <!-- Botón Ver (Show) -->
                                <a href="{{ route('admin.empleados.show', $empleado->id) }}" class="inline-flex items-center px-3 py-1.5 bg-sky-500 hover:bg-sky-600 text-white text-xs font-medium rounded-md shadow-sm transition cursor-pointer">
                                    <i class="fas fa-eye mr-1.5"></i> Ver
                                </a>
                                
                                <!-- Botón Editar -->
                                <a href="{{ route('admin.empleados.edit', $empleado) }}" class="inline-flex items-center px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-medium rounded-md shadow-sm transition cursor-pointer">
                                    <i class="fas fa-pencil-alt mr-1.5"></i> Editar
                                </a>

                                <!-- Formulario Eliminar -->
                                <form action="{{ route('admin.empleados.destroy', $empleado) }}" method="POST" class="inline-flex" id="miFormularioEliminarEmpleado{{ $empleado->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs font-medium rounded-md shadow-sm transition cursor-pointer" onclick="preguntarEliminarEmpleado{{ $empleado->id }}(event)">
                                        <i class="fas fa-trash-alt mr-1.5"></i> Eliminar
                                    </button>
                                </form>

                                <script>
                                    function preguntarEliminarEmpleado{{ $empleado->id }}(event) {
                                        event.preventDefault();
                                        Swal.fire({
                                            title: '¿Desea eliminar a este empleado?',
                                            text: "¡No podrás revertir esto!",
                                            icon: 'warning',
                                            showDenyButton: true,
                                            confirmButtonText: 'Sí, eliminar',
                                            confirmButtonColor: '#a5161d',
                                            denyButtonColor: '#270a0a',
                                            denyButtonText: 'Cancelar',
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                document.getElementById('miFormularioEliminarEmpleado{{ $empleado->id }}').submit();
                                            }
                                        });
                                    }
                                </script>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">No hay empleados registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($empleados->hasPages())
        <div class="px-3 mt-4 flex justify-between items-center">
            <div class="text-gray-600 dark:text-gray-400 text-sm">
                Mostrando
                <span class="font-semibold">{{ $empleados->firstItem() }}</span>
                al
                <span class="font-semibold">{{ $empleados->lastItem() }}</span>
                de
                <span class="font-semibold">{{ $empleados->total() }}</span>
                resultados.
            </div>
            <div>
                {{ $empleados->links() }}
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
