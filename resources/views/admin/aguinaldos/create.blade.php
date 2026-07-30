<x-layouts::app title="Registrar Nuevo Beneficio">
    <div class="relative mb-6 w-full flex justify-between items-center">
        <div>
            <flux:heading size="xl" level="1">Registrar Nuevo Beneficio</flux:heading>
            <flux:subheading>Registra manualmente o calcula el aguinaldo o doble aguinaldo de un empleado.</flux:subheading>
        </div>
        <div>
            <a href="{{ route('admin.aguinaldos.index') }}">
                <flux:button variant="subtle" icon="arrow-left">Volver</flux:button>
            </a>
        </div>
    </div>

    <flux:separator variant="subtle" class="mb-6" />

    <div class="rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-6 shadow-sm w-full">
        <form action="{{ route('admin.aguinaldos.store') }}" method="POST" class="flex flex-col gap-6" id="beneficioForm">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="empleado_id" class="block text-xs font-bold text-gray-500 uppercase mb-1">Empleado (*)</label>
                    <select id="empleado_id" name="empleado_id" required class="w-full rounded-lg border border-gray-300 bg-white py-2.5 px-3 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white">
                        <option value="">Seleccione un empleado...</option>
                        @foreach($empleados as $emp)
                            <option value="{{ $emp->id }}" data-salario="{{ $emp->salario ?? $emp->sueldo_base ?? 0 }}" {{ old('empleado_id') == $emp->id ? 'selected' : '' }}>
                                {{ $emp->nombre }} {{ $emp->apellido }} (CI: {{ $emp->ci }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="tipo" class="block text-xs font-bold text-gray-500 uppercase mb-1">Tipo de Beneficio (*)</label>
                    <select id="tipo" name="tipo" required class="w-full rounded-lg border border-gray-300 bg-white py-2.5 px-3 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white">
                        <option value="Aguinaldo" {{ old('tipo') == 'Aguinaldo' ? 'selected' : '' }}>Aguinaldo</option>
                        <option value="Doble Aguinaldo" {{ old('tipo') == 'Doble Aguinaldo' ? 'selected' : '' }}>Doble Aguinaldo</option>
                    </select>
                </div>

                <div>
                    <label for="ultimo_salario" class="block text-xs font-bold text-gray-500 uppercase mb-1">Último Salario Base ({{ $simboloMoneda }})</label>
                    <input type="number" step="0.01" id="ultimo_salario" name="ultimo_salario" value="{{ old('ultimo_salario', 0) }}" readonly class="w-full rounded-lg border border-gray-300 bg-gray-100 py-2 px-3 text-sm text-gray-700 shadow-sm dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-400 font-mono">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="gestion" class="block text-xs font-bold text-gray-500 uppercase mb-1">Gestión (Año) (*)</label>
                    <select id="gestion" name="gestion" required class="w-full rounded-lg border border-gray-300 bg-white py-2.5 px-3 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white">
                        @php
                            $anioActual = date('Y');
                            $anioInicio = $anioActual - 5;
                            $anioFin = $anioActual + 3;
                        @endphp
                        @for ($i = $anioFin; $i >= $anioInicio; $i--)
                            <option value="{{ $i }}" {{ old('gestion', $anioActual) == $i ? 'selected' : '' }}>
                                {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label for="meses_trabajados" class="block text-xs font-bold text-gray-500 uppercase mb-1" id="label_meses">Meses Trabajados (*)</label>
                    <input type="number" id="meses_trabajados" name="meses_trabajados" min="1" max="12" value="{{ old('meses_trabajados', 12) }}" required class="w-full rounded-lg border border-gray-300 bg-white py-2 px-3 text-sm text-gray-900 shadow-sm focus:border-blue-500 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white">
                </div>

                <div>
                    <div id="contenedor_dias">
                        <label for="dias_trabajados" class="block text-xs font-bold text-gray-500 uppercase mb-1">Días Trabajados (Fracción) (*)</label>
                        <input type="number" id="dias_trabajados" name="dias_trabajados" min="0" max="360" value="{{ old('dias_trabajados', 360) }}" required class="w-full rounded-lg border border-gray-300 bg-white py-2 px-3 text-sm text-gray-900 shadow-sm focus:border-blue-500 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white">
                        <span class="text-[11px] text-gray-400 mt-1 block">Días totales para el cálculo (hasta 360).</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="monto_pagar" class="block text-xs font-bold text-gray-500 uppercase mb-1">Monto Final a Pagar ({{ $simboloMoneda }}) (*)</label>
                    <input type="number" step="0.01" id="monto_pagar" name="monto_pagar" value="{{ old('monto_pagar', 0) }}" required class="w-full rounded-lg border border-gray-300 bg-white py-2 px-3 text-sm text-gray-900 shadow-sm focus:border-blue-500 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white font-mono font-bold text-emerald-600">
                </div>

                <div>
                    <label for="fecha_pago" class="block text-xs font-bold text-gray-500 uppercase mb-1">Fecha de Pago</label>
                    <input type="date" id="fecha_pago" name="fecha_pago" value="{{ old('fecha_pago', date('Y-m-d')) }}" class="w-full rounded-lg border border-gray-300 bg-white py-2 px-3 text-sm text-gray-900 shadow-sm focus:border-blue-500 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white">
                </div>

                <div>
                    <label for="estado" class="block text-xs font-bold text-gray-500 uppercase mb-1">Estado del Pago (*)</label>
                    <select id="estado" name="estado" required class="w-full rounded-lg border border-gray-300 bg-white py-2.5 px-3 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white">
                        <option value="Pendiente" {{ old('estado') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="Pagado" {{ old('estado') == 'Pagado' ? 'selected' : '' }}>Pagado</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="observaciones" class="block text-xs font-bold text-gray-500 uppercase mb-1">Observaciones</label>
                <input type="text" id="observaciones" name="observaciones" value="{{ old('observaciones') }}" class="w-full rounded-lg border border-gray-300 bg-white py-2.5 px-3 text-sm text-gray-900 shadow-sm focus:border-blue-500 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white" placeholder="Opcional...">
            </div>

            <div class="flex items-center justify-end gap-3 mt-4">
                <a href="{{ route('admin.aguinaldos.index') }}">
                    <flux:button variant="subtle">Cancelar</flux:button>
                </a>
                <flux:button type="submit" variant="primary" icon="arrow-down-tray" color="blue">Guardar Registro</flux:button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const empleadoSelect = document.getElementById('empleado_id');
            const tipoSelect = document.getElementById('tipo');
            const salarioInput = document.getElementById('ultimo_salario');
            const mesesInput = document.getElementById('meses_trabajados');
            const diasInput = document.getElementById('dias_trabajados');
            const montoInput = document.getElementById('monto_pagar');

            let procesando = false; // Evita bucles infinitos en la sincronización

            function calcularBeneficio() {
                if (!empleadoSelect || !salarioInput || !montoInput) return;

                const selectedOption = empleadoSelect.options[empleadoSelect.selectedIndex];
                const salario = parseFloat(selectedOption ? selectedOption.getAttribute('data-salario') : 0) || 0;
                salarioInput.value = salario.toFixed(2);

                // Tomamos los días actuales para calcular el monto final proporcional (Fórmula estándar: / 360)
                const diasTotales = parseFloat(diasInput ? diasInput.value : 0) || 0;
                const totalAguinaldo = (salario / 360) * diasTotales;
                montoInput.value = totalAguinaldo.toFixed(2);
            }

            // Sincronización: Si el usuario escribe en MESES -> Actualiza DÍAS
            if (mesesInput) {
                mesesInput.addEventListener('input', function() {
                    if (procesando) return;
                    procesando = true;

                    const meses = parseFloat(mesesInput.value) || 0;
                    if (diasInput) {
                        diasInput.value = Math.round(meses * 30);
                    }
                    calcularBeneficio();

                    procesando = false;
                });
            }

            // Sincronización: Si el usuario escribe en DÍAS -> Actualiza MESES
            if (diasInput) {
                diasInput.addEventListener('input', function() {
                    if (procesando) return;
                    procesando = true;

                    const dias = parseFloat(diasInput.value) || 0;
                    if (mesesInput) {
                        const mesesEquivalentes = dias / 30;
                        mesesInput.value = mesesEquivalentes > 0 ? mesesEquivalentes.toFixed(1) : 0;
                    }
                    calcularBeneficio();

                    procesando = false;
                });
            }

            // Eventos generales
            if (empleadoSelect) empleadoSelect.addEventListener('change', calcularBeneficio);
            if (tipoSelect) tipoSelect.addEventListener('change', calcularBeneficio);

            // Calcular al iniciar por si hay valores predeterminados
            calcularBeneficio();
        });
    </script>
</x-layouts::app>
