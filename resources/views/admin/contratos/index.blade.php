<x-layouts::app title="Contratos">
    <div class="space-y-6">
        <!-- Cabecera de la sección -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-zinc-100">Gestión de Contratos</h1>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Administra los contratos laborales del personal y sus archivos adjuntos.</p>
            </div>
            <div>
                <flux:button variant="primary" href="{{ route('admin.contratos.create') }}" icon="plus">
                    Nuevo Contrato
                </flux:button>
            </div>
        </div>

        <!-- Alertas de éxito o error -->
        @if (session('success'))
            <flux:callout variant="success" icon="check-circle">
                {{ session('success') }}
            </flux:callout>
        @endif

        @if (session('error'))
            <flux:callout variant="danger" icon="exclamation-triangle">
                {{ session('error') }}
            </flux:callout>
        @endif

        <!-- Filtros y Búsqueda -->
        <div class="bg-white dark:bg-zinc-900 p-4 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm">
            <form method="GET" action="{{ route('admin.contratos.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-2">
                    <flux:input
                        name="search"
                        value="{{ $search ?? '' }}"
                        placeholder="Buscar por nombre, apellido, CI o cargo..."
                        icon="magnifying-glass"
                        clearable
                    />
                </div>
                <div>
                    <flux:select name="estado" placeholder="Filtrar por estado" onchange="this.form.submit()">
                        <option value="">Todos los estados</option>
                        <option value="Activo" {{ ($estado ?? '') == 'Activo' ? 'selected' : '' }}>Activo</option>
                        <option value="Finalizado" {{ ($estado ?? '') == 'Finalizado' ? 'selected' : '' }}>Finalizado</option>
                        <option value="Cancelado" {{ ($estado ?? '') == 'Cancelado' ? 'selected' : '' }}>Cancelado</option>
                    </flux:select>
                </div>
            </form>
        </div>

        <!-- Tabla de Contratos -->
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-800/50 text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase tracking-wider">
                            <th class="py-3 px-4">Empleado</th>
                            <th class="py-3 px-4">Tipo / Cargo</th>
                            <th class="py-3 px-4">Vigencia</th>
                            <th class="py-3 px-4">Salario</th>
                            <th class="py-3 px-4">Estado</th>
                            <th class="py-3 px-4">Imprimir</th>
                            <th class="py-3 px-4 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800 text-sm text-zinc-700 dark:text-zinc-300">
                        @forelse ($contratos as $contrato)
                            <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-800/50 transition-colors">
                                <td class="py-3 px-4 font-medium text-zinc-900 dark:text-zinc-100">
                                    {{ $contrato->empleado->nombre ?? 'N/A' }} {{ $contrato->empleado->apellido ?? '' }}
                                    <div class="text-xs text-zinc-500">CI: {{ $contrato->empleado->ci ?? 'N/A' }}</div>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="font-medium text-zinc-900 dark:text-zinc-100">{{ $contrato->cargo_contrato }}</div>
                                    <div class="text-xs text-zinc-500">{{ $contrato->tipo_contrato }}</div>
                                </td>
                                <td class="py-3 px-4 text-xs">
                                    <div><span class="font-semibold">Inicio:</span> {{ \Carbon\Carbon::parse($contrato->fecha_inicio)->format('d/m/Y') }}</div>
                                    <div><span class="font-semibold">Fin:</span> {{ $contrato->fecha_fin ? \Carbon\Carbon::parse($contrato->fecha_fin)->format('d/m/Y') : 'Indefinido / Labor determinada' }}</div>
                                </td>
                                <td class="py-3 px-4 font-medium">
                                    Bs. {{ number_format($contrato->salario_mensual, 2, ',', '.') }}
                                </td>
                                <td class="py-3 px-4">
                                    @php
                                        $badgeVariant = match($contrato->estado) {
                                            'Activo' => 'success',
                                            'Finalizado' => 'warning',
                                            default => 'danger',
                                        };
                                    @endphp
                                    <flux:badge variant="{{ $badgeVariant }}" size="sm">{{ $contrato->estado }}</flux:badge>
                                </td>
                                <td class="py-3 px-4">
                                    <flux:button variant="ghost" size="sm" href="{{ route('admin.contratos.imprimir', $contrato->id) }}" icon="printer" target="_blank">
                                        Ver Contrato
                                    </flux:button>
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <flux:button variant="ghost" size="sm" href="{{ route('admin.contratos.show', $contrato->id) }}" icon="eye" />
                                        <flux:button variant="ghost" size="sm" href="{{ route('admin.contratos.edit', $contrato->id) }}" icon="pencil-square" />

                                        <form action="{{ route('admin.contratos.destroy', $contrato->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este contrato?');">
                                            @csrf
                                            @method('DELETE')
                                            <flux:button variant="ghost" size="sm" type="submit" icon="trash" class="text-red-600 hover:text-red-700" />
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-zinc-500 dark:text-zinc-400">
                                    No se encontraron contratos registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="p-4 border-t border-zinc-200 dark:border-zinc-800">
                {{ $contratos->withQueryString()->links() }}
            </div>
        </div>
    </div>
</x-layouts::app>
