<x-layouts::app title="Panel Principal">
    <div class="max-w-full mx-auto space-y-6">

        <!-- Cabecera de Bienvenida -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white dark:bg-zinc-900 p-6 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-zinc-950 dark:text-zinc-50">¡Bienvenido de nuevo, {{ auth()->user()->name ?? 'Administrador' }}!</h1>
                <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Aquí tienes el resumen general de los recursos humanos y la nómina de la empresa.</p>
            </div>
            <div class="flex items-center gap-3">
                <flux:button variant="primary" href="{{ route('admin.empleados.create') }}" icon="user-plus">
                    Nuevo Empleado
                </flux:button>
                <flux:button variant="ghost" href="{{ route('admin.planillas.index') }}" icon="calculator">
                    Ir a Planillas
                </flux:button>
            </div>
        </div>

        <!-- 1. Tarjetas de Estadísticas Clave (KPIs) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

            <!-- Empleados Activos -->
            <div class="bg-white dark:bg-zinc-900 p-5 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Empleados Activos</p>
                    <h3 class="text-3xl font-extrabold text-zinc-900 dark:text-zinc-100 mt-1">{{ $totalEmpleados ?? 0 }}</h3>
                    <span class="text-xs text-emerald-600 dark:text-emerald-400 font-medium flex items-center gap-1 mt-1">
                        ● Personal registrado
                    </span>
                </div>
                <div class="p-3 bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 rounded-full">
                    <flux:icon.users class="w-8 h-8" />
                </div>
            </div>

            <!-- Masa Salarial Actual -->
            <div class="bg-white dark:bg-zinc-900 p-5 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Masa Salarial (Mes)</p>
                    <h3 class="text-3xl font-extrabold text-zinc-900 dark:text-zinc-100 mt-1">Bs. {{ number_format($masaSalarial ?? 0, 2, ',', '.') }}</h3>
                    <span class="text-xs text-zinc-500 dark:text-zinc-400 font-medium flex items-center gap-1 mt-1">
                        Sueldos base vigentes
                    </span>
                </div>
                <div class="p-3 bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 rounded-full">
                    <flux:icon.banknotes class="w-8 h-8" />
                </div>
            </div>

            <!-- Contratos Vigentes -->
            <div class="bg-white dark:bg-zinc-900 p-5 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Contratos Vigentes</p>
                    <h3 class="text-3xl font-extrabold text-zinc-900 dark:text-zinc-100 mt-1">{{ $totalContratosActivos ?? 0 }}</h3>
                    <span class="text-xs text-blue-600 dark:text-blue-400 font-medium flex items-center gap-1 mt-1">
                        Activos en sistema
                    </span>
                </div>
                <div class="p-3 bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 rounded-full">
                    <flux:icon.document-text class="w-8 h-8" />
                </div>
            </div>

            <!-- Cumpleaños del Mes -->
            <div class="bg-white dark:bg-zinc-900 p-5 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Cumpleaños (Mes)</p>
                    <h3 class="text-3xl font-extrabold text-zinc-900 dark:text-zinc-100 mt-1">{{ $cumpleañeros ?? 0 }}</h3>
                    <span class="text-xs text-pink-600 dark:text-pink-400 font-medium flex items-center gap-1 mt-1">
                        ¡Felicitar al personal!
                    </span>
                </div>
                <div class="p-3 bg-pink-50 dark:bg-pink-950/50 text-pink-600 dark:text-pink-400 rounded-full">
                    <flux:icon.cake class="w-8 h-8" />
                </div>
            </div>

        </div>

        <!-- 2. Sección Inferior: Tabla de Vencimientos y Accesos Rápidos -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Columna Izquierda: Contratos por vencer -->
            <div class="lg:col-span-2 bg-white dark:bg-zinc-900 p-6 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm space-y-4">
                <div class="flex items-center justify-between gap-4">
                    <h2 class="text-lg font-bold text-zinc-900 dark:text-zinc-100">Contratos Próximos a Vencer (30 días)</h2>
                    <flux:button variant="ghost" size="sm" href="{{ route('admin.contratos.index') }}">Ver todos</flux:button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-zinc-600 dark:text-zinc-400">
                        <thead class="border-b border-zinc-200 dark:border-zinc-800 text-xs uppercase bg-zinc-50 dark:bg-zinc-800/50 text-zinc-700 dark:text-zinc-300">
                            <tr>
                                <th class="p-3">Empleado</th>
                                <th class="p-3">Tipo</th>
                                <th class="p-3">Fecha de Fin</th>
                                <th class="p-3 text-right">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                            @forelse($contratosPorVencer ?? [] as $contrato)
                                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/30">
                                    <td class="p-3 font-medium text-zinc-900 dark:text-zinc-100">
                                        {{ optional($contrato->empleado)->nombre }} {{ optional($contrato->empleado)->apellido }}
                                    </td>
                                    <td class="p-3">{{ $contrato->tipo_contrato }}</td>
                                    <td class="p-3 font-semibold text-amber-600 dark:text-amber-400">
                                        {{ \Carbon\Carbon::parse($contrato->fecha_fin)->format('d/m/Y') }}
                                    </td>
                                    <td class="p-3 text-right">
                                        <flux:button size="sm" variant="subtle" href="{{ route('admin.contratos.edit', $contrato->id) }}">Revisar</flux:button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-6 text-center text-zinc-500 dark:text-zinc-400 italic">No hay contratos próximos a vencer en los siguientes 30 días. 🎉</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Columna Derecha: Accesos Rápidos -->
            <div class="bg-white dark:bg-zinc-900 p-6 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm space-y-4">
                <h2 class="text-lg font-bold text-zinc-900 dark:text-zinc-100">Accesos Rápidos</h2>

                <div class="grid grid-cols-1 gap-3">
                    <flux:button variant="subtle" href="{{ route('admin.planillas.create') }}" icon="document-plus" class="justify-between w-full text-left">
                        Generar Nueva Planilla
                    </flux:button>
                    <flux:button variant="subtle" href="{{ route('admin.pagos.create') }}" icon="currency-dollar" class="justify-between w-full text-left">
                        Registrar Pago Individual
                    </flux:button>
                    <flux:button variant="subtle" href="{{ route('admin.empleados.index') }}" icon="user-group" class="justify-between w-full text-left">
                        Catálogo de Empleados
                    </flux:button>
                    <flux:button variant="subtle" href="{{ route('admin.aguinaldos.index') }}" icon="gift" class="justify-between w-full text-left">
                        Módulo de Aguinaldos
                    </flux:button>
                </div>

                <div class="pt-4 mt-4 border-t border-zinc-200 dark:border-zinc-800">
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400 mb-2">Estado del Sistema</h3>
                    <div class="flex items-center justify-between text-xs text-zinc-600 dark:text-zinc-400">
                        <span>Base de Datos</span>
                        <span class="text-emerald-600 dark:text-emerald-400 font-medium">Conectado (Online)</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-layouts::app>
