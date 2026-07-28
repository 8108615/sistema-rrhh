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
            <flux:subheading>Complete los datos del empleado y las fechas para calcular los beneficios sociales.</flux:subheading>
        </div>
        <div>
            <a href="{{ route('admin.finiquitos.index') }}">
                <flux:button variant="subtle" icon="arrow-left">Volver</flux:button>
            </a>
        </div>
    </div>

    <flux:separator variant="subtle" class="mb-6" />

    <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-6 shadow-sm">
        <form action="{{ route('admin.finiquitos.store') }}" method="POST" class="space-y-6">
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
                                    data-salario="{{ $empleado->salario ?? 0 }}">
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

                <!-- Fecha de Ingreso (Autocompletada) -->
                <div>
                    <flux:input name="fecha_ingreso" id="fecha_ingreso" type="date" label="Fecha de Ingreso" readonly required />
                </div>

                <!-- Fecha de Retiro -->
                <div>
                    <flux:input name="fecha_retiro" id="fecha_retiro" type="date" label="Fecha de Retiro / Desvinculación" required />
                </div>

                <!-- Último Salario Base (Autocompletado) -->
                <div>
                    <flux:input name="ultimo_salario" id="ultimo_salario" type="number" step="0.01" label="Último Salario Base ({{ $simboloMoneda }})" required />
                </div>

                <!-- Promedio de los últimos 3 salarios (Autocompletado o editable) -->
                <div>
                    <flux:input name="promedio_tres_salarios" id="promedio_tres_salarios" type="number" step="0.01" label="Promedio Indemnizable (3 meses) ({{ $simboloMoneda }})" required />
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
                <flux:button type="submit" variant="primary" color="blue">Calcular y Guardar Finiquito</flux:button>
            </div>
        </form>
    </div>

    <!-- Script para autocompletar fecha y salarios al seleccionar el empleado -->
    <script>
        document.getElementById('empleado_id').addEventListener('change', function() {
            var selectedOption = this.options[this.selectedIndex];
            var fechaIngreso = selectedOption.getAttribute('data-ingreso');
            var salarioBase = selectedOption.getAttribute('data-salario');

            document.getElementById('fecha_ingreso').value = fechaIngreso ? fechaIngreso.split('T')[0] : '';
            document.getElementById('ultimo_salario').value = salarioBase ? salarioBase : '';
            document.getElementById('promedio_tres_salarios').value = salarioBase ? salarioBase : '';
        });
    </script>
</x-layouts::app>
