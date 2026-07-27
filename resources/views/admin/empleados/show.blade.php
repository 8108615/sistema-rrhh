<x-layouts::app title="Detalles del Empleado">
    <div class="relative mb-6 w-full flex justify-between items-center">
        <div>
            <flux:heading size="xl" level="1">Detalles del Empleado</flux:heading>
            <flux:subheading>Información completa del personal registrado.</flux:subheading>
        </div>
        <div>
            <a href="{{ route('admin.empleados.index') }}">
                <flux:button variant="subtle" icon="arrow-left">Volver al listado</flux:button>
            </a>
        </div>
    </div>

    <flux:separator variant="subtle" class="mb-6" />

    <div class="space-y-6">

        <!-- DATOS PERSONALES -->
        <div class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl p-6 shadow-sm">
            <div class="flex items-center gap-2 pb-4 mb-4 border-b border-gray-100 dark:border-zinc-700">
                <flux:icon name="user" class="text-blue-500 h-5 w-5" />
                <h3 class="text-base font-semibold text-gray-900 dark:text-white">Datos Personales</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <span class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Nombre Completo</span>
                    <p class="text-sm font-semibold text-gray-900 dark:text-white mt-1">{{ $empleado->nombre }} {{ $empleado->apellido }}</p>
                </div>
                <div>
                    <span class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Cédula de Identidad (CI)</span>
                    <p class="text-sm font-semibold text-gray-900 dark:text-white mt-1">{{ $empleado->ci }}</p>
                </div>
                <div>
                    <span class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Género</span>
                    <p class="text-sm font-semibold text-gray-900 dark:text-white mt-1">{{ $empleado->genero ?? 'No especificado' }}</p>
                </div>
                <div>
                    <span class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Fecha de Nacimiento</span>
                    <p class="text-sm font-semibold text-gray-900 dark:text-white mt-1">{{ $empleado->fecha_nacimiento ?? 'No registrada' }}</p>
                </div>
                <div>
                    <span class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Teléfono / Celular</span>
                    <p class="text-sm font-semibold text-gray-900 dark:text-white mt-1">{{ $empleado->telefono ?? 'No registrado' }}</p>
                </div>
                <div>
                    <span class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Correo Electrónico</span>
                    <p class="text-sm font-semibold text-gray-900 dark:text-white mt-1">{{ $empleado->email ?? 'No registrado' }}</p>
                </div>
                <div class="md:col-span-3">
                    <span class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Dirección Domiciliaria</span>
                    <p class="text-sm font-semibold text-gray-900 dark:text-white mt-1">{{ $empleado->direccion ?? 'No registrada' }}</p>
                </div>
            </div>
        </div>

        <!-- DATOS LABORALES -->
        <div class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl p-6 shadow-sm">
            <div class="flex items-center gap-2 pb-4 mb-4 border-b border-gray-100 dark:border-zinc-700">
                <flux:icon name="briefcase" class="text-indigo-500 h-5 w-5" />
                <h3 class="text-base font-semibold text-gray-900 dark:text-white">Datos Laborales</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <span class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Departamento</span>
                    <p class="text-sm font-semibold text-gray-900 dark:text-white mt-1">{{ $empleado->departamento->nombre ?? 'N/A' }}</p>
                </div>
                <div>
                    <span class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Área de Trabajo</span>
                    <p class="text-sm font-semibold text-gray-900 dark:text-white mt-1">{{ $empleado->area->nombre ?? 'N/A' }}</p>
                </div>
                <div>
                    <span class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Fecha de Ingreso</span>
                    <p class="text-sm font-semibold text-gray-900 dark:text-white mt-1">{{ $empleado->fecha_ingreso ?? 'No registrada' }}</p>
                </div>
                <div>
                    <span class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Salario Base</span>
                    <p class="text-sm font-semibold text-emerald-600 dark:text-emerald-400 mt-1">{{ number_format($empleado->salario, 2, ',', '.') }} Bs.</p>
                </div>
                <div>
                    <span class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Estado</span>
                    <p class="mt-1">
                        @if ($empleado->estado)
                            <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-emerald-500 text-white">Activo</span>
                        @else
                            <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-red-500 text-white">Inactivo</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- INFORMACIÓN BANCARIA Y REFERENCIA -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Banco -->
            <div class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl p-6 shadow-sm">
                <div class="flex items-center gap-2 pb-4 mb-4 border-b border-gray-100 dark:border-zinc-700">
                    <flux:icon name="credit-card" class="text-emerald-500 h-5 w-5" />
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">Información Bancaria</h3>
                </div>
                <div class="space-y-4">
                    <div>
                        <span class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Entidad Financiera (Banco)</span>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white mt-1">{{ $empleado->banco ?? 'No registrado' }}</p>
                    </div>
                    <div>
                        <span class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Número de Cuenta</span>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white mt-1">{{ $empleado->nro_cuenta ?? 'No registrado' }}</p>
                    </div>
                </div>
            </div>

            <!-- Emergencia -->
            <div class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl p-6 shadow-sm">
                <div class="flex items-center gap-2 pb-4 mb-4 border-b border-gray-100 dark:border-zinc-700">
                    <flux:icon name="phone-arrow-up-right" class="text-amber-500 h-5 w-5" />
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">Contacto de Emergencia</h3>
                </div>
                <div class="space-y-4">
                    <div>
                        <span class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Celular de Referencia</span>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white mt-1">{{ $empleado->celular_referencia ?? 'No registrado' }}</p>
                    </div>
                    <div>
                        <span class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Parentesco</span>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white mt-1">{{ $empleado->parentesco_referencia ?? 'No registrado' }}</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-layouts::app>
