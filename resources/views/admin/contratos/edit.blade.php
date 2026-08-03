<x-layouts::app title="Editar Contrato">
    <div class="max-w-full mx-auto space-y-6">
        <!-- Cabecera de la sección -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-zinc-100">Editar Contrato</h1>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Modifica la información necesaria del contrato seleccionado.</p>
            </div>
            <div>
                <flux:button variant="ghost" href="{{ route('admin.contratos.index') }}" icon="arrow-left">
                    Volver
                </flux:button>
            </div>
        </div>

        <!-- Alertas de errores de validación -->
        @if ($errors->any())
            <flux:callout variant="danger" icon="exclamation-triangle">
                <span class="font-medium">Por favor corrige los siguientes errores:</span>
                <ul class="mt-1 list-disc list-inside text-xs">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </flux:callout>
        @endif

        <!-- Formulario -->
        <div class="bg-white dark:bg-zinc-900 p-6 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm">
            <form action="{{ route('admin.contratos.update', $contrato->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Empleado -->
                    <div class="md:col-span-2">
                        <flux:select id="empleado_id" name="empleado_id" label="Empleado" placeholder="Selecciona un empleado..." required>
                            <option value="">Selecciona un empleado...</option>
                            @foreach ($empleados as $empleado)
                                <option value="{{ $empleado->id }}"
                                    data-fecha="{{ $empleado->fecha_ingreso }}"
                                    data-salario="{{ $empleado->salario }}"
                                    data-area="{{ optional($empleado->area)->nombre }}"
                                    {{ old('empleado_id', $contrato->empleado_id) == $empleado->id ? 'selected' : '' }}>
                                    {{ $empleado->nombre }} {{ $empleado->apellido }} - CI: {{ $empleado->ci }}
                                </option>
                            @endforeach
                        </flux:select>
                    </div>

                    <!-- Tipo de Contrato -->
                    <div>
                        <flux:select name="tipo_contrato" label="Tipo de Contrato" placeholder="Selecciona el tipo..." required>
                            <option value="Indefinido" {{ old('tipo_contrato', $contrato->tipo_contrato) == 'Indefinido' ? 'selected' : '' }}>Indefinido</option>
                            <option value="Plazo Fijo" {{ old('tipo_contrato', $contrato->tipo_contrato) == 'Plazo Fijo' ? 'selected' : '' }}>Plazo Fijo</option>
                            <option value="Consultoría por Producto" {{ old('tipo_contrato', $contrato->tipo_contrato) == 'Consultoría por Producto' ? 'selected' : '' }}>Consultoría por Producto</option>
                            <option value="Consultoría en Línea" {{ old('tipo_contrato', $contrato->tipo_contrato) == 'Consultoría en Línea' ? 'selected' : '' }}>Consultoría en Línea</option>
                            <option value="Pasantía" {{ old('tipo_contrato', $contrato->tipo_contrato) == 'Pasantía' ? 'selected' : '' }}>Pasantía</option>
                        </flux:select>
                    </div>

                    <!-- Cargo del Contrato -->
                    <div>
                        <flux:select id="cargo_contrato" name="cargo_contrato" label="Cargo / Área del Contrato" placeholder="Selecciona un área..." required>
                            <option value="">Selecciona un área...</option>
                            @foreach ($areas as $area)
                                <option value="{{ $area->nombre }}" {{ old('cargo_contrato', $contrato->cargo_contrato) == $area->nombre ? 'selected' : '' }}>
                                    {{ $area->nombre }}
                                </option>
                            @endforeach
                        </flux:select>
                    </div>

                    <!-- Fecha de Inicio -->
                    <div>
                        <flux:input type="date" id="fecha_inicio" name="fecha_inicio" label="Fecha de Inicio" value="{{ old('fecha_inicio', $contrato->fecha_inicio ? \Carbon\Carbon::parse($contrato->fecha_inicio)->format('Y-m-d') : '') }}" required />
                    </div>

                    <!-- Fecha de Fin -->
                    <div>
                        <flux:input type="date" name="fecha_fin" label="Fecha de Fin (Opcional Dejar en blanco si es indefinido)" value="{{ old('fecha_fin', $contrato->fecha_fin ? \Carbon\Carbon::parse($contrato->fecha_fin)->format('Y-m-d') : '') }}"/>
                    </div>

                    <!-- Salario Mensual (Deshabilitado visualmente + Campo oculto para enviar el valor) -->
                    <div>
                        <flux:input type="number" step="0.01" min="0" id="salario_mensual_disabled" label="Salario Mensual (Bs.) - [Heredado de Empleados]" value="{{ old('salario_mensual', $contrato->salario_mensual) }}" placeholder="0.00" disabled />
                        <input type="hidden" id="salario_mensual" name="salario_mensual" value="{{ old('salario_mensual', $contrato->salario_mensual) }}">
                    </div>

                    <!-- Estado -->
                    <div>
                        <flux:select name="estado" label="Estado del Contrato" required>
                            <option value="Activo" {{ old('estado', $contrato->estado) == 'Activo' ? 'selected' : '' }}>Activo</option>
                            <option value="Vencido" {{ old('estado', $contrato->estado) == 'Vencido' ? 'selected' : '' }}>Vencido</option>
                            <option value="Finalizado" {{ old('estado', $contrato->estado) == 'Finalizado' ? 'selected' : '' }}>Finalizado</option>
                            <option value="Anulado" {{ old('estado', $contrato->estado) == 'Anulado' ? 'selected' : '' }}>Anulado</option>
                        </flux:select>
                    </div>

                    <!-- Observaciones -->
                    <div class="md:col-span-2">
                        <flux:textarea name="observaciones" label="Observaciones" placeholder="Notas o detalles adicionales del contrato...">{{ old('observaciones', $contrato->observaciones) }}</flux:textarea>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-zinc-200 dark:border-zinc-800">
                    <flux:button variant="ghost" href="{{ route('admin.contratos.index') }}">Cancelar</flux:button>
                    <flux:button variant="primary" type="submit" color="green">Actualizar Contrato</flux:button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script para autocompletar datos del empleado seleccionado -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const empleadoSelect = document.querySelector('select[name="empleado_id"]');

            if (empleadoSelect) {
                empleadoSelect.addEventListener('change', function () {
                    const selectedOption = this.options[this.selectedIndex];

                    if (selectedOption && selectedOption.value) {
                        const fechaIngreso = selectedOption.getAttribute('data-fecha');
                        const salario = selectedOption.getAttribute('data-salario');
                        const areaNombre = selectedOption.getAttribute('data-area');

                        if (fechaIngreso) {
                            const fechaInput = document.querySelector('input[name="fecha_inicio"]');
                            if (fechaInput) fechaInput.value = fechaIngreso;
                        }

                        if (salario) {
                            // Input visual (deshabilitado)
                            const salarioDisabledInput = document.querySelector('#salario_mensual_disabled');
                            if (salarioDisabledInput) salarioDisabledInput.value = salario;

                            // Input oculto para enviar el dato al controlador
                            const salarioHiddenInput = document.querySelector('#salario_mensual');
                            if (salarioHiddenInput) salarioHiddenInput.value = salario;
                        }

                        if (areaNombre) {
                            const cargoSelect = document.querySelector('select[name="cargo_contrato"]');
                            if (cargoSelect) {
                                cargoSelect.value = areaNombre;
                            }
                        }
                    }
                });
            }
        });
    </script>
</x-layouts::app>
