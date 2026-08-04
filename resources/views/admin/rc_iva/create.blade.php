<x-layouts::app title="Nuevo Registro RC-IVA">
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Cabecera -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-zinc-100">Registrar Formulario 110 (RC-IVA)</h1>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Seleccione el empleado para autocompletar su sueldo.</p>
            </div>
            <div>
                <flux:button variant="subtle" href="{{ route('admin.rc_iva.index') }}" icon="arrow-left">
                    Volver
                </flux:button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Formulario Principal -->
            <div class="lg:col-span-2 bg-white dark:bg-zinc-900 p-6 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm">
                <form action="{{ route('admin.rc_iva.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="space-y-4">
                        <!-- Empleado -->
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Empleado</label>
                            <flux:select name="empleado_id" id="empleado_id" placeholder="Seleccione un empleado" required>
                                @foreach($empleados as $emp)
                                    <option value="{{ $emp->id }}" data-salario="{{ $emp->salario }}" {{ old('empleado_id') == $emp->id ? 'selected' : '' }}>
                                        {{ $emp->nombre }} {{ $emp->apellido }} (CI: {{ $emp->ci }})
                                    </option>
                                @endforeach
                            </flux:select>
                            @error('empleado_id')
                                <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Periodo Mes -->
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Periodo (Mes/Año)</label>
                            <flux:input name="periodo_mes" value="{{ old('periodo_mes', date('Y-m')) }}" placeholder="Ej: 2026-08" required />
                            @error('periodo_mes')
                                <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Sueldo Neto -->
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Sueldo Neto (Total ganado - AFPs)</label>
                            <flux:input type="number" step="0.01" name="sueldo_neto" id="sueldo_neto" value="{{ old('sueldo_neto', 0) }}" required />
                            @error('sueldo_neto')
                                <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Dos Salarios Mínimos (Exento) -->
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">2 Salarios Mínimos (Monto Exento Legal)</label>
                            <flux:input type="number" step="0.01" name="dos_salarios_minimos" id="dos_salarios_minimos" value="{{ old('dos_salarios_minimos', 6600) }}" required />
                            <span class="text-xs text-zinc-400 mt-1">Ajustado al Salario Mínimo Nacional vigente (Bs 3,300 × 2).</span>
                            @error('dos_salarios_minimos')
                                <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Total Facturas Presentadas -->
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Total Facturas Presentadas (F-110)</label>
                            <flux:input type="number" step="0.01" name="total_facturas_presentadas" id="total_facturas_presentadas" value="{{ old('total_facturas_presentadas', 0) }}" required />
                            @error('total_facturas_presentadas')
                                <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Saldo Fisco Periodo Anterior (Calculado y Bloqueado) -->
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Saldo a favor del Dependiente (Periodo Anterior)</label>
                            <flux:input type="number" step="0.01" name="saldo_fisco_periodo_anterior" id="saldo_fisco_periodo_anterior" value="{{ old('saldo_fisco_periodo_anterior', 0) }}" readonly class="bg-zinc-100 dark:bg-zinc-800/50 cursor-not-allowed font-semibold text-emerald-600 dark:text-emerald-400" />
                            <span class="text-xs text-zinc-400 mt-1">Calculado automáticamente según las facturas presentadas (No modificable).</span>
                            @error('saldo_fisco_periodo_anterior')
                                <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-zinc-200 dark:border-zinc-800">
                        <flux:button variant="subtle" href="{{ route('admin.rc_iva.index') }}">Cancelar</flux:button>
                        <flux:button variant="primary" type="submit">Guardar y Calcular</flux:button>
                    </div>
                </form>
            </div>

            <!-- Panel Lateral de Resumen en Vivo -->
            <div class="bg-zinc-50 dark:bg-zinc-900/50 p-6 rounded-xl border border-zinc-200 dark:border-zinc-800 h-fit space-y-4">
                <h3 class="text-base font-bold text-zinc-800 dark:text-zinc-200 border-b border-zinc-200 dark:border-zinc-800 pb-2">
                    Resumen del Cálculo
                </h3>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-zinc-500">Base Imponible:</span>
                        <span id="preview-base" class="font-semibold text-zinc-800 dark:text-zinc-200">Bs 0.00</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-zinc-500">Impuesto RC-IVA (13%):</span>
                        <span id="preview-impuesto" class="font-semibold text-zinc-800 dark:text-zinc-200">Bs 0.00</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-zinc-500">Crédito Facturas (13%):</span>
                        <span id="preview-credito" class="font-semibold text-emerald-600 dark:text-emerald-400">Bs 0.00</span>
                    </div>

                    <div class="pt-3 border-t border-zinc-200 dark:border-zinc-800 space-y-2">
                        <div id="resultado-retencion-box" class="p-3 rounded-lg bg-blue-50 dark:bg-blue-950/40 border border-blue-200 dark:border-blue-900">
                            <p class="text-xs text-blue-600 dark:text-blue-400 font-medium">Impuesto a Retener (Fisco):</p>
                            <p id="preview-retenido" class="text-lg font-bold text-blue-700 dark:text-blue-300">Bs 0.00</p>
                        </div>
                        <div id="resultado-favor-box" class="p-3 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-900 hidden">
                            <p class="text-xs text-emerald-600 dark:text-emerald-400 font-medium">Saldo a Favor Dependiente:</p>
                            <p id="preview-favor" class="text-lg font-bold text-emerald-700 dark:text-emerald-300">Bs 0.00</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script de Autocompletado y Cálculo en Vivo -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectEmpleado = document.getElementById('empleado_id');
            const inputSueldo = document.getElementById('sueldo_neto');
            const inputDosMinimos = document.getElementById('dos_salarios_minimos');
            const inputFacturas = document.getElementById('total_facturas_presentadas');
            const inputAnterior = document.getElementById('saldo_fisco_periodo_anterior');

            const prevBase = document.getElementById('preview-base');
            const prevImpuesto = document.getElementById('preview-impuesto');
            const prevCredito = document.getElementById('preview-credito');
            const prevRetenido = document.getElementById('preview-retenido');
            const prevFavor = document.getElementById('preview-favor');
            const boxRetenido = document.getElementById('resultado-retencion-box');
            const boxFavor = document.getElementById('resultado-favor-box');

            function calcularRcIva() {
                const sueldoNeto = parseFloat(inputSueldo.value) || 0;
                const dosMinimos = parseFloat(inputDosMinimos.value) || 0;
                const totalFacturas = parseFloat(inputFacturas.value) || 0;

                const baseImponible = Math.max(0, sueldoNeto - dosMinimos);
                const impuestoRcIva = baseImponible * 0.13;
                const creditoFacturas = totalFacturas * 0.13;

                // Aquí calculamos el subtotal directamente restando el impuesto menos el crédito fiscal de las facturas
                const subtotal = impuestoRcIva - creditoFacturas;

                let retenido = 0;
                let aFavor = 0;

                if (subtotal > 0) {
                    retenido = subtotal;
                    aFavor = 0;
                    boxRetenido.classList.remove('hidden');
                    boxFavor.classList.add('hidden');
                } else {
                    retenido = 0;
                    aFavor = Math.abs(subtotal);
                    boxFavor.classList.remove('hidden');
                    boxRetenido.classList.add('hidden');
                }

                // Actualizamos las vistas previas y asignamos el valor automáticamente al input bloqueado
                prevBase.textContent = 'Bs ' + baseImponible.toFixed(2);
                prevImpuesto.textContent = 'Bs ' + impuestoRcIva.toFixed(2);
                prevCredito.textContent = 'Bs ' + creditoFacturas.toFixed(2);
                prevRetenido.textContent = 'Bs ' + retenido.toFixed(2);
                prevFavor.textContent = 'Bs ' + aFavor.toFixed(2);

                // Inyectamos el resultado directamente en el campo de saldo anterior (que ahora actúa como saldo acumulado/fisco)
                inputAnterior.value = aFavor.toFixed(2);
            }

            if (selectEmpleado) {
                selectEmpleado.addEventListener('change', function () {
                    const selectedOption = this.options ? this.options[this.selectedIndex] : null;
                    if (selectedOption) {
                        const salario = selectedOption.getAttribute('data-salario');
                        if (salario !== null) {
                            inputSueldo.value = salario;
                        }
                        calcularRcIva();
                    }
                });
            }

            [inputSueldo, inputDosMinimos, inputFacturas].forEach(input => {
                if (input) {
                    input.addEventListener('input', calcularRcIva);
                }
            });

            calcularRcIva();
        });
    </script>
</x-layouts::app>
