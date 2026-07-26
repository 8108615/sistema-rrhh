<x-layouts::app title="Gestión de Departamentos">
    <div class="relative mb-6 w-full flex justify-between items-center">
        <div>
            <flux:heading size="xl" level="1">Departamentos</flux:heading>
            <flux:subheading>Administración de departamentos y regiones del país.</flux:subheading>
        </div>
        <!-- Botón para abrir modal de creación -->
        <flux:modal.trigger name="crear-departamento">
            <flux:button variant="primary" icon="plus" color="blue">Nuevo Departamento</flux:button>
        </flux:modal.trigger>
    </div>

    <flux:separator variant="subtle" class="mb-6" />



    <!-- Tabla de Departamentos -->
    <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
            <thead class="bg-gray-50 dark:bg-neutral-900">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sigla</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                @forelse ($departamentos as $dep)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">#{{ $dep->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $dep->nombre }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $dep->sigla ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if ($dep->estado)
                                <span class="px-2.5 py-1 inline-flex text-xs font-semibold rounded-full bg-emerald-100 text-emerald-800">Activo</span>
                            @else
                                <span class="px-2.5 py-1 inline-flex text-xs font-semibold rounded-full bg-red-100 text-red-800">Inactivo</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            <!-- Botón Editar -->
                            <flux:modal.trigger name="editar-departamento-{{ $dep->id }}">
                                <flux:button size="sm" variant="ghost" icon="pencil-square">Editar</flux:button>
                            </flux:modal.trigger>

                            <!-- Formulario Eliminar -->
                            <form action="{{ route('admin.departamentos.destroy', $dep) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de eliminar este departamento?');">
                                @csrf
                                @method('DELETE')
                                <flux:button size="sm" variant="ghost" type="submit" icon="trash" class="text-red-600 hover:text-red-900">Eliminar</flux:button>
                            </form>
                        </td>
                    </tr>

                    <!-- Modal de Edición para cada registro -->
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

                            <div class="flex items-center gap-2">
                                <input type="checkbox" name="estado" value="1" id="estado-{{ $dep->id }}" {{ $dep->estado ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <label for="estado-{{ $dep->id }}" class="text-sm font-medium text-gray-700 dark:text-gray-300">Activo</label>
                            </div>

                            <div class="flex justify-end gap-2">
                                <flux:modal.close>
                                    <flux:button variant="subtle">Cancelar</flux:button>
                                </flux:modal.close>
                                <flux:button type="submit" variant="primary">Actualizar</flux:button>
                            </div>
                        </form>
                    </flux:modal>

                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">No hay departamentos registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $departamentos->links() }}
    </div>

    <!-- Modal de Creación -->
    <flux:modal name="crear-departamento" class="md:w-96">
        <form action="{{ route('admin.departamentos.store') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <flux:heading size="lg">Nuevo Departamento</flux:heading>
                <flux:subheading>Registra una nueva región o departamento.</flux:subheading>
            </div>

            <flux:input label="Nombre" name="nombre" placeholder="Ej: Santa Cruz" required />
            <flux:input label="Sigla" name="sigla" placeholder="Ej: SC" />

            <div class="flex items-center gap-2">
                <input type="checkbox" name="estado" value="1" id="estado-nuevo" checked class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                <label for="estado-nuevo" class="text-sm font-medium text-gray-700 dark:text-gray-300">Activo</label>
            </div>

            <div class="flex justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="subtle">Cancelar</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary">Guardar</flux:button>
            </div>
        </form>
    </flux:modal>
</x-layouts::app>