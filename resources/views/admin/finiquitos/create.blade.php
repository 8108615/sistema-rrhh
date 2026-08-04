<x-layouts::app title="Nuevo Finiquito">
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

    <div class="relative mb-6 w-full flex justify-between items-center">
        <div>
            <flux:heading size="xl" level="1">Registrar Nuevo Finiquito</flux:heading>
            <flux:subheading>Complete los datos para calcular los beneficios sociales incluyendo el saldo de vacaciones y aguinaldo.</flux:subheading>
        </div>
        <div>
            <a href="{{ route('admin.finiquitos.index') }}">
                <flux:button variant="subtle" icon="arrow-left">Volver</flux:button>
            </a>
        </div>
    </div>

    <flux:separator variant="subtle" class="mb-6" />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Formulario Principal -->
        <div class="lg:col-span-2 bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6 shadow-sm">
            <form action="{{ route('admin.finiquitos.store') }}" method="POST" class="space-y-6" id="form-finiquito">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Selección de Empleado -->
                    <div>
                        <flux:field>
                            <flux:label>Empleado</flux:label>
                            <select name="empleado_id" id="empleado_id" class="w-full rounded-lg border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 px-3 py-2 text-sm text-gray-700 dark:text-zinc-200 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="">Seleccione un empleado...</option>
                                @foreach ($empleados as $empleado)
                                    <option value="{{ $empleado->id }}"
                                        data-ingreso="{{ $empleado->fecha_ingreso }}"
                                        data-salario="{{ $empleado->salario ?? 0 }}"
                                        data-vacaciones-disponibles="{{ $empleado->vacaciones_disponibles ?? 0 }}">
                                        {{ $empleado->nombre }} {{ $empleado->apellido }} (CI: {{ $empleado->ci }})
                                    </option>
                                @endforeach
                            </select>
                            <flux:error name="empleado_id" />
                        </flux:field>
                    </div>

                    <!-- Causal de Retiro -->
                    <div>
                        <flux:field>
                            <flux:label>Causal de Retiro</flux:label>
                            <select name="causal_retiro" id="causal_retiro" class="w-full rounded-lg border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 px-3 py-2 text-sm text-gray-700 dark:text-zinc-200 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="">Seleccione la causal...</option>
                                <option value="Despido Injustificado">Despido Injustificado</option>
                                <option value="Renuncia Voluntaria">Renuncia Voluntaria</option>
                                <option value="Abandono de Trabajo">Abandono de Trabajo</option>
                                <option value="Jubilación">Jubilación</option>
                                <option value="Mutuo Acuerdo">Mutuo Acuerdo</option>
                            </select>
                            <flux:error name="causal_retiro" />
                        </flux:field>
                    </div>

                    <!-- Fecha de Ingreso -->
                    <div>
                        <flux:input name="fecha_ingreso" id="fecha_ingreso" type="date" label="Fecha de Ingreso" readonly required />
                    </div>

                    <!-- Fecha de Retiro -->
                    <div>
                        <flux:input name="fecha_retiro" id="fecha_retiro" type="date" label="Fecha de Retiro / Desvinculación" required />
                    </div>

                    <!-- Último Salario Base -->
                    <div>
                        <flux:input name="ultimo_salario" id="ultimo_salario" type="number" step="0.01" label="Último Salario Base ({{ $simboloMoneda }})" required />
                    </div>

                    <!-- Promedio de los últimos 3 salarios -->
                    <div>
                        <flux:input name="promedio_tres_salarios" id="promedio_tres_salarios" type="number" step="0.01" label="Promedio Indemnizable (3 meses) ({{ $simboloMoneda }})" required />
                    </div>

                    <!-- Montos Calculados -->
                    <div>
                        <flux:input name="monto_indemnizacion" id="monto_indemnizacion" type="number" step="0.01" label="Monto Indemnización ({{ $simboloMoneda }})" required />
                    </div>

                    <div>
                        <flux:input name="monto_desahucio" id="monto_desahucio" type="number" step="0.01" label="Monto Desahucio ({{ $simboloMoneda }})" required />
                    </div>

                    <div>
                        <flux:input name="monto_aguinaldo" id="monto_aguinaldo" type="number" step="0.01" label="Aguinaldo Proporcional ({{ $simboloMoneda }})" required />
                    </div>

                    <div>
                        <flux:input name="monto_vacacion" id="monto_vacacion" type="number" step="0.01" label="Monto Vacación ({{ $simboloMoneda }})" required />
                    </div>

                    <!-- Observaciones -->
                    <div class="md:col-span-2">
                        <flux:field>
                            <flux:label>Observaciones (Opcional)</flux:label>
                            <textarea name="observaciones" rows="3" class="w-full rounded-lg border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 px-3 py-2 text-sm text-gray-700 dark:text-zinc-200 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Detalles adicionales sobre el finiquito..."></textarea>
                            <flux:error name="observaciones" />
                        </flux:field>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-zinc-700">
                    <a href="{{ route('admin.finiquitos.index') }}">
                        <flux:button type="button" variant="subtle">Cancelar</flux:button>
                    </a>
                    <flux:button type="submit" variant="primary" color="blue">Guardar Finiquito</flux:button>
                </div>
            </form>
        </div>

        <!-- Panel Lateral de Resumen y Cálculo en Vivo -->
        <div class="bg-zinc-50 dark:bg-zinc-900/50 p-6 rounded-xl border border-zinc-200 dark:border-zinc-800 h-fit space-y-4">
            <h3 class="text-base font-bold text-zinc-800 dark:text-zinc-200 border-b border-zinc-200 dark:border-zinc-800 pb-2">
                Resumen de Cálculo (Ley Boliviana)
            </h3>

            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-zinc-500">Tiempo de Servicio:</span>
                    <span id="lbl-tiempo" class="font-semibold text-zinc-800 dark:text-zinc-200">0 años, 0 meses, 0 días</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-zinc-500">Años decimales:</span>
                    <span id="lbl-anios-decimal" class="font-semibold text-zinc-800 dark:text-zinc-200">0.00</span>
                </div>
                
                <!-- Información detallada del saldo de vacaciones -->
                <div class="p-2 bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-900 rounded-lg text-xs space-y-1">
                    <div class="flex justify-between text-amber-700 dark:text-amber-300">
                        <span>Días Vacación Disponibles:</span>
                        <span id="lbl-dias-vacacion" class="font-bold">0.00 días</span>
                    </div>
                </div>

                <div class="pt-3 border-t border-zinc-200 dark:border-zinc-800 space-y-2">
                    <div class="flex justify-between">
                        <span class="text-zinc-500">Indemnización:</span>
                        <span id="lbl-indemnizacion" class="font-semibold text-emerald-600 dark:text-emerald-400">{{ $simboloMoneda }} 0.00</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-zinc-500">Desahucio:</span>
                        <span id="lbl-desahucio" class="font-semibold text-zinc-800 dark:text-zinc-200">{{ $simboloMoneda }} 0.00</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-zinc-500">Aguinaldo Proporcional:</span>
                        <span id="lbl-aguinaldo" class="font-semibold text-zinc-800 dark:text-zinc-200">{{ $simboloMoneda }} 0.00</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-zinc-500">Vacación Pagar:</span>
                        <span id="lbl-vacacion" class="font-semibold text-zinc-800 dark:text-zinc-200">{{ $simboloMoneda }} 0.00</span>
                    </div>
                </div>

                <div class="pt-4 border-t border-zinc-200 dark:border-zinc-800">
                    <div class="p-3 rounded-lg bg-blue-50 dark:bg-blue-950/40 border border-blue-200 dark:border-blue-900 flex justify-between items-center">
                        <span class="text-xs text-blue-600 dark:text-blue-400 font-bold uppercase">Total Beneficios:</span>
                        <span id="lbl-total" class="text-lg font-bold text-blue-700 dark:text-blue-300">{{ $simboloMoneda }} 0.00</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script de cálculo automático en tiempo real -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectEmpleado = document.getElementById('empleado_id');
            const inputIngreso = document.getElementById('fecha_ingreso');
            const inputRetiro = document.getElementById('fecha_retiro');
            const selectCausal = document.getElementById('causal_retiro');
            const inputUltimoSalario = document.getElementById('ultimo_salario');
            const inputPromedio = document.getElementById('promedio_tres_salarios');
            
            const inputIndemnizacion = document.getElementById('monto_indemnizacion');
            const inputDesahucio = document.getElementById('monto_desahucio');
            const inputAguinaldo = document.getElementById('monto_aguinaldo');
            const inputVacacion = document.getElementById('monto_vacacion');

            const lblTiempo = document.getElementById('lbl-tiempo');
            const lblAniosDecimal = document.getElementById('lbl-anios-decimal');
            const lblDiasVacacion = document.getElementById('lbl-dias-vacacion');
            const lblIndemnizacion = document.getElementById('lbl-indemnizacion');
            const lblDesahucio = document.getElementById('lbl-desahucio');
            const lblAguinaldo = document.getElementById('lbl-aguinaldo');
            const lblVacacion = document.getElementById('lbl-vacacion');
            const lblTotal = document.getElementById('lbl-total');
            const simbolo = "{{ $simboloMoneda }}";

            let vacacionesDisponiblesGlobal = 0;

            function calcularFiniquito() {
                const fechaIngresoVal = inputIngreso.value;
                const fechaRetiroVal = inputRetiro.value;
                const ultimoSalario = parseFloat(inputUltimoSalario.value) || 0;
                const promedio = parseFloat(inputPromedio.value) || 0;
                const causal = selectCausal.value;

                if (!fechaIngresoVal || !fechaRetiroVal) return;

                const ingreso = new Date(fechaIngresoVal);
                const retiro = new Date(fechaRetiroVal);

                if (retiro < ingreso) return;

                let años = retiro.getFullYear() - ingreso.getFullYear();
                let meses = retiro.getMonth() - ingreso.getMonth();
                let dias = retiro.getDate() - ingreso.getDate();

                if (dias < 0) {
                    meses--;
                    const mesAnterior = new Date(retiro.getFullYear(), retiro.getMonth(), 0);
                    dias += mesAnterior.getDate();
                }
                if (meses < 0) {
                    años--;
                    meses += 12;
                }

                const anosServicioDecimal = años + (meses / 12) + (dias / 360);

                // 1. Indemnización
                const montoIndemnizacion = promedio * anosServicioDecimal;

                // 2. Desahucio
                const montoDesahucio = (causal === 'Despido Injustificado') ? (ultimoSalario * 3) : 0;

                // 3. Aguinaldo Proporcional
                const anioRetiro = retiro.getFullYear();
                let inicioAnio = new Date(anioRetiro, 0, 1);
                let fechaCalculoAguinaldo = ingreso > inicioAnio ? ingreso : inicioAnio;

                let mesesAguinaldo = (retiro.getFullYear() - fechaCalculoAguinaldo.getFullYear()) * 12 + (retiro.getMonth() - fechaCalculoAguinaldo.getMonth());
                let diasAguinaldo = retiro.getDate() - fechaCalculoAguinaldo.getDate();
                if (diasAguinaldo < 0) {
                    mesesAguinaldo--;
                    diasAguinaldo += 30;
                }
                if (mesesAguinaldo < 0) mesesAguinaldo = 0;
                if (diasAguinaldo < 0) diasAguinaldo = 0;

                const montoAguinaldo = (ultimoSalario / 12) * mesesAguinaldo + ((ultimoSalario / 12 / 30) * diasAguinaldo);

                // 4. Vacación Pagar: SOLO SI CUMPLE AL MENOS 1 AÑO DE SERVICIO
                let montoVacacion = 0;
                let diasVacacionMostrados = 0;
                
                if (anosServicioDecimal >= 1) {
                    montoVacacion = (ultimoSalario / 30) * vacacionesDisponiblesGlobal;
                    diasVacacionMostrados = vacacionesDisponiblesGlobal;
                }

                // Total
                const total = montoIndemnizacion + montoDesahucio + montoAguinaldo + montoVacacion;

                // Actualizar Inputs
                inputIndemnizacion.value = montoIndemnizacion.toFixed(2);
                inputDesahucio.value = montoDesahucio.toFixed(2);
                inputAguinaldo.value = montoAguinaldo.toFixed(2);
                inputVacacion.value = montoVacacion.toFixed(2);

                // Actualizar Panel Lateral
                lblTiempo.textContent = `${años} años, ${meses} meses, ${dias} días`;
                lblAniosDecimal.textContent = anosServicioDecimal.toFixed(4);
                lblDiasVacacion.textContent = `${diasVacacionMostrados.toFixed(2)} días`;
                lblIndemnizacion.textContent = `${simbolo} ${montoIndemnizacion.toFixed(2)}`;
                lblDesahucio.textContent = `${simbolo} ${montoDesahucio.toFixed(2)}`;
                lblAguinaldo.textContent = `${simbolo} ${montoAguinaldo.toFixed(2)}`;
                lblVacacion.textContent = `${simbolo} ${montoVacacion.toFixed(2)}`;
                lblTotal.textContent = `${simbolo} ${total.toFixed(2)}`;
            }

            selectEmpleado.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const fechaIngreso = selectedOption.getAttribute('data-ingreso');
                const salarioBase = selectedOption.getAttribute('data-salario');
                vacacionesDisponiblesGlobal = parseFloat(selectedOption.getAttribute('data-vacaciones-disponibles')) || 0;

                inputIngreso.value = fechaIngreso ? fechaIngreso.split('T')[0] : '';
                inputUltimoSalario.value = salarioBase ? salarioBase : '';
                inputPromedio.value = salarioBase ? salarioBase : '';
                
                if (!inputRetiro.value) {
                    const hoy = new Date().toISOString().split('T')[0];
                    inputRetiro.value = hoy;
                }

                calcularFiniquito();
            });

            [inputRetiro, selectCausal, inputUltimoSalario, inputPromedio, inputIngreso, inputVacacion].forEach(element => {
                element.addEventListener('input', calcularFiniquito);
                element.addEventListener('change', calcularFiniquito);
            });
        });
    </script>
</x-layouts::app>