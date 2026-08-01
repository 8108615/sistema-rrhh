<x-layouts::app title="Historial de Pagos a Empleados">
    <div class="relative mb-6 w-full flex justify-between items-center">
        <div>
            <flux:heading size="xl" level="1">Pagos y Boletas de Sueldo</flux:heading>
            <flux:subheading>Historial de desembolsos realizados al personal.</flux:subheading>
        </div>
        <div>
            <a href="{{ route('admin.pagos.create') }}">
                <flux:button variant="primary" icon="plus" color="blue">Nuevo Pago</flux:button>
            </a>
        </div>
    </div>

    <!-- Buscador -->
    <div class="flex gap-4 mb-6">
        <div class="flex-1">
            <form action="{{ route('admin.pagos.index') }}" method="GET" class="flex gap-2 w-1/2">
                <div class="flex-1">
                    <flux:input name="buscar" type="text" icon="magnifying-glass" placeholder="Buscar por empleado, mes, año o comprobante..."
                        value="{{ $buscar ?? '' }}" />
                </div>
                <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition flex items-center gap-2 cursor-pointer">
                    <i class="fas fa-search"></i> Buscar
                </button>
                @if (isset($buscar) && $buscar != '')
                    <a href="{{ route('admin.pagos.index') }}" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
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
                    <th class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 uppercase">Nro</th>
                    <th class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 uppercase">Nro Comprobante</th>
                    <th class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 uppercase">Empleado</th>
                    <th class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 uppercase">Área</th>
                    <th class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 uppercase">Periodo</th>
                    <th class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 uppercase">Fecha Pago</th>
                    <th class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 uppercase">Total Líquido ({{ $simboloMoneda }})</th>
                    <th class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 uppercase">Método</th>
                    <th class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-zinc-800 text-center">
                @forelse ($pagos as $pago)
                    <tr class="hover:bg-blue-50 dark:hover:bg-zinc-700/50 transition">
                        <td class="px-3 py-3 border border-gray-200 dark:border-zinc-700 text-sm">{{ ($pagos->currentPage() - 1) * $pagos->perPage() + $loop->iteration }}</td>
                        <td class="px-4 py-3 border border-gray-200 dark:border-zinc-700 text-sm font-semibold text-gray-700 dark:text-zinc-300">
                            {{ $pago->nro_comprobante ?? 'S/N' }}
                        </td>
                        <td class="px-4 py-3 border border-gray-200 dark:border-zinc-700 text-sm text-left font-semibold">
                            {{ $pago->empleado->nombre ?? 'N/A' }} {{ $pago->empleado->apellido ?? '' }}
                            <div class="text-xs text-gray-500 font-normal">CI: {{ $pago->empleado->ci ?? 'N/A' }}</div>
                        </td>
                        <td class="px-4 py-3 border border-gray-200 dark:border-zinc-700 text-sm text-gray-600 dark:text-zinc-400">
                            {{ $pago->empleado->area->nombre ?? $pago->empleado->area ?? 'N/A' }}
                        </td>
                        <td class="px-4 py-3 border border-gray-200 dark:border-zinc-700 text-sm font-medium">{{ $pago->mes }} / {{ $pago->anio }}</td>
                        <td class="px-4 py-3 border border-gray-200 dark:border-zinc-700 text-sm">{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 border border-gray-200 dark:border-zinc-700 text-sm font-bold text-emerald-600 dark:text-emerald-400">
                            {{ number_format($pago->total_pagar, 2, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 border border-gray-200 dark:border-zinc-700 text-sm">
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-indigo-100 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300">{{ $pago->metodo_pago }}</span>
                        </td>
                        <td class="px-4 py-3 border border-gray-200 dark:border-zinc-700">
                            <div class="flex justify-center items-center gap-1.5">
                                <!-- Ver / Boleta -->
                                <a href="{{ route('admin.pagos.show', $pago->id) }}" class="px-3 py-1.5 bg-sky-500 hover:bg-sky-600 text-white text-xs font-medium rounded-md shadow-sm transition flex items-center">
                                    <i class="fas fa-eye mr-1"></i> Ver
                                </a>

                                <!-- Imprimir Individual (Usa tu ruta print existente) -->
                                <a href="{{ route('admin.pagos.print', $pago->id) }}" target="_blank" class="px-3 py-1.5 bg-zinc-600 hover:bg-zinc-700 text-white text-xs font-medium rounded-md shadow-sm transition flex items-center">
                                    <i class="fas fa-print mr-1"></i> Imprimir
                                </a>

                                <!-- Editar -->
                                <a href="{{ route('admin.pagos.edit', $pago->id) }}" class="px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-medium rounded-md shadow-sm transition flex items-center">
                                    <i class="fas fa-edit mr-1"></i> Editar
                                </a>

                                <!-- Eliminar con confirmación de SweetAlert local -->
                                <form action="{{ route('admin.pagos.destroy', $pago->id) }}" method="POST" class="inline-flex" id="formEliminarPago{{ $pago->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs font-medium rounded-md shadow-sm transition cursor-pointer flex items-center" onclick="confirmarEliminacion{{ $pago->id }}(event)">
                                        <i class="fas fa-trash-alt mr-1"></i> Eliminar
                                    </button>
                                </form>
                                <script>
                                    function confirmarEliminacion{{ $pago->id }}(e) {
                                        e.preventDefault();
                                        Swal.fire({
                                            title: '¿Eliminar registro de pago?',
                                            text: "¡No podrás revertir esta acción!",
                                            icon: 'warning',
                                            showCancelButton: true,
                                            confirmButtonColor: '#d33',
                                            cancelButtonColor: '#3085d6',
                                            confirmButtonText: 'Sí, eliminar',
                                            cancelButtonText: 'Cancelar'
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                document.getElementById('formEliminarPago{{ $pago->id }}').submit();
                                            }
                                        });
                                    }
                                </script>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-6 py-6 text-center text-sm text-gray-500 dark:text-zinc-400">No hay pagos registrados en el sistema.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($pagos->hasPages())
        <div class="mt-4">{{ $pagos->appends(['buscar' => $buscar])->links() }}</div>
    @endif
</x-layouts::app>
