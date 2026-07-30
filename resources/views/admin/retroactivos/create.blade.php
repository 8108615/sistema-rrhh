<x-layouts::app title="Nuevo Registro Retroactivo">
    <!-- Encabezado de la Página -->
    <div class="relative mb-6 w-full flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <flux:heading size="xl" level="1">Nuevo Registro Retroactivo</flux:heading>
            <flux:subheading>Registra manualmente el incremento salarial retroactivo para un empleado.</flux:subheading>
        </div>
        <div>
            <a href="{{ route('admin.retroactivos.index') }}" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg shadow-sm transition flex items-center gap-2 text-sm">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <!-- Formulario de Creación -->
    <div class="rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-6 shadow-sm">
        <form action="{{ route('admin.retroactivos.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Campo oculto o extra si tu controlador requiere 'diferencia_mensual' -->
            <input type="hidden" id="diferencia_mensual" name="diferencia_mensual" value="{{ old('diferencia_mensual') }}">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                <!-- Empleado -->
                <div class="md:col-span-2 lg:col-span-3">
                    <label for="empleado_id" class="block text-xs font-bold text-gray-500 dark:text-zinc-400 uppercase mb-1">Empleado <span class="text-red-500">*</span></label>
                    <select id="empleado_id" name="empleado_id" required class="w-full rounded-lg border border-gray-300 bg-white py-2.5 px-3 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white">
                        <option value="">-- Seleccione un Empleado --</option>
                        @foreach($empleados as $empleado)
                            <option value="{{ $empleado->id }}"
                                    data-sueldo="{{ $empleado->salario }}"
                                    {{ old('empleado_id') == $empleado->id ? 'selected' : '' }}>
                                {{ $empleado->nombre }} {{ $empleado->apellido }} (CI: {{ $empleado->ci }}) - Salario: {{ $simboloMoneda ?? 'Bs.' }} {{ number_format($empleado->salario, 2, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                    @error('empleado_id')
                        <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Gestión (Año) -->
                <div>
                    <label for="gestion" class="block text-xs font-bold text-gray-500 dark:text-zinc-400 uppercase mb-1">Gestión (Año) <span class="text-red-500">*</span></label>
                    <select id="gestion" name="gestion" required class="w-full rounded-lg border border-gray-300 bg-white py-2.5 px-3 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white">
                        @for ($i = date('Y'); $i >= date('Y') - 5; $i--)
                            <option value="{{ $i }}" {{ (old('gestion', date('Y')) == $i) ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                    @error('gestion')
                        <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Meses Aplicados -->
                <div>
                    <label for="meses_aplicados" class="block text-xs font-bold text-gray-500 dark:text-zinc-400 uppercase mb-1">Meses Aplicados <span class="text-red-500">*</span></label>
                    <input type="number" id="meses_aplicados" name="meses_aplicados" value="{{ old('meses_aplicados', 5) }}" required min="1" max="12" class="w-full rounded-lg border border-gray-300 bg-white py-2 px-3 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white">
                    @error('meses_aplicados')
                        <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Porcentaje de Incremento -->
                <div>
                    <label for="porcentaje_incremento" class="block text-xs font-bold text-gray-500 dark:text-zinc-400 uppercase mb-1">% de Incremento</label>
                    <input type="number" step="0.01" id="porcentaje_incremento" name="porcentaje_incremento" value="{{ old('porcentaje_incremento') }}" placeholder="Ej. 3.00" class="w-full rounded-lg border border-gray-300 bg-white py-2 px-3 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white font-mono">
                    <span class="text-[11px] text-gray-400 mt-0.5 block">Calcula tarifa nueva y monto a pagar.</span>
                </div>

                <!-- Sueldo Anterior -->
                <div>
                    <label for="sueldo_anterior" class="block text-xs font-bold text-gray-500 dark:text-zinc-400 uppercase mb-1">Sueldo Anterior ({{ $simboloMoneda ?? 'Bs.' }}) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" id="sueldo_anterior" name="sueldo_anterior" value="{{ old('sueldo_anterior') }}" required placeholder="0.00" class="w-full rounded-lg border border-gray-300 bg-white py-2 px-3 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white font-mono">
                    @error('sueldo_anterior')
                        <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Sueldo Nuevo -->
                <div>
                    <label for="sueldo_nuevo" class="block text-xs font-bold text-gray-500 dark:text-zinc-400 uppercase mb-1">Sueldo Nuevo ({{ $simboloMoneda ?? 'Bs.' }}) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" id="sueldo_nuevo" name="sueldo_nuevo" value="{{ old('sueldo_nuevo') }}" required placeholder="0.00" class="w-full rounded-lg border border-gray-300 bg-white py-2 px-3 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white font-mono">
                    @error('sueldo_nuevo')
                        <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Monto a Pagar (Nombre exacto de tu BD) -->
                <div>
                    <label for="monto_pagar" class="block text-xs font-bold text-gray-500 dark:text-zinc-400 uppercase mb-1">Monto a Pagar ({{ $simboloMoneda ?? 'Bs.' }}) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" id="monto_pagar" name="monto_pagar" value="{{ old('monto_pagar') }}" required placeholder="0.00" class="w-full rounded-lg border border-gray-300 bg-gray-50 py-2 px-3 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white font-mono">
                    @error('monto_pagar')
                        <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Fecha de Pago -->
                <div>
                    <label for="fecha_pago" class="block text-xs font-bold text-gray-500 dark:text-zinc-400 uppercase mb-1">Fecha de Pago</label>
                    <input type="date" id="fecha_pago" name="fecha_pago" value="{{ old('fecha_pago', date('Y-m-d')) }}" class="w-full rounded-lg border border-gray-300 bg-white py-2 px-3 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white">
                    @error('fecha_pago')
                        <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Estado -->
                <div>
                    <label for="estado" class="block text-xs font-bold text-gray-500 dark:text-zinc-400 uppercase mb-1">Estado <span class="text-red-500">*</span></label>
                    <select id="estado" name="estado" required class="w-full rounded-lg border border-gray-300 bg-white py-2.5 px-3 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white">
                        <option value="Pendiente" {{ old('estado') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="Pagado" {{ old('estado') == 'Pagado' ? 'selected' : '' }}>Pagado</option>
                    </select>
                    @error('estado')
                        <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

            </div>

            <!-- Botones de Acción -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-zinc-700">
                <a href="{{ route('admin.retroactivos.index') }}" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg transition text-sm">
                    Cancelar
                </a>
                <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-sm transition flex items-center gap-2 text-sm cursor-pointer">
                    <i class="fas fa-save"></i> Guardar Registro
                </button>
            </div>

        </form>
    </div>

    <!-- Script de cálculo actualizado para monto_pagar y diferencia_mensual -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectEmpleado = document.getElementById('empleado_id');
            const inputSueldoAnterior = document.getElementById('sueldo_anterior');
            const inputSueldoNuevo = document.getElementById('sueldo_nuevo');
            const inputMontoPagar = document.getElementById('monto_pagar');
            const inputDiferenciaMensual = document.getElementById('diferencia_mensual');
            const inputPorcentaje = document.getElementById('porcentaje_incremento');
            const inputMeses = document.getElementById('meses_aplicados');

            selectEmpleado.addEventListener('change', function () {
                const selectedOption = this.options[this.selectedIndex];
                const salarioBase = selectedOption.getAttribute('data-sueldo');

                if (salarioBase) {
                    inputSueldoAnterior.value = parseFloat(salarioBase).toFixed(2);
                    calcularValores();
                } else {
                    inputSueldoAnterior.value = '';
                    inputSueldoNuevo.value = '';
                    inputMontoPagar.value = '';
                    inputDiferenciaMensual.value = '';
                }
            });

            function calcularValores() {
                const sueldoAnt = parseFloat(inputSueldoAnterior.value) || 0;
                const porcentaje = parseFloat(inputPorcentaje.value) || 0;
                const meses = parseInt(inputMeses.value) || 1;

                if (sueldoAnt > 0) {
                    const sueldoNuevoMensual = sueldoAnt * (1 + (porcentaje / 100));
                    inputSueldoNuevo.value = sueldoNuevoMensual.toFixed(2);

                    const diferenciaMensual = sueldoNuevoMensual - sueldoAnt;
                    inputDiferenciaMensual.value = diferenciaMensual.toFixed(2);

                    const pagoRetroactivo = diferenciaMensual * meses;
                    inputMontoPagar.value = pagoRetroactivo.toFixed(2);
                } else {
                    inputSueldoNuevo.value = '';
                    inputDiferenciaMensual.value = '';
                    inputMontoPagar.value = '';
                }
            }

            inputSueldoAnterior.addEventListener('input', calcularValores);
            inputPorcentaje.addEventListener('input', calcularValores);
            inputMeses.addEventListener('input', calcularValores);

            inputSueldoNuevo.addEventListener('input', function() {
                const sueldoAnt = parseFloat(inputSueldoAnterior.value) || 0;
                const sueldoNuevo = parseFloat(inputSueldoNuevo.value) || 0;
                const meses = parseInt(inputMeses.value) || 1;
                if (sueldoNuevo >= sueldoAnt) {
                    const diff = sueldoNuevo - sueldoAnt;
                    inputDiferenciaMensual.value = diff.toFixed(2);
                    inputMontoPagar.value = (diff * meses).toFixed(2);
                }
            });
        });
    </script>
</x-layouts::app>
