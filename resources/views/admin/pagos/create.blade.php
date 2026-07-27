<x-layouts::app title="Registrar Pago a Empleado">
    <div class="relative mb-6 w-full flex justify-between items-center">
        <div>
            <flux:heading size="xl" level="1">Nuevo Registro de Pago</flux:heading>
            <flux:subheading>Complete los datos del sueldo, bonos y descuentos correspondientes.</flux:subheading>
        </div>
        <div>
            <a href="{{ route('admin.pagos.index') }}">
                <flux:button variant="subtle" icon="arrow-left">Volver</flux:button>
            </a>
        </div>
    </div>

    <flux:separator variant="subtle" class="mb-6" />

    <div class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl p-6 shadow-sm">
        <form action="{{ route('admin.pagos.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Seleccionar Empleado -->
                <div class="md:col-span-2">
                    <flux:field>
                        <flux:label>Empleado</flux:label>
                        <select name="empleado_id" id="empleado_id" class="w-full rounded-lg border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-gray-800 dark:text-zinc-200 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500" required>
                            <option value="">-- Seleccione un empleado --</option>
                            @foreach ($empleados as $empleado)
                                <option value="{{ $empleado->id }}" 
                                        data-salario="{{ $empleado->salario ?? 0 }}" 
                                        data-area="{{ $empleado->area->nombre ?? 'Sin área asignada' }}" 
                                        {{ old('empleado_id') == $empleado->id ? 'selected' : '' }}>
                                    {{ $empleado->nombre }} {{ $empleado->apellido }} (CI: {{ $empleado->ci }})
                                </option>
                            @endforeach
                        </select>
                        <flux:error name="empleado_id" />
                    </flux:field>
                </div>

                <!-- Campo Área (Automático y Solo Lectura) -->
                <div>
                    <flux:field>
                        <flux:label>Área de Trabajo</flux:label>
                        <flux:input id="txt_area_empleado" type="text" value="Seleccione un empleado" readonly class="bg-gray-50 dark:bg-zinc-900 text-gray-500" />
                    </flux:field>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Método de Pago -->
                <div>
                    <flux:field>
                        <flux:label>Método de Pago</flux:label>
                        <select name="metodo_pago" class="w-full rounded-lg border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-gray-800 dark:text-zinc-200 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500" required>
                            <option value="Transferencia" {{ old('metodo_pago') == 'Transferencia' ? 'selected' : '' }}>Transferencia</option>
                            <option value="Efectivo" {{ old('metodo_pago') == 'Efectivo' ? 'selected' : '' }}>Efectivo</option>
                            <option value="Cheque" {{ old('metodo_pago') == 'Cheque' ? 'selected' : '' }}>Cheque</option>
                        </select>
                        <flux:error name="metodo_pago" />
                    </flux:field>
                </div>

                <!-- Mes -->
                <div>
                    <flux:field>
                        <flux:label>Mes Correspondiente</flux:label>
                        <select name="mes" class="w-full rounded-lg border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-gray-800 dark:text-zinc-200 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500" required>
                            @php
                                $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                                $mesActual = ucfirst(\Carbon\Carbon::now()->locale('es')->monthName);
                            @endphp
                            @foreach ($meses as $m)
                                <option value="{{ $m }}" {{ (old('mes') == $m || (!old('mes') && $mesActual == $m)) ? 'selected' : '' }}>{{ $m }}</option>
                            @endforeach
                        </select>
                        <flux:error name="mes" />
                    </flux:field>
                </div>

                <!-- Año -->
                <div>
                    <flux:field>
                        <flux:label>Año</flux:label>
                        <flux:input name="anio" type="number" value="{{ old('anio', date('Y')) }}" required />
                        <flux:error name="anio" />
                    </flux:field>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Fecha de Pago -->
                <div>
                    <flux:field>
                        <flux:label>Fecha de Emisión / Pago</flux:label>
                        <flux:input name="fecha_pago" type="date" value="{{ old('fecha_pago', date('Y-m-d')) }}" required />
                        <flux:error name="fecha_pago" />
                    </flux:field>
                </div>

                <!-- Nro Comprobante -->
                <div class="md:col-span-2">
                    <flux:field>
                        <flux:label>Nro. Comprobante / Recibo</flux:label>
                        <flux:input name="nro_comprobante" type="text" placeholder="Ej. CMP-00123" value="{{ old('nro_comprobante') }}" />
                        <flux:error name="nro_comprobante" />
                    </flux:field>
                </div>
            </div>

            <flux:separator variant="subtle" class="my-6" />

            <flux:heading size="lg" class="mb-4">Desglose de Montos ({{ $simboloMoneda }})</flux:heading>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Salario Base -->
                <div>
                    <flux:field>
                        <flux:label>Salario Base</flux:label>
                        <flux:input name="salario_base" id="salario_base" type="number" step="0.01" min="0" value="{{ old('salario_base', 0) }}" required />
                        <flux:error name="salario_base" />
                    </flux:field>
                </div>

                <!-- Bonos -->
                <div>
                    <flux:field>
                        <flux:label>Bonos / Incentivos (+)</flux:label>
                        <flux:input name="bonos" id="bonos" type="number" step="0.01" min="0" value="{{ old('bonos', 0) }}" />
                        <flux:error name="bonos" />
                    </flux:field>
                </div>

                <!-- Descuento AFP -->
                <div>
                    <flux:field>
                        <flux:label>Descuento AFP (-)</flux:label>
                        <flux:input name="descuento_afp" id="descuento_afp" type="number" step="0.01" min="0" value="{{ old('descuento_afp', 0) }}" />
                        <flux:error name="descuento_afp" />
                    </flux:field>
                </div>

                <!-- Anticipos -->
                <div>
                    <flux:field>
                        <flux:label>Anticipos (-)</flux:label>
                        <flux:input name="anticipos" id="anticipos" type="number" step="0.01" min="0" value="{{ old('anticipos', 0) }}" />
                        <flux:error name="anticipos" />
                    </flux:field>
                </div>

                <!-- Otros Descuentos -->
                <div>
                    <flux:field>
                        <flux:label>Otros Descuentos (-)</flux:label>
                        <flux:input name="otros_descuentos" id="otros_descuentos" type="number" step="0.01" min="0" value="{{ old('otros_descuentos', 0) }}" />
                        <flux:error name="otros_descuentos" />
                    </flux:field>
                </div>
            </div>

            <!-- Total Líquido a Pagar calculado en tiempo real -->
            <div class="bg-blue-50 dark:bg-zinc-900/60 border border-blue-200 dark:border-zinc-700 rounded-xl p-4 mb-6 flex justify-between items-center">
                <div>
                    <span class="text-sm font-semibold text-gray-600 dark:text-zinc-400">Total Líquido a Pagar:</span>
                    <div class="text-xs text-gray-500">Calculado: (Salario Base + Bonos) - (AFP + Anticipos + Otros)</div>
                </div>
                <div class="text-2xl font-black text-emerald-600 dark:text-emerald-400" id="lblTotalPagar">
                    0.00 {{ $simboloMoneda }}
                </div>
            </div>

            <!-- Observaciones -->
            <div class="mb-6">
                <flux:field>
                    <flux:label>Observaciones</flux:label>
                    <textarea name="observaciones" rows="3" class="w-full rounded-lg border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-gray-800 dark:text-zinc-200 p-3 text-sm focus:ring-2 focus:ring-blue-500" placeholder="Observaciones adicionales sobre el pago...">{{ old('observaciones') }}</textarea>
                    <flux:error name="observaciones" />
                </flux:field>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.pagos.index') }}">
                    <flux:button variant="subtle">Cancelar</flux:button>
                </a>
                <flux:button variant="primary" type="submit" color="blue">Guardar Pago</flux:button>
            </div>
        </form>
    </div>

    <!-- Script optimizado para autocompletar área, salario, calcular AFP y actualizar el total -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectEmpleado = document.getElementById('empleado_id');
            const inputArea = document.getElementById('txt_area_empleado');
            const inputSalario = document.getElementById('salario_base');
            const inputBonos = document.getElementById('bonos');
            const inputAfp = document.getElementById('descuento_afp');
            const inputAnticipos = document.getElementById('anticipos');
            const inputOtros = document.getElementById('otros_descuentos');
            const lblTotalPagar = document.getElementById('lblTotalPagar');
            const simbolo = "{{ $simboloMoneda }}";

            // Autocompletar área, salario base y calcular sugerencia de AFP al seleccionar empleado
            selectEmpleado.addEventListener('change', function () {
                const selectedOption = this.options[this.selectedIndex];
                const salario = parseFloat(selectedOption.getAttribute('data-salario')) || 0;
                const area = selectedOption.getAttribute('data-area') || 'Seleccione un empleado';

                // Mostrar el área automáticamente
                inputArea.value = area;

                if (salario > 0) {
                    inputSalario.value = salario.toFixed(2);

                    // Cálculo referencial automático de la AFP (12.71% sobre el salario base)
                    const afpSugerida = salario * 0.1271;
                    inputAfp.value = afpSugerida.toFixed(2);
                } else {
                    inputSalario.value = '0.00';
                    inputAfp.value = '0.00';
                }
                calcularTotal();
            });

            // Función de cálculo general en tiempo real
            function calcularTotal() {
                const salario = parseFloat(inputSalario.value) || 0;
                const bonos = parseFloat(inputBonos.value) || 0;
                const afp = parseFloat(inputAfp.value) || 0;
                const anticipos = parseFloat(inputAnticipos.value) || 0;
                const otros = parseFloat(inputOtros.value) || 0;

                let total = (salario + bonos) - (afp + anticipos + otros);
                if (total < 0) total = 0;

                lblTotalPagar.textContent = total.toLocaleString('es-BO', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' ' + simbolo;
            }

            // Escuchar cambios de escritura (input) en todos los campos numéricos
            [inputSalario, inputBonos, inputAfp, inputAnticipos, inputOtros].forEach(input => {
                input.addEventListener('input', calcularTotal);
            });

            // Ejecutar al cargar la vista si hay un empleado seleccionado previamente (ej. validación fallida con old())
            if (selectEmpleado.value) {
                const selectedOption = selectEmpleado.options[selectEmpleado.selectedIndex];
                inputArea.value = selectedOption.getAttribute('data-area') || 'Sin área asignada';
            }
            calcularTotal();
        });
    </script>
</x-layouts::app>