<x-layouts::app title="Nuevo Empleado">
    <div class="relative mb-6 w-full flex justify-between items-center">
        <div>
            <flux:heading size="xl" level="1">Registrar Nuevo Empleado</flux:heading>
            <flux:subheading>Completa los campos organizados por secciones para dar de alta al personal.</flux:subheading>
        </div>
        <div>
            <a href="{{ route('admin.empleados.index') }}">
                <flux:button variant="subtle" icon="arrow-left">Volver</flux:button>
            </a>
        </div>
    </div>

    <flux:separator variant="subtle" class="mb-6" />

    <form action="{{ route('admin.empleados.store') }}" method="POST">
        @csrf

        <div class="space-y-6">

            <!-- SECCIÓN 1: DATOS PERSONALES -->
            <div class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl p-6 shadow-sm">
                <div class="flex items-center gap-2 pb-4 mb-4 border-b border-gray-100 dark:border-zinc-700">
                    <flux:icon name="user" class="text-blue-500 h-5 w-5" />
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">Datos Personales</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <flux:label>Nombre <span class="text-red-500">(*)</span></flux:label>
                        <flux:input name="nombre" value="{{ old('nombre') }}" placeholder="Ej. Juan Carlos" required />
                        <flux:error name="nombre" />
                    </div>

                    <div>
                        <flux:label>Apellido <span class="text-red-500">(*)</span></flux:label>
                        <flux:input name="apellido" value="{{ old('apellido') }}" placeholder="Ej. Pérez Gómez" required />
                        <flux:error name="apellido" />
                    </div>

                    <div>
                        <flux:label>Cédula de Identidad (CI) <span class="text-red-500">(*)</span></flux:label>
                        <flux:input name="ci" value="{{ old('ci') }}" placeholder="Ej. 1234567 SC" required />
                        <flux:error name="ci" />
                    </div>

                    <div>
                        <flux:label>Fecha de Nacimiento</flux:label>
                        <flux:input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}" />
                        <flux:error name="fecha_nacimiento" />
                    </div>

                    <div>
                        <flux:label>Género</flux:label>
                        <flux:select placeholder="Seleccionar género..." name="genero">
                            <flux:select.option value="MASCULINO" :selected="old('genero') == 'MASCULINO'">MASCULINO</flux:select.option>
                            <flux:select.option value="FEMENINO" :selected="old('genero') == 'FEMENINO'">FEMENINO</flux:select.option>
                        </flux:select>
                        <flux:error name="genero" />
                    </div>

                    <div>
                        <flux:label>Teléfono / Celular personal</flux:label>
                        <flux:input name="telefono" value="{{ old('telefono') }}" placeholder="Ej. 70012345" />
                        <flux:error name="telefono" />
                    </div>

                    <div class="md:col-span-2">
                        <flux:label>Dirección domiciliaria</flux:label>
                        <flux:input name="direccion" value="{{ old('direccion') }}" placeholder="Ej. Av. 3 Pasos al Frente #123" />
                        <flux:error name="direccion" />
                    </div>

                    <div>
                        <flux:label>Correo electrónico</flux:label>
                        <flux:input type="email" name="email" value="{{ old('email') }}" placeholder="correo@ejemplo.com" />
                        <flux:error name="email" />
                    </div>
                </div>
            </div>

            <!-- SECCIÓN 2: DATOS LABORALES -->
            <div class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl p-6 shadow-sm">
                <div class="flex items-center gap-2 pb-4 mb-4 border-b border-gray-100 dark:border-zinc-700">
                    <flux:icon name="briefcase" class="text-indigo-500 h-5 w-5" />
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">Datos Laborales y Estructura</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <flux:label>Departamento <span class="text-red-500">(*)</span></flux:label>
                        <flux:select placeholder="Selecciona el departamento..." name="departamento_id" required>
                            @foreach ($departamentos as $dep)
                                <flux:select.option value="{{ $dep->id }}" :selected="old('departamento_id') == $dep->id">
                                    {{ $dep->nombre }}
                                </flux:select.option>
                            @endforeach
                        </flux:select>
                        <flux:error name="departamento_id" />
                    </div>

                    <div>
                        <flux:label>Área de trabajo <span class="text-red-500">(*)</span></flux:label>
                        <flux:select placeholder="Selecciona el área..." name="area_id" required>
                            @foreach ($areas as $area)
                                <flux:select.option value="{{ $area->id }}" :selected="old('area_id') == $area->id">
                                    {{ $area->nombre }}
                                </flux:select.option>
                            @endforeach
                        </flux:select>
                        <flux:error name="area_id" />
                    </div>

                    <div>
                        <flux:label>Fecha de Ingreso</flux:label>
                        <flux:input type="date" name="fecha_ingreso" value="{{ old('fecha_ingreso', date('Y-m-d')) }}" />
                        <flux:error name="fecha_ingreso" />
                    </div>

                    <div>
                        <flux:label>Salario Base <span class="text-red-500">(*)</span></flux:label>
                        <flux:input type="number" step="0.01" name="salario" value="{{ old('salario') }}" placeholder="0.00" required />
                        <flux:error name="salario" />
                    </div>

                    <div>
                        <flux:label>Estado <span class="text-red-500">(*)</span></flux:label>
                        <flux:select placeholder="Selecciona el estado..." name="estado" required>
                            <flux:select.option value="1" :selected="old('estado', '1') == '1'">ACTIVO</flux:select.option>
                            <flux:select.option value="0" :selected="old('estado') == '0'">INACTIVO</flux:select.option>
                        </flux:select>
                        <flux:error name="estado" />
                    </div>
                </div>
            </div>

            <!-- SECCIÓN 3: DATOS BANCARIOS Y CONTACTO DE EMERGENCIA -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Datos Bancarios -->
                <div class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl p-6 shadow-sm">
                    <div class="flex items-center gap-2 pb-4 mb-4 border-b border-gray-100 dark:border-zinc-700">
                        <flux:icon name="credit-card" class="text-emerald-500 h-5 w-5" />
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">Información Bancaria</h3>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <flux:label>Entidad Financiera (Banco)</flux:label>
                            <flux:input name="banco" value="{{ old('banco') }}" placeholder="Ej. Banco Nacional de Bolivia" />
                            <flux:error name="banco" />
                        </div>
                        <div>
                            <flux:label>Número de Cuenta</flux:label>
                            <flux:input name="nro_cuenta" value="{{ old('nro_cuenta') }}" placeholder="Ej. 4001234567" />
                            <flux:error name="nro_cuenta" />
                        </div>
                    </div>
                </div>

                <!-- Contacto de Emergencia -->
                <div class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl p-6 shadow-sm">
                    <div class="flex items-center gap-2 pb-4 mb-4 border-b border-gray-100 dark:border-zinc-700">
                        <flux:icon name="phone-arrow-up-right" class="text-amber-500 h-5 w-5" />
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">Contacto de Emergencia / Referencia</h3>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <flux:label>Celular de Referencia</flux:label>
                            <flux:input name="celular_referencia" value="{{ old('celular_referencia') }}" placeholder="Ej. 79876543" />
                            <flux:error name="celular_referencia" />
                        </div>
                        <div>
                            <flux:label>Parentesco de la Referencia</flux:label>
                            <flux:input name="parentesco_referencia" value="{{ old('parentesco_referencia') }}" placeholder="Ej. Esposo/a, Padre, Hermano/a" />
                            <flux:error name="parentesco_referencia" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- BOTONES DE ACCIÓN -->
            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('admin.empleados.index') }}">
                    <flux:button variant="subtle">Cancelar</flux:button>
                </a>
                <flux:button variant="primary" type="submit" color="blue" icon="check">Guardar Empleado</flux:button>
            </div>

        </div>
    </form>
</x-layouts::app>
