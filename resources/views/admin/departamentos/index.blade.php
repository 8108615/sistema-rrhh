<x-layouts::app title="Gestión de Departamentos">
    <div class="relative mb-6 w-full flex justify-between items-center">
        <div>
            <flux:heading size="xl" level="1">Departamentos</flux:heading>
            <flux:subheading>Administración de departamentos y regiones del país.</flux:subheading>
        </div>
        <!-- Botón para abrir modal de creación protegido con permiso -->
        @can('admin.departamentos.store')
            <flux:modal.trigger name="crear-departamento">
                <flux:button variant="primary" icon="plus" color="blue">Nuevo Departamento</flux:button>
            </flux:modal.trigger>
        @endcan
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
    </style>

    <!-- Tabla de Departamentos -->
    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 shadow-sm">
        <table class="min-w-full border-collapse">
            <thead class="bg-gray-50 dark:bg-zinc-900 text-center">
                <tr>
                    <th class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Nro
                    </th>
                    <th class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Nombre
                    </th>
                    <th class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Sigla
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
                @forelse ($departamentos as $dep)
                    <tr class="even:bg-slate-50 odd:bg-white dark:even:bg-zinc-700/20 dark:odd:bg-zinc-800 hover:bg-blue-50 dark:hover:bg-zinc-700/50 transition">
                        <td class="px-3 py-3 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 text-center">
                            {{ ($departamentos->currentPage() - 1) * $departamentos->perPage() + $loop->iteration }}
                        </td>
                        <td class="px-4 py-3 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                            {{ $dep->nombre }}
                        </td>
                        <td class="px-4 py-3 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                            {{ $dep->sigla ?? '-' }}
                        </td>
                        <td class="px-4 py-3 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-center text-sm">
                            @if ($dep->estado)
                                <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-emerald-500 text-white border border-emerald-200">Activo</span>
                            @else
                                <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-red-500 text-white border border-red-200">Inactivo</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-center">
                            <div class="flex justify-center items-center gap-1.5">
                                <!-- Botón Editar protegido -->
                                @can('admin.departamentos.update')
                                    <flux:modal.trigger name="editar-departamento-{{ $dep->id }}">
                                        <button type="button" class="inline-flex items-center px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-medium rounded-md shadow-sm transition">
                                            <i class="fas fa-pencil-alt mr-1.5"></i> Editar
                                        </button>
                                    </flux:modal.trigger>
                                @endcan

                                <!-- Botón Eliminar protegido -->
                                @can('admin.departamentos.destroy')
                                    <form action="{{ route('admin.departamentos.destroy', $dep) }}" method="POST" class="inline-flex" id="miFormularioEliminarDep{{ $dep->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs font-medium rounded-md shadow-sm transition cursor-pointer" onclick="preguntarEliminarDep{{ $dep->id }}(event)">
                                            <i class="fas fa-trash-alt mr-1.5"></i> Eliminar
                                        </button>
                                    </form>

                                    <script>
                                        function preguntarEliminarDep{{ $dep->id }}(event) {
                                            event.preventDefault();
                                            Swal.fire({
                                                title: '¿Desea eliminar este departamento?',
                                                icon: 'question',
                                                showDenyButton: true,
                                                confirmButtonText: 'Eliminar',
                                                confirmButtonColor: '#a5161d',
                                                denyButtonColor: '#270a0a',
                                                denyButtonText: 'Cancelar',
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    document.getElementById('miFormularioEliminarDep{{ $dep->id }}').submit();
                                                }
                                            });
                                        }
                                    </script>
                                @endcan
                            </div>
                        </td>
                    </tr>

                    <!-- Modal de Edición protegido (solo se renderiza si tiene permiso de actualizar) -->
                    @can('admin.departamentos.update')
                        <flux:modal name="editar-departamento-{{ $dep->id }}" class="md:w-96">
                            <form action="{{ route('admin.departamentos.update', $dep) }}" method="POST" class="space-y-6">
                                @csrf
                                @method('PUT')
                                <div>
                                    <flux:heading size="lg">Editar Departamento</flux:heading>
                                    <flux:subheading>Modifica los datos del departamento.</flux:subheading>
                                </div>

                                <flux:input label="Nombre" name="nombre" value="{{ old('nombre', $dep->nombre) }}" required />
                                <flux:input label="Sigla" name="sigla" value="{{ old('sigla', $dep->sigla) }}" placeholder="Ej: SC" />

                                <!-- Toggle Switch ON/OFF -->
                                <div class="flex flex-col gap-1.5">
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Estado</label>
                                    <div class="relative inline-block w-28 select-none transition duration-250 ease-in">
                                        <input type="checkbox" name="estado" value="1" id="toggle-edit-{{ $dep->id }}" {{ $dep->estado ? 'checked' : '' }} class="toggle-checkbox absolute block w-7 h-7 rounded-full bg-white border-4 appearance-none cursor-pointer z-10 top-0.5 left-0.5 transition-all duration-300 shadow-md checked:translate-x-[72px]" />
                                        <label for="toggle-edit-{{ $dep->id }}" class="toggle-label block overflow-hidden h-8 rounded-full cursor-pointer transition-colors duration-300 shadow-inner relative">
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
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">No hay departamentos registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <style>
        nav[role="navigation"] p {
            display: none !important;
        }
    </style>

    @if ($departamentos->hasPages())
        <div class="px-3 mt-4 flex justify-between items-center">
            <div class="text-gray-600 dark:text-gray-400 text-sm">
                Mostrando
                <span class="font-semibold">{{ $departamentos->firstItem() }}</span>
                al
                <span class="font-semibold">{{ $departamentos->lastItem() }}</span>
                de
                <span class="font-semibold">{{ $departamentos->total() }}</span>
                resultados.
            </div>
            <div>
                {{ $departamentos->links() }}
            </div>
        </div>
    @endif

    <!-- Modal de Creación protegido -->
    @can('admin.departamentos.store')
        <flux:modal name="crear-departamento" class="md:w-96">
            <form action="{{ route('admin.departamentos.store') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <flux:heading size="lg">Nuevo Departamento</flux:heading>
                    <flux:subheading>Registra una nueva región o departamento.</flux:subheading>
                </div>

                <flux:input label="Nombre" name="nombre" placeholder="Ej: Santa Cruz" required />
                <flux:input label="Sigla" name="sigla" placeholder="Ej: SC" />

                <!-- Toggle Switch ON/OFF -->
                <div class="flex flex-col gap-1.5">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Estado</label>
                    <div class="relative inline-block w-28 select-none transition duration-250 ease-in">
                        <input type="checkbox" name="estado" value="1" id="toggle-nuevo" checked class="toggle-checkbox absolute block w-7 h-7 rounded-full bg-white border-4 appearance-none cursor-pointer z-10 top-0.5 left-0.5 transition-all duration-300 shadow-md checked:translate-x-[72px]" />
                        <label for="toggle-nuevo" class="toggle-label block overflow-hidden h-8 rounded-full cursor-pointer transition-colors duration-300 shadow-inner relative">
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
