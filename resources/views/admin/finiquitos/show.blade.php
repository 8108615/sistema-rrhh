<x-layouts::app title="Detalle de Finiquito">
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

    <div class="relative mb-6 w-full flex justify-between items-center print:hidden">
        <div>
            <flux:heading size="xl" level="1">Hoja de Liquidación de Beneficios Sociales</flux:heading>
            <flux:subheading>Detalle completo del cálculo de finiquito.</flux:subheading>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.finiquitos.index') }}">
                <flux:button variant="subtle" icon="arrow-left">Volver</flux:button>
            </a>
            <a href="{{ route('admin.finiquitos.print', $finiquito->id) }}" target="_blank">
                <flux:button variant="primary" icon="printer" color="blue">Imprimir / PDF</flux:button>
            </a>
        </div>
    </div>

    <flux:separator variant="subtle" class="mb-6 print:hidden" />

    <!-- Contenedor Estilo Documento / Recibo -->
    <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-8 shadow-sm space-y-6 max-w-4xl mx-auto">
        <!-- Encabezado de la Empresa / Reporte -->
        <div class="flex justify-between items-start border-b border-gray-200 dark:border-zinc-700 pb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-white">COMPROBANTE DE FINIQUITO</h2>
                <p class="text-sm text-gray-500">Liquidación por Leyes Sociales</p>
            </div>
            <div class="text-right">
                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300">
                    {{ $finiquito->causal_retiro }}
                </span>
                <p class="text-xs text-gray-400 mt-1">Fecha de Emisión: {{ date('d/m/Y') }}</p>
            </div>
        </div>

        <!-- Datos del Empleado -->
        <div>
            <h3 class="text-sm font-bold uppercase text-gray-500 dark:text-zinc-400 mb-3">1. Datos del Trabajador</h3>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm bg-gray-50 dark:bg-zinc-900/50 p-4 rounded-lg border border-gray-200 dark:border-zinc-700">
                <div>
                    <span class="block text-xs text-gray-500">Nombre Completo:</span>
                    <span class="font-semibold text-gray-800 dark:text-zinc-200">{{ $finiquito->empleado->nombre }} {{ $finiquito->empleado->apellido }}</span>
                </div>
                <div>
                    <span class="block text-xs text-gray-500">Cédula de Identidad (CI):</span>
                    <span class="font-semibold text-gray-800 dark:text-zinc-200">{{ $finiquito->empleado->ci }}</span>
                </div>
                <div>
                    <span class="block text-xs text-gray-500">Cargo / Departamento:</span>
                    <span class="font-semibold text-gray-800 dark:text-zinc-200">{{ $finiquito->empleado->departamento->nombre ?? 'N/A' }}</span>
                </div>
                <div>
                    <span class="block text-xs text-gray-500">Fecha de Ingreso:</span>
                    <span class="font-semibold text-gray-800 dark:text-zinc-200">{{ \Carbon\Carbon::parse($finiquito->fecha_ingreso)->format('d/m/Y') }}</span>
                </div>
                <div>
                    <span class="block text-xs text-gray-500">Fecha de Retiro:</span>
                    <span class="font-semibold text-gray-800 dark:text-zinc-200">{{ \Carbon\Carbon::parse($finiquito->fecha_retiro)->format('d/m/Y') }}</span>
                </div>
                <div>
                    <span class="block text-xs text-gray-500">Tiempo de Servicio:</span>
                    <span class="font-semibold text-gray-800 dark:text-zinc-200">{{ $finiquito->anos_servicio }} años equivalentes</span>
                </div>
            </div>
        </div>

        <!-- Bases de Cálculo -->
        <div>
            <h3 class="text-sm font-bold uppercase text-gray-500 dark:text-zinc-400 mb-3">2. Bases Salariales</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm bg-gray-50 dark:bg-zinc-900/50 p-4 rounded-lg border border-gray-200 dark:border-zinc-700">
                <div>
                    <span class="block text-xs text-gray-500">Último Salario Base:</span>
                    <span class="font-semibold text-gray-800 dark:text-zinc-200">{{ $simboloMoneda }} {{ number_format($finiquito->ultimo_salario, 2, ',', '.') }}</span>
                </div>
                <div>
                    <span class="block text-xs text-gray-500">Promedio Indemnizable (3 Meses):</span>
                    <span class="font-semibold text-gray-800 dark:text-zinc-200">{{ $simboloMoneda }} {{ number_format($finiquito->promedio_tres_salarios, 2, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Desglose de Beneficios Sociales -->
        <div>
            <h3 class="text-sm font-bold uppercase text-gray-500 dark:text-zinc-400 mb-3">3. Desglose de Beneficios</h3>
            <table class="min-w-full border-collapse border border-gray-200 dark:border-zinc-700 text-sm">
                <thead class="bg-gray-100 dark:bg-zinc-900 text-left">
                    <tr>
                        <th class="px-4 py-2 border border-gray-200 dark:border-zinc-700">Concepto</th>
                        <th class="px-4 py-2 border border-gray-200 dark:border-zinc-700 text-right">Monto ({{ $simboloMoneda }})</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="px-4 py-2 border border-gray-200 dark:border-zinc-700">Indemnización por Tiempo de Servicios</td>
                        <td class="px-4 py-2 border border-gray-200 dark:border-zinc-700 text-right font-medium">{{ number_format($finiquito->monto_indemnizacion, 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2 border border-gray-200 dark:border-zinc-700">Desahucio (3 Meses - Si corresponde)</td>
                        <td class="px-4 py-2 border border-gray-200 dark:border-zinc-700 text-right font-medium">{{ number_format($finiquito->monto_desahucio, 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2 border border-gray-200 dark:border-zinc-700">Aguinaldo Proporcional</td>
                        <td class="px-4 py-2 border border-gray-200 dark:border-zinc-700 text-right font-medium">{{ number_format($finiquito->monto_aguinaldo, 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2 border border-gray-200 dark:border-zinc-700">Vacación Compensatoria / Proporcional</td>
                        <td class="px-4 py-2 border border-gray-200 dark:border-zinc-700 text-right font-medium">{{ number_format($finiquito->monto_vacacion, 2, ',', '.') }}</td>
                    </tr>
                    <tr class="bg-emerald-50 dark:bg-emerald-950/30 font-bold text-emerald-700 dark:text-emerald-400">
                        <td class="px-4 py-3 border border-gray-200 dark:border-zinc-700 uppercase">Total General a Pagar</td>
                        <td class="px-4 py-3 border border-gray-200 dark:border-zinc-700 text-right text-base">
                            {{ $simboloMoneda }} {{ number_format($finiquito->total_beneficios, 2, ',', '.') }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        @if($finiquito->observaciones)
        <div>
            <h3 class="text-sm font-bold uppercase text-gray-500 dark:text-zinc-400 mb-1">Observaciones</h3>
            <p class="text-sm bg-gray-50 dark:bg-zinc-900/50 p-3 rounded-lg border border-gray-200 dark:border-zinc-700 text-gray-700 dark:text-zinc-300">
                {{ $finiquito->observaciones }}
            </p>
        </div>
        @endif

        <!-- Firmas -->
        <div class="pt-16 grid grid-cols-2 gap-12 text-center">
            <div class="border-t border-gray-400 pt-2">
                <p class="text-sm font-semibold text-gray-700 dark:text-zinc-300">Firma del Empleador / Representante</p>
            </div>
            <div class="border-t border-gray-400 pt-2">
                <p class="text-sm font-semibold text-gray-700 dark:text-zinc-300">Firma del Trabajador</p>
            </div>
        </div>
    </div>
</x-layouts::app>
