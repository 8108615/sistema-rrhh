<x-layouts::app title="Detalles del Contrato">
    <div class="space-y-6 max-w-full mx-auto">
        <!-- Cabecera -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-zinc-100">Detalles del Contrato</h1>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Información completa del contrato laboral e historial.</p>
            </div>
            <div class="flex items-center gap-2">
                <flux:button variant="primary" href="{{ route('admin.contratos.imprimir', $contrato->id) }}" icon="printer" target="_blank" class="bg-amber-500 hover:bg-amber-600">
                    Imprimir Contrato
                </flux:button>
                <flux:button variant="subtle" href="{{ route('admin.contratos.index') }}" icon="arrow-left">
                    Volver
                </flux:button>
            </div>
        </div>

        <!-- Tarjeta de Información -->
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm overflow-hidden p-6 space-y-6">

            <!-- Estado y Tipo -->
            <div class="flex flex-col sm:flex-row justify-between items-center border-b border-zinc-200 dark:border-zinc-800 pb-4 gap-4">
                <div>
                    <span class="text-xs text-zinc-500 uppercase tracking-wider font-semibold">Tipo de Contrato</span>
                    <h2 class="text-lg font-bold text-zinc-900 dark:text-zinc-100">{{ $contrato->tipo_contrato }}</h2>
                </div>
                <div>
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
                </div>
            </div>

            <!-- Datos del Empleado -->
            <div>
                <h3 class="text-sm font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider mb-3">Información del Empleado</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-zinc-50 dark:bg-zinc-800/50 p-4 rounded-lg border border-zinc-200 dark:border-zinc-800">
                    <div>
                        <span class="text-xs text-zinc-500">Nombre Completo:</span>
                        <p class="font-medium text-zinc-900 dark:text-zinc-100">
                            {{ $contrato->empleado->nombre ?? 'N/A' }} {{ $contrato->empleado->apellido ?? '' }}
                        </p>
                    </div>
                    <div>
                        <span class="text-xs text-zinc-500">Cédula de Identidad (CI):</span>
                        <p class="font-medium text-zinc-900 dark:text-zinc-100">{{ $contrato->empleado->ci ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Detalles del Puesto y Salario -->
            <div>
                <h3 class="text-sm font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider mb-3">Detalles del Puesto</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-zinc-50 dark:bg-zinc-800/50 p-4 rounded-lg border border-zinc-200 dark:border-zinc-800">
                    <div>
                        <span class="text-xs text-zinc-500">Cargo del Contrato:</span>
                        <p class="font-medium text-zinc-900 dark:text-zinc-100">{{ $contrato->cargo_contrato }}</p>
                    </div>
                    <div>
                        <span class="text-xs text-zinc-500">Salario Mensual:</span>
                        <p class="font-medium text-zinc-900 dark:text-zinc-100">Bs. {{ number_format($contrato->salario_mensual, 2, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <!-- Vigencia -->
            <div>
                <h3 class="text-sm font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider mb-3">Vigencia del Contrato</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-zinc-50 dark:bg-zinc-800/50 p-4 rounded-lg border border-zinc-200 dark:border-zinc-800">
                    <div>
                        <span class="text-xs text-zinc-500">Fecha de Inicio:</span>
                        <p class="font-medium text-zinc-900 dark:text-zinc-100">{{ \Carbon\Carbon::parse($contrato->fecha_inicio)->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <span class="text-xs text-zinc-500">Fecha de Fin:</span>
                        <p class="font-medium text-zinc-900 dark:text-zinc-100">
                            {{ $contrato->fecha_fin ? \Carbon\Carbon::parse($contrato->fecha_fin)->format('d/m/Y') : 'Indefinido / Labor determinada' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Observaciones o PDF Adjunto si existen -->
            @if($contrato->observaciones)
                <div>
                    <h3 class="text-sm font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider mb-2">Observaciones</h3>
                    <p class="text-sm text-zinc-700 dark:text-zinc-300 bg-zinc-50 dark:bg-zinc-800/50 p-4 rounded-lg border border-zinc-200 dark:border-zinc-800">
                        {{ $contrato->observaciones }}
                    </p>
                </div>
            @endif

            @if($contrato->archivo_pdf)
                <div class="pt-4 border-t border-zinc-200 dark:border-zinc-800 flex items-center justify-between">
                    <span class="text-sm text-zinc-600 dark:text-zinc-400">Archivo PDF de respaldo disponible.</span>
                    <flux:button variant="primary" size="sm" href="{{ route('admin.contratos.download-pdf', $contrato->id) }}" icon="arrow-down-tray" class="bg-violet-600 hover:bg-violet-700">
                        Descargar PDF
                    </flux:button>
                </div>
            @endif

        </div>
    </div>
</x-layouts::app>
