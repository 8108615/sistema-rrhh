<x-layouts::app title="Registrar Contrato">
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Cabecera de la sección -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-zinc-100">Registrar Nuevo Contrato</h1>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Selecciona el empleado para autocompletar los datos y completa la información del contrato.</p>
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
            <form action="{{ route('admin.contratos.store') }}" method="POST" class="space-y-6">
                @csrf

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
                                    {{ old('empleado_id') == $empleado->id ? 'selected' : '' }}>
                                    {{ $empleado->nombre }} {{ $empleado->apellido }} - CI: {{ $empleado->ci }}
                                </option>
                            @endforeach
                        </flux:select>
                    </div>

                    <!-- Tipo de Contrato -->
                    <div>
                        <flux:select name="tipo_contrato" label="Tipo de Contrato" placeholder="Selecciona el tipo..." required>
                            <option value="Indefinido" {{ old('tipo_contrato') == 'Indefinido' ? 'selected' : '' }}>Indefinido</option>
                            <option value="Plazo Fijo" {{ old('tipo_contrato') == 'Plazo Fijo' ? 'selected' : '' }}>Plazo Fijo</option>
                            <option value="Consultoría por Producto" {{ old('tipo_contrato') == 'Consultoría por Producto' ? 'selected' : '' }}>Consultoría por Producto</option>
                            <option value="Consultoría en Línea" {{ old('tipo_contrato') == 'Consultoría en Línea' ? 'selected' : '' }}>Consultoría en Línea</option>
                            <option value="Pasantía" {{ old('tipo_contrato') == 'Pasantía' ? 'selected' : '' }}>Pasantía</option>
                        </flux:select>
                    </div>

                    <!-- Cargo del Contrato -->
                    <div>
                        <flux:select id="cargo_contrato" name="cargo_contrato" label="Cargo / Área del Contrato" placeholder="Selecciona un área..." required>
                            <option value="">Selecciona un área...</option>
                            @foreach ($areas as $area)
                                <option value="{{ $area->nombre }}" {{ old('cargo_contrato') == $area->nombre ? 'selected' : '' }}>
                                    {{ $area->nombre }}
                                </option>
                            @endforeach
                        </flux:select>
                    </div>

                    <!-- Fecha de Inicio -->
                    <div>
                        <flux:input type="date" id="fecha_inicio" name="fecha_inicio" label="Fecha de Inicio" value="{{ old('fecha_inicio') }}" required />
                    </div>

                    <!-- Fecha de Fin -->
                    <div>
                        <flux:input type="date" name="fecha_fin" label="Fecha de Fin (Opcional)" value="{{ old('fecha_fin') }}" description="Dejar en blanco si es indefinido o labor determinada." />
                    </div>

                    <!-- Salario Mensual -->
                    <div>
                        <flux:input type="number" step="0.01" min="0" id="salario_mensual" name="salario_mensual" label="Salario Mensual (Bs.)" value="{{ old('salario_mensual') }}" placeholder="0.00" required />
                    </div>

                    <!-- Estado -->
                    <div>
                        <flux:select name="estado" label="Estado del Contrato" required>
                            <option value="Activo" {{ old('estado', 'Activo') == 'Activo' ? 'selected' : '' }}>Activo</option>
                            <option value="Vencido" {{ old('estado') == 'Vencido' ? 'selected' : '' }}>Vencido</option>
                            <option value="Finalizado" {{ old('estado') == 'Finalizado' ? 'selected' : '' }}>Finalizado</option>
                            <option value="Anulado" {{ old('estado') == 'Anulado' ? 'selected' : '' }}>Anulado</option>
                        </flux:select>
                    </div>

                    <!-- Observaciones -->
                    <div class="md:col-span-2">
                        <flux:textarea name="observaciones" label="Observaciones" placeholder="Notas o detalles adicionales del contrato...">{{ old('observaciones') }}</flux:textarea>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-zinc-200 dark:border-zinc-800">
                    <flux:button variant="ghost" href="{{ route('admin.contratos.index') }}">Cancelar</flux:button>
                    <flux:button variant="primary" type="submit">Guardar Contrato</flux:button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script para autocompletar datos del empleado seleccionado -->
    <!-- Script para autocompletar datos del empleado seleccionado -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const empleadoSelect = document.querySelector('#empleado_id');
            
            // Si usas Livewire o Alpine dentro de Flux, escuchamos el cambio
            document.addEventListener('change', function (e) {
                if (e.target && e.target.id === 'empleado_id') {
                    actualizarDatosEmpleado(e.target);
                }
            });

            // Por si acaso el componente emite un evento personalizado o cambia directamente
            const selectElement = document.querySelector('select[name="empleado_id"]');
            if (selectElement) {
                selectElement.addEventListener('change', function () {
                    actualizarDatosEmpleado(this);
                });
            }

            function actualizarDatosEmpleado(select) {
                const selectedOption = select.options ? select.options[select.selectedIndex] : null;
                
                if (selectedOption && selectedOption.value) {
                    const fechaIngreso = selectedOption.getAttribute('data-fecha');
                    const salario = selectedOption.getAttribute('data-salario');
                    const areaNombre = selectedOption.getAttribute('data-area');

                    // Asignar Fecha de Inicio
                    if (fechaIngreso) {
                        const fechaInput = document.querySelector('input[name="fecha_inicio"]');
                        if (fechaInput) {
                            fechaInput.value = fechaIngreso;
                            fechaInput.dispatchEvent(new Event('input', { bubbles: true }));
                            fechaInput.dispatchEvent(new Event('change', { bubbles: true }));
                        }
                    }

                    // Asignar Salario
                    if (salario) {
                        const salarioInput = document.querySelector('input[name="salario_mensual"]');
                        if (salarioInput) {
                            salarioInput.value = salario;
                            salarioInput.dispatchEvent(new Event('input', { bubbles: true }));
                            salarioInput.dispatchEvent(new Event('change', { bubbles: true }));
                        }
                    }

                    // Asignar Cargo / Área
                    if (areaNombre) {
                        const cargoSelect = document.querySelector('select[name="cargo_contrato"]');
                        if (cargoSelect) {
                            cargoSelect.value = areaNombre;
                            cargoSelect.dispatchEvent(new Event('change', { bubbles: true }));
                        }
                    }
                }
            }
        });
    </script>
</x-layouts::app>