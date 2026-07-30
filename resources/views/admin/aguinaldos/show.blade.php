<x-layouts::app title="Detalle del pago">
    <div class="relative mb-6 w-full flex justify-between items-center">
        <div>
            <flux:heading size="xl" level="1">
                Detalles del pago de:  {{ $aguinaldo->tipo }}
            </flux:heading>
            <flux:subheading>Información completa del registro de aguinaldo o doble aguinaldo.</flux:subheading>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.aguinaldos.print', $aguinaldo->id) }}" target="_blank">
                <flux:button variant="primary" icon="printer">Imprimir</flux:button>
            </a>
            <a href="{{ route('admin.aguinaldos.index', ['gestion' => $aguinaldo->gestion, 'tipo' => $aguinaldo->tipo]) }}">
                <flux:button variant="subtle" icon="arrow-left">Volver</flux:button>
            </a>
        </div>
    </div>

    <flux:separator variant="subtle" class="mb-6" />

    <div class="rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-6 shadow-sm w-full">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <div class="flex flex-col gap-4 border-b md:border-b-0 md:border-r border-gray-200 dark:border-zinc-700 pb-4 md:pb-0 md:pr-6">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Información del Empleado</h3>

                <div>
                    <span class="block text-xs text-gray-500">Nombre Completo</span>
                    <span class="text-base font-semibold text-gray-900 dark:text-white">
                        {{ $aguinaldo->empleado->nombre ?? 'N/D' }} {{ $aguinaldo->empleado->apellido ?? '' }}
                    </span>
                </div>

                <div>
                    <span class="block text-xs text-gray-500">Cédula de Identidad (CI)</span>
                    <span class="text-sm font-mono text-gray-800 dark:text-zinc-200">
                        {{ $aguinaldo->empleado->ci ?? 'N/D' }}
                    </span>
                </div>

                <div>
                    <span class="block text-xs text-gray-500">Área / Departamento</span>
                    <span class="text-sm text-gray-800 dark:text-zinc-200">
                        {{ $aguinaldo->empleado->area->nombre ?? 'N/D' }}
                    </span>
                </div>
            </div>

            <div class="flex flex-col gap-4 border-b md:border-b-0 md:border-r border-gray-200 dark:border-zinc-700 pb-4 md:pb-0 md:pr-6">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Parámetros de Cálculo</h3>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="block text-xs text-gray-500">Tipo de Beneficio</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400 mt-1">
                            {{ $aguinaldo->tipo }}
                        </span>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-500">Gestión (Año)</span>
                        <span class="text-sm font-bold text-gray-900 dark:text-white">
                            {{ $aguinaldo->gestion }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="block text-xs text-gray-500">Último Salario</span>
                        <span class="text-sm font-mono text-gray-800 dark:text-zinc-200">
                            {{ $simboloMoneda ?? 'Bs.' }} {{ number_format($aguinaldo->ultimo_salario, 2) }}
                        </span>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-500">Meses / Días Trabajados</span>
                        <span class="text-sm text-gray-800 dark:text-zinc-200">
                            {{ $aguinaldo->meses_trabajados }} meses ({{ $aguinaldo->dias_trabajados }} días)
                        </span>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-4">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Estado Financiero</h3>

                <div>
                    <span class="block text-xs text-gray-500">Monto Final a Pagar</span>
                    <span class="text-2xl font-mono font-bold text-emerald-600 dark:text-emerald-400">
                        {{ $simboloMoneda ?? 'Bs.' }} {{ number_format($aguinaldo->monto_pagar, 2) }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="block text-xs text-gray-500 mb-1">Estado</span>
                        @if($aguinaldo->estado == 'Pagado')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider bg-emerald-500 text-white shadow-sm dark:bg-emerald-600">
                                <i class="fas fa-check-circle"></i> Pagado
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider bg-amber-500 text-white shadow-sm dark:bg-amber-600">
                                <i class="fas fa-clock"></i> Pendiente
                            </span>
                        @endif
                    </div>
                    <div>
                        <span class="block text-xs text-gray-500 mb-1">Fecha de Pago</span>
                        <span class="text-sm font-semibold text-gray-800 dark:text-zinc-200">
                            {{ $aguinaldo->fecha_pago ? \Carbon\Carbon::parse($aguinaldo->fecha_pago)->format('d/m/Y') : 'No registrado' }}
                        </span>
                    </div>
                </div>

                @if($aguinaldo->observaciones)
                <div class="mt-2">
                    <span class="block text-xs text-gray-500">Observaciones</span>
                    <span class="text-sm text-gray-700 dark:text-zinc-300 italic">
                        "{{ $aguinaldo->observaciones }}"
                    </span>
                </div>
                @endif
            </div>

        </div>

    </div>
</x-layouts::app>
