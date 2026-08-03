<x-layouts::app title="Contratos">
    @php
        $ajusteDivisa = \App\Models\Ajuste::first()->divisas ?? 'BOB';
        $jsonPath = public_path('divisas.json');
        $simboloMoneda = $ajusteDivisa;
        if (file_exists($jsonPath)) {
            $divisasData = json_decode(file_get_contents($jsonPath), true);
            if (isset($divisasData[$ajusteDivisa]['symbol'])) {
                $simboloMoneda = $divisasData[$ajusteDivisa]['symbol'];
            }
        }
    @endphp

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
                        <option value="Vencido" {{ ($estado ?? '') == 'Vencido' ? 'selected' : '' }}>Vencido</option>
                        <option value="Finalizado" {{ ($estado ?? '') == 'Finalizado' ? 'selected' : '' }}>Finalizado</option>
                        <option value="Anulado" {{ ($estado ?? '') == 'Anulado' ? 'selected' : '' }}>Anulado</option>
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
                                <td class="py-3 px-4 font-medium font-mono">
                                    {{ $simboloMoneda }} {{ number_format($contrato->salario_mensual, 2, ',', '.') }}
                                </td>
                                <td class="py-3 px-4">
                                    @php
                                        $customBg = match($contrato->estado) {
                                            'Activo' => 'bg-emerald-500 text-white border border-emerald-600',
                                            'Vencido' => 'bg-red-500 text-white border border-red-600',
                                            'Finalizado' => 'bg-amber-500 text-white border border-amber-600',
                                            'Anulado' => 'bg-red-500 text-white border border-red-600',
                                            default => 'bg-red-500 text-white border border-red-600',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center justify-center px-3 py-1 text-xs font-semibold rounded-full {{ $customBg }}">
                                        {{ $contrato->estado }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <!-- Botón Ver Contrato (Naranja) -->
                                    <flux:button variant="primary" size="sm" href="{{ route('admin.contratos.imprimir', $contrato->id) }}" icon="printer" target="_blank" class="bg-amber-500 hover:bg-amber-600">
                                        Ver Contrato
                                    </flux:button>
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <div class="flex items-center justify-end gap-2">

                                        <!-- Botón Ver (Azul) -->
                                        <flux:button variant="primary" size="sm" href="{{ route('admin.contratos.show', $contrato->id) }}" icon="eye" class="bg-blue-500 hover:bg-blue-600">
                                            Ver
                                        </flux:button>

                                        <!-- Botón Editar (Verde) -->
                                        <flux:button variant="primary" size="sm" href="{{ route('admin.contratos.edit', $contrato->id) }}" icon="pencil-square" class="bg-emerald-600 hover:bg-emerald-700">
                                            Editar
                                        </flux:button>

                                        <!-- Formulario y Botón Eliminar con SweetAlert2 -->
                                        <form action="{{ route('admin.contratos.destroy', $contrato->id) }}" method="POST" id="delete-form-{{ $contrato->id }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <flux:button variant="danger" size="sm" type="button" icon="trash" onclick="confirmDelete({{ $contrato->id }})">
                                                Eliminar
                                            </flux:button>
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

    <!-- Script de confirmación con SweetAlert2 -->
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, ¡eliminar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            })
        }
    </script>
</x-layouts::app>
