<x-layouts::app title="Gestión RC-IVA (F-110)">
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
                <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-zinc-100">Gestión de RC-IVA (Formulario 110)</h1>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Administra los descargos de facturas y retenciones de impuestos del personal.</p>
            </div>
            <div>
                <flux:button variant="primary" href="{{ route('admin.rc_iva.create') }}" icon="plus">
                    Nuevo Descargo / Registro
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
            <form method="GET" action="{{ route('admin.rc_iva.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-2">
                    <flux:input
                        name="search"
                        value="{{ $search ?? '' }}"
                        placeholder="Buscar por nombre, apellido o CI del empleado..."
                        icon="magnifying-glass"
                        clearable
                    />
                </div>
                <div>
                    <flux:input
                        name="periodo"
                        value="{{ $periodo ?? '' }}"
                        placeholder="Filtrar por periodo (Ej: 2026-08)"
                        icon="calendar"
                        clearable
                    />
                </div>
            </form>
        </div>

        <!-- Tabla de Registros RC-IVA -->
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-800/50 text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase tracking-wider">
                            <th class="py-3 px-4">Empleado</th>
                            <th class="py-3 px-4">Periodo</th>
                            <th class="py-3 px-4">Sueldo Neto</th>
                            <th class="py-3 px-4">Total Facturas (F110)</th>
                            <th class="py-3 px-4">Impuesto Retenido</th>
                            <th class="py-3 px-4">Saldo Favor Dependiente</th>
                            <th class="py-3 px-4 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800 text-sm text-zinc-700 dark:text-zinc-300">
                        @forelse ($formularios as $item)
                            <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-800/50 transition-colors">
                                <td class="py-3 px-4 font-medium text-zinc-900 dark:text-zinc-100">
                                    {{ $item->empleado->nombre ?? 'N/A' }} {{ $item->empleado->apellido ?? '' }}
                                    <div class="text-xs text-zinc-500">CI: {{ $item->empleado->ci ?? 'N/A' }}</div>
                                </td>
                                <td class="py-3 px-4 font-semibold">
                                    {{ $item->periodo_mes }}
                                </td>
                                <td class="py-3 px-4 font-mono">
                                    {{ $simboloMoneda }} {{ number_format($item->sueldo_neto, 2, ',', '.') }}
                                </td>
                                <td class="py-3 px-4 font-mono text-emerald-600 dark:text-emerald-400">
                                    {{ $simboloMoneda }} {{ number_format($item->total_facturas_presentadas, 2, ',', '.') }}
                                </td>
                                <td class="py-3 px-4 font-mono text-red-600 dark:text-red-400">
                                    {{ $simboloMoneda }} {{ number_format($item->impuesto_retenido_fisco, 2, ',', '.') }}
                                </td>
                                <td class="py-3 px-4 font-mono text-blue-600 dark:text-blue-400">
                                    {{ $simboloMoneda }} {{ number_format($item->saldo_a_favor_dependiente, 2, ',', '.') }}
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Formulario y Botón Eliminar con SweetAlert2 -->
                                        <form action="{{ route('admin.rc_iva.destroy', $item->id) }}" method="POST" id="delete-form-{{ $item->id }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <flux:button variant="danger" size="sm" type="button" icon="trash" onclick="confirmDelete({{ $item->id }})">
                                                Eliminar
                                            </flux:button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-zinc-500 dark:text-zinc-400">
                                    No se encontraron registros de RC-IVA.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="p-4 border-t border-zinc-200 dark:border-zinc-800">
                {{ $formularios->withQueryString()->links() }}
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