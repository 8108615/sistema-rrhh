<x-layouts::app title="Gestión de Cargos">
    <div class="relative mb-6 w-full flex justify-between items-center">
        <div>
            <flux:heading size="xl" level="1">Cargos</flux:heading>
            <flux:subheading>Administración de los puestos de trabajo y su relación con las áreas.</flux:subheading>
        </div>
        <!-- Botón para abrir modal de creación protegido -->
        @can('admin.cargos.store')
            <flux:modal.trigger name="crear-cargo">
                <flux:button variant="primary" icon="plus" color="blue">Nuevo Cargo</flux:button>
            </flux:modal.trigger>
        @endcan
    </div>

    <div class="flex gap-4 mb-6">
        <div class="flex-1">
            <form action="{{ route('admin.cargos.index') }}" method="GET" class="flex gap-2 w-1/2">
                <div class="flex-1">
                    <flux:input name="buscar" type="text" icon="magnifying-glass" placeholder="Buscar cargos o áreas..."
                        value="{{ $buscar ?? '' }}" class="transition-all duration-200" />
                </div>
                <button type="submit"
                    class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg
                transition flex items-center gap-2 cursor-pointer">
                    <i class="fas fa-search"></i>
                    Buscar
                </button>
                @if (isset($buscar) && $buscar != '')
                    <a href="{{ route('admin.cargos.index') }}"
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

    <!-- Estilos personalizados para el Switch ON/OFF -->
    <style>
        .toggle-checkbox:checked {
            right: 0;
            border-color: #22c55e;
        }
        .toggle-checkbox:checked + .toggle-label {
            background-color: #22c55e;
        }
        .toggle-checkbox:not(:checked) + .toggle-label {
            background-color: #ef4444;
        }
        .toggle-checkbox:checked ~ .toggle-label .text-off {
            opacity: 0;
            visibility: hidden;
        }
        .toggle-checkbox:not(:checked) ~ .toggle-label .text-on {
            opacity: 0;
            visibility: hidden;
        }
        nav[role="navigation"] p {
            display: none !important;
        }
    </style>

    <!-- Tabla de Cargos -->
    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 shadow-sm">
        <table class="min-w-full border-collapse">
            <thead class="bg-gray-50 dark:bg-zinc-900 text-center">
                <tr>
                    <th class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Nro
                    </th>
                    <th class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Cargo
                    </th>
                    <th class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Área
                    </th>
                    <th class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Estado
                    </th>
                    <th class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-zinc-800">
                @forelse ($cargos as $cargo)
                    <tr class="even:bg-slate-50 odd:bg-white dark:even:bg-zinc-700/20 dark:odd:bg-zinc-800 hover:bg-blue-50 dark:hover:bg-zinc-700/50 transition">
                        <td class="px-3 py-3 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 text-center">
                            {{ ($cargos->currentPage() - 1) * $cargos->perPage() + $loop->iteration }}
                        </td>
                        <td class="px-4 py-3 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                            {{ $cargo->nombre }}
                        </td>
                        <td class="px-4 py-3 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300 text-center">
                            <span class="px-2.5 py-1 inline-flex text-xs font-medium rounded-md bg-blue-100 dark:bg-blue-900/40 text-blue-800 dark:text-blue-300">
                                {{ $cargo->area->nombre ?? 'Sin Área' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-center text-sm">
                            @if ($cargo->estado)
                                <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-emerald-500 text-white border border-emerald-200">Activo</span>
                            @else
                                <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-red-500 text-white border border-red-200">Inactivo</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-center">
                            <div class="flex justify-center items-center gap-1.5">
                                <!-- Botón Editar protegido -->
                                @can('admin.cargos.update')
                                    <flux:modal.trigger name="editar-cargo-{{ $cargo->id }}">
                                        <button type="button" class="inline-flex items-center px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-medium rounded-md shadow-sm transition cursor-pointer">
                                            <i class="fas fa-pencil-alt mr-1.5"></i> Editar
                                        </button>
                                    </flux:modal.trigger>
                                @endcan

                                <!-- Botón Eliminar protegido -->
                                @can('admin.cargos.destroy')
                                    <form action="{{ route('admin.cargos.destroy', $cargo) }}" method="POST" class="inline-flex" id="miFormularioEliminarCargo{{ $cargo->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs font-medium rounded-md shadow-sm transition cursor-pointer" onclick="preguntarEliminarCargo{{ $cargo->id }}(event)">
                                            <i class="fas fa-trash-alt mr-1.5"></i> Eliminar
                                        </button>
                                    </form>

                                    <script>
                                        function preguntarEliminarCargo{{ $cargo->id }}(event) {
                                            event.preventDefault();
                                            Swal.fire({
                                                title: '¿Desea eliminar este cargo?',
                                                icon: 'question',
                                                showDenyButton: true,
                                                confirmButtonText: 'Eliminar',
                                                confirmButtonColor: '#a5161d',
                                                denyButtonColor: '#270a0a',
                                                denyButtonText: 'Cancelar',
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    document.getElementById('miFormularioEliminarCargo{{ $cargo->id }}').submit();
                                                }
                                            });
                                        }
                                    </script>
                                @endcan
                            </div>
                        </td>
                    </tr>

                    <!-- Modal de Edición protegido -->
                    @can('admin.cargos.update')
                        <flux:modal name="editar-cargo-{{ $cargo->id }}" class="md:w-96">
                            <form action="{{ route('admin.cargos.update', $cargo) }}" method="POST" class="space-y-6">
                                @csrf
                                @method('PUT')
                                <div>
                                    <flux:heading size="lg">Editar Cargo</flux:heading>
                                    <flux:subheading>Modifica los datos del cargo.</flux:subheading>
                                </div>

                                <!-- Selector de Área -->
                                <div class="flex flex-col gap-1.5">
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Área</label>
                                    <select name="area_id" required class="w-full rounded-lg border-gray-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white text-sm shadow-sm focus:ring-indigo-500 focus:border-indigo-500 px-3 py-2">
                                        <option value="">Seleccione un área</option>
                                        @foreach ($areas as $area)
                                            <option value="{{ $area->id }}" {{ $cargo->area_id == $area->id ? 'selected' : '' }}>
                                                {{ $area->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <flux:input label="Nombre del Cargo" name="nombre" value="{{ old('nombre', $cargo->nombre) }}" required />

                                <!-- Toggle Switch ON/OFF -->
                                <div class="flex flex-col gap-1.5">
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Estado</label>
                                    <div class="relative inline-block w-28 select-none transition duration-250 ease-in">
                                        <input type="checkbox" name="estado" value="1" id="toggle-edit-cargo-{{ $cargo->id }}" {{ $cargo->estado ? 'checked' : '' }} class="toggle-checkbox absolute block w-7 h-7 rounded-full bg-white border-4 appearance-none cursor-pointer z-10 top-0.5 left-0.5 transition-all duration-300 shadow-md checked:translate-x-[72px]" />
                                        <label for="toggle-edit-cargo-{{ $cargo->id }}" class="toggle-label block overflow-hidden h-8 rounded-full cursor-pointer transition-colors duration-300 shadow-inner relative">
                                            <span class="text-on absolute text-white text-xs font-bold tracking-wider left-3.5 top-2 select-none">ON</span>
                                            <span class="text-off absolute text-white text-xs font-bold tracking-wider right-3.5 top-2 select-none">OFF</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="flex justify-end gap-2">
                                    <flux:modal.close>
                                        <flux:button variant="subtle">Cancelar</flux:button>
                                    </flux:modal.close>
                                    <flux:button type="submit" variant="primary">Actualizar</flux:button>
                                </div>
                            </form>
                        </flux:modal>
                    @endcan

                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">No hay cargos registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($cargos->hasPages())
        <div class="px-3 mt-4 flex justify-between items-center">
            <div class="text-gray-600 dark:text-gray-400 text-sm">
                Mostrando
                <span class="font-semibold">{{ $cargos->firstItem() }}</span>
                al
                <span class="font-semibold">{{ $cargos->lastItem() }}</span>
                de
                <span class="font-semibold">{{ $cargos->total() }}</span>
                resultados.
            </div>
            <div>
                {{ $cargos->links() }}
            </div>
        </div>
    @endif

    <!-- Modal de Creación protegido -->
    @can('admin.cargos.store')
        <flux:modal name="crear-cargo" class="md:w-96">
            <form action="{{ route('admin.cargos.store') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <flux:heading size="lg">Nuevo Cargo</flux:heading>
                    <flux:subheading>Registra un nuevo puesto de trabajo.</flux:subheading>
                </div>

                <!-- Selector de Área -->
                <div class="flex flex-col gap-1.5">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Área</label>
                    <select name="area_id" required class="w-full rounded-lg border-gray-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white text-sm shadow-sm focus:ring-indigo-500 focus:border-indigo-500 px-3 py-2">
                        <option value="">Seleccione un área</option>
                        @foreach ($areas as $area)
                            <option value="{{ $area->id }}">{{ $area->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <flux:input label="Nombre del Cargo" name="nombre" placeholder="Ej: Desarrollador Backend" required />

                <!-- Toggle Switch ON/OFF -->
                <div class="flex flex-col gap-1.5">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Estado</label>
                    <div class="relative inline-block w-28 select-none transition duration-250 ease-in">
                        <input type="checkbox" name="estado" value="1" id="toggle-nuevo-cargo" checked class="toggle-checkbox absolute block w-7 h-7 rounded-full bg-white border-4 appearance-none cursor-pointer z-10 top-0.5 left-0.5 transition-all duration-300 shadow-md checked:translate-x-[72px]" />
                        <label for="toggle-nuevo-cargo" class="toggle-label block overflow-hidden h-8 rounded-full cursor-pointer transition-colors duration-300 shadow-inner relative">
                            <span class="text-on absolute text-white text-xs font-bold tracking-wider left-3.5 top-2 select-none">ON</span>
                            <span class="text-off absolute text-white text-xs font-bold tracking-wider right-3.5 top-2 select-none">OFF</span>
                        </label>
                    </div>
                </div>

                <div class="flex justify-end gap-2">
                    <flux:modal.close>
                        <flux:button variant="subtle">Cancelar</flux:button>
                    </flux:modal.close>
                    <flux:button type="submit" variant="primary">Guardar</flux:button>
                </div>
            </form>
        </flux:modal>
    @endcan

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
