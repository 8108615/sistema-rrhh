<x-layouts::app title="Gestión de Planillas">
    @php
        $simboloMoneda = $ajuste->divisa ?? 'Bs.';
    @endphp

    <div class="relative mb-6 w-full flex justify-between items-center">
        <div>
            <flux:heading size="xl" level="1">Planillas de Sueldos</flux:heading>
            <flux:subheading>Historial y control de pagos de sueldos al personal.</flux:subheading>
        </div>
        <!-- Botón para redireccionar a la vista de creación protegido -->
        @can('admin.planillas.create')
            <div>
                <a href="{{ route('admin.planillas.create') }}">
                    <flux:button variant="primary" icon="plus">Generar Planilla</flux:button>
                </a>
            </div>
        @endcan
    </div>

    <flux:separator variant="subtle" class="mb-6" />

    <!-- Alertas de éxito -->
    @if (session('success'))
        <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 rounded-xl text-sm flex items-center gap-2">
            <flux:icon name="check-circle" class="h-5 w-5 text-emerald-500 flex-shrink-0" />
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Barra de búsqueda y filtros -->
    <div class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl p-4 shadow-sm mb-6">
        <form method="GET" action="{{ route('admin.planillas.index') }}" class="flex flex-col sm:flex-row gap-4 justify-between items-center">
            <div class="w-full sm:w-96">
                <flux:input
                    type="text"
                    name="buscar"
                    value="{{ $buscar ?? '' }}"
                    placeholder="Buscar por mes, año o estado..."
                    icon="magnifying-glass"
                    clearable
                />
            </div>
            <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                <flux:button type="submit" variant="subtle">Buscar</flux:button>
                @if(!empty($buscar))
                    <a href="{{ route('admin.planillas.index') }}">
                        <flux:button variant="ghost">Limpiar</flux:button>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tabla de Planillas -->
    <div class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-900/50 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        <th class="py-3.5 px-6">NRO</th>
                        <th class="py-3.5 px-6">Mes y Año</th>
                        <th class="py-3.5 px-6">Nro. Empleados</th>
                        <th class="py-3.5 px-6">Total Pagado</th>
                        <th class="py-3.5 px-6">Estado</th>
                        <th class="py-3.5 px-6 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-zinc-700 text-sm text-gray-700 dark:text-gray-300">
                    @forelse ($planillas as $planilla)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-zinc-700/50 transition">
                            <td class="py-4 px-6 font-medium text-gray-900 dark:text-white">
                                {{ $loop->iteration + ($planillas->currentPage() - 1) * $planillas->perPage() }}
                            </td>
                            <td class="py-4 px-6 font-semibold text-gray-900 dark:text-white">
                                {{ $planilla->mes }} de {{ $planilla->anio }}
                            </td>
                            <td class="py-4 px-6 text-gray-600 dark:text-gray-400">
                                <span class="px-2.5 py-1 bg-gray-100 dark:bg-zinc-700 rounded-md text-xs font-medium">
                                    {{ $planilla->detalles_count ?? $planilla->detalles->count() }} emp.
                                </span>
                            </td>
                            <td class="py-4 px-6 font-semibold text-emerald-600 dark:text-emerald-400">
                                {{ number_format($planilla->total_pagado, 2, ',', '.') }} {{ $simboloMoneda }}
                            </td>
                            <td class="py-4 px-6">
                                @if ($planilla->estado == 'Pagado')
                                    <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-emerald-500 text-white">Pagado</span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-amber-500 text-white">Pendiente</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center space-x-2">
                                <div class="flex justify-center items-center gap-1.5">
                                    <!-- Botón Pagar (Solo si está pendiente) protegido -->
                                    @if ($planilla->estado != 'Pagado')
                                        @can('admin.planillas.pagar')
                                            <form action="{{ route('admin.planillas.pagar', $planilla->id) }}" method="POST" class="inline-block pagar-form" id="miFormularioPagarPlanilla{{ $planilla->id }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-medium rounded-md shadow-sm transition cursor-pointer" onclick="preguntarPagarPlanilla{{ $planilla->id }}(event)">
                                                    <i class="fas fa-check-circle mr-1.5"></i> Pagar
                                                </button>
                                            </form>

                                            <script>
                                                function preguntarPagarPlanilla{{ $planilla->id }}(event) {
                                                    event.preventDefault();
                                                    Swal.fire({
                                                        title: '¿Confirmar pago de planilla?',
                                                        text: "Esta acción cambiará el estado de la planilla a 'Pagado'.",
                                                        icon: 'question',
                                                        showCancelButton: true,
                                                        confirmButtonColor: '#10b981',
                                                        cancelButtonColor: '#6b7280',
                                                        confirmButtonText: 'Sí, marcar como pagado',
                                                        cancelButtonText: 'Cancelar'
                                                    }).then((result) => {
                                                        if (result.isConfirmed) {
                                                            document.getElementById('miFormularioPagarPlanilla{{ $planilla->id }}').submit();
                                                        }
                                                    });
                                                }
                                            </script>
                                        @endcan
                                    @endif

                                    <!-- Botón Ver Detalle protegido -->
                                    @can('admin.planillas.show')
                                        <a href="{{ route('admin.planillas.show', $planilla->id) }}" class="inline-flex items-center px-3 py-1.5 bg-sky-500 hover:bg-sky-600 text-white text-xs font-medium rounded-md shadow-sm transition">
                                            <i class="fas fa-eye mr-1.5"></i> Ver
                                        </a>
                                    @endcan

                                    <!-- Botón PDF / Imprimir protegido -->
                                    @can('admin.planillas.pdf')
                                        <a href="{{ route('admin.planillas.pdf', $planilla->id) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-indigo-500 hover:bg-indigo-600 text-white text-xs font-medium rounded-md shadow-sm transition">
                                            <i class="fas fa-print mr-1.5"></i> PDF
                                        </a>
                                    @endcan

                                    <!-- Botón Eliminar protegido -->
                                    @can('admin.planillas.destroy')
                                        <form action="{{ route('admin.planillas.destroy', $planilla->id) }}" method="POST" class="inline-block delete-form" id="miFormularioEliminarPlanilla{{ $planilla->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs font-medium rounded-md shadow-sm transition cursor-pointer delete-btn" onclick="preguntarEliminarPlanilla{{ $planilla->id }}(event)">
                                                <i class="fas fa-trash-alt mr-1.5"></i> Eliminar
                                            </button>
                                        </form>

                                        <script>
                                            function preguntarEliminarPlanilla{{ $planilla->id }}(event) {
                                                event.preventDefault();
                                                Swal.fire({
                                                    title: '¿Estás seguro?',
                                                    text: "¡Esta acción eliminará la planilla y todos sus detalles asociados!",
                                                    icon: 'warning',
                                                    showCancelButton: true,
                                                    confirmButtonColor: '#ef4444',
                                                    cancelButtonColor: '#6b7280',
                                                    confirmButtonText: 'Sí, eliminar',
                                                    cancelButtonText: 'Cancelar'
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        document.getElementById('miFormularioEliminarPlanilla{{ $planilla->id }}').submit();
                                                    }
                                                });
                                            }
                                        </script>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-500 dark:text-gray-400">
                                No se encontraron registros de planillas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        @if ($planillas->hasPages())
            <div class="p-4 border-t border-gray-200 dark:border-zinc-700">
                {{ $planillas->links() }}
            </div>
        @endif
    </div>

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
