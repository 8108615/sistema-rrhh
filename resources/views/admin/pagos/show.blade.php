<x-layouts::app title="Detalle del Pago">
    <div class="relative mb-6 w-full flex justify-between items-center">
        <div>
            <flux:heading size="xl" level="1">Detalle del Comprobante de Pago</flux:heading>
            <flux:subheading>Información completa del sueldo, desglose y retenciones registradas.</flux:subheading>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.pagos.index') }}">
                <flux:button variant="subtle" icon="arrow-left">Volver al listado</flux:button>
            </a>
            <!-- Botón que redirige a la vista de impresión profesional -->
            <a href="{{ route('admin.pagos.print', $pago->id) }}" target="_blank">
                <flux:button variant="primary" icon="printer">Imprimir Boleta</flux:button>
            </a>
        </div>
    </div>

    <flux:separator variant="subtle" class="mb-6" />

    <!-- Sección 1: Información General del Pago -->
    <div class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl p-6 shadow-sm mb-6">
        <div class="flex items-center gap-2 mb-4 text-blue-600 dark:text-blue-400 font-semibold">
            <flux:icon name="document-text" class="w-5 h-5" />
            <span>Datos Generales del Comprobante</span>
        </div>
        <flux:separator variant="subtle" class="mb-4" />

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
            <div>
                <span class="block text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase">Período (Mes / Año)</span>
                <span class="font-semibold text-gray-800 dark:text-zinc-200 text-base">{{ $pago->mes }} de {{ $pago->anio }}</span>
            </div>
            <div>
                <span class="block text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase">Fecha de Emisión / Pago</span>
                <span class="font-semibold text-gray-800 dark:text-zinc-200 text-base">{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}</span>
            </div>
            <div>
                <span class="block text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase">Método de Pago</span>
                <span class="font-semibold text-gray-800 dark:text-zinc-200 text-base">{{ $pago->metodo_pago }}</span>
            </div>
            <div>
                <span class="block text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase">Nro. Comprobante / Recibo</span>
                <span class="font-semibold text-gray-800 dark:text-zinc-200 text-base">{{ $pago->nro_comprobante ?? 'No especificado' }}</span>
            </div>
            <div class="md:col-span-2">
                <span class="block text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase">Observaciones</span>
                <span class="text-gray-800 dark:text-zinc-200">{{ $pago->observaciones ?? 'Sin observaciones registradas.' }}</span>
            </div>
        </div>
    </div>

    <!-- Sección 2: Datos del Empleado -->
    <div class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl p-6 shadow-sm mb-6">
        <div class="flex items-center gap-2 mb-4 text-blue-600 dark:text-blue-400 font-semibold">
            <flux:icon name="user" class="w-5 h-5" />
            <span>Datos del Empleado</span>
        </div>
        <flux:separator variant="subtle" class="mb-4" />

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
            <div>
                <span class="block text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase">Nombre Completo</span>
                <span class="font-semibold text-gray-800 dark:text-zinc-200 text-base">{{ $pago->empleado->nombre ?? '' }} {{ $pago->empleado->apellido ?? '' }}</span>
            </div>
            <div>
                <span class="block text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase">Cédula de Identidad (CI)</span>
                <span class="font-semibold text-gray-800 dark:text-zinc-200 text-base">{{ $pago->empleado->ci ?? 'N/A' }}</span>
            </div>
            <div>
                <span class="block text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase">Área de Trabajo</span>
                <span class="font-semibold text-gray-800 dark:text-zinc-200 text-base">{{ $pago->empleado->area->nombre ?? 'No asignada' }}</span>
            </div>
        </div>
    </div>

    <!-- Sección 3: Desglose Económico (Ingresos y Descuentos) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Ingresos -->
        <div class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl p-6 shadow-sm">
            <div class="flex items-center gap-2 mb-4 text-emerald-600 dark:text-emerald-400 font-semibold">
                <flux:icon name="arrow-trending-up" class="w-5 h-5" />
                <span>Ingresos y Percepciones (+)</span>
            </div>
            <flux:separator variant="subtle" class="mb-4" />
            
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-zinc-400">Salario Base:</span>
                    <span class="font-semibold text-gray-800 dark:text-zinc-200">{{ number_format($pago->salario_base, 2, '.', ',') }} {{ $simboloMoneda }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-zinc-400">Bonos / Incentivos:</span>
                    <span class="font-semibold text-gray-800 dark:text-zinc-200">{{ number_format($pago->bonos, 2, '.', ',') }} {{ $simboloMoneda }}</span>
                </div>
                <flux:separator variant="subtle" class="my-2" />
                <div class="flex justify-between font-bold text-base">
                    <span class="text-gray-700 dark:text-zinc-300">Total Ganado:</span>
                    <span class="text-emerald-600 dark:text-emerald-400">{{ number_format($pago->salario_base + $pago->bonos, 2, '.', ',') }} {{ $simboloMoneda }}</span>
                </div>
            </div>
        </div>

        <!-- Descuentos y Retenciones -->
        <div class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl p-6 shadow-sm">
            <div class="flex items-center gap-2 mb-4 text-rose-600 dark:text-rose-400 font-semibold">
                <flux:icon name="arrow-trending-down" class="w-5 h-5" />
                <span>Descuentos y Retenciones (-)</span>
            </div>
            <flux:separator variant="subtle" class="mb-4" />
            
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-zinc-400">Descuento AFP:</span>
                    <span class="font-semibold text-gray-800 dark:text-zinc-200">{{ number_format($pago->descuento_afp, 2, '.', ',') }} {{ $simboloMoneda }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-zinc-400">Anticipos:</span>
                    <span class="font-semibold text-gray-800 dark:text-zinc-200">{{ number_format($pago->anticipos, 2, '.', ',') }} {{ $simboloMoneda }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-zinc-400">Otros Descuentos:</span>
                    <span class="font-semibold text-gray-800 dark:text-zinc-200">{{ number_format($pago->otros_descuentos, 2, '.', ',') }} {{ $simboloMoneda }}</span>
                </div>
                <flux:separator variant="subtle" class="my-2" />
                @php
                    $totalDescuentos = $pago->descuento_afp + $pago->anticipos + $pago->otros_descuentos;
                @endphp
                <div class="flex justify-between font-bold text-base">
                    <span class="text-gray-700 dark:text-zinc-300">Total Descuentos:</span>
                    <span class="text-rose-600 dark:text-rose-400">{{ number_format($totalDescuentos, 2, '.', ',') }} {{ $simboloMoneda }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección Final: Total Líquido a Pagar -->
    <div class="bg-blue-50 dark:bg-zinc-900 border border-blue-200 dark:border-zinc-700 rounded-xl p-6 shadow-sm flex justify-between items-center">
        <div>
            <span class="text-sm font-semibold text-gray-600 dark:text-zinc-400 uppercase tracking-wider">Total Líquido a Pagar</span>
            <div class="text-xs text-gray-500">Monto neto final transferido o entregado al colaborador.</div>
        </div>
        <div class="text-3xl font-black text-emerald-600 dark:text-emerald-400">
            @php
                $totalLiquido = ($pago->salario_base + $pago->bonos) - ($pago->descuento_afp + $pago->anticipos + $pago->otros_descuentos);
            @endphp
            {{ number_format($totalLiquido, 2, '.', ',') }} {{ $simboloMoneda }}
        </div>
    </div>
</x-layouts::app>