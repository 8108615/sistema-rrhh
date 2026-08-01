<x-layouts::app title="Registrar Contrato">
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Cabecera de la sección -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-zinc-100">Registrar Nuevo Contrato</h1>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Completa los datos del contrato laboral y adjunta el documento escaneado.</p>
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
            <form action="{{ route('admin.contratos.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Empleado -->
                    <div class="md:col-span-2">
                        <flux:select name="empleado_id" label="Empleado" placeholder="Selecciona un empleado..." required>
                            @foreach ($empleados as $empleado)
                                <option value="{{ $empleado->id }}" {{ old('empleado_id') == $empleado->id ? 'selected' : '' }}>
                                    {{ $empleado->nombre }} {{ $empleado->apellido }} - CI: {{ $empleado->ci }}
                                </option>
                            @endforeach
                        </flux:select>
                    </div>

                    <!-- Tipo de Contrato -->
                    <div>
                        <flux:select name="tipo_contrato" label="Tipo de Contrato" placeholder="Selecciona el tipo..." required>
                            <option value="Plazo Fijo" {{ old('tipo_contrato') == 'Plazo Fijo' ? 'selected' : '' }}>Plazo Fijo</option>
                            <option value="Indefinido" {{ old('tipo_contrato') == 'Indefinido' ? 'selected' : '' }}>Indefinido</option>
                            <option value="Realización de Labor Determinada" {{ old('tipo_contrato') == 'Realización de Labor Determinada' ? 'selected' : '' }}>Realización de Labor Determinada</option>
                            <option value="Consultoría" {{ old('tipo_contrato') == 'Consultoría' ? 'selected' : '' }}>Consultoría</option>
                        </flux:select>
                    </div>

                    <!-- Cargo del Contrato -->
                    <div>
                        <flux:select name="cargo_contrato" label="Cargo / Área del Contrato" placeholder="Selecciona un área..." required>
                            @foreach ($areas as $area)
                                <option value="{{ $area->nombre }}" {{ old('cargo_contrato') == $area->nombre ? 'selected' : '' }}>
                                    {{ $area->nombre }}
                                </option>
                            @endforeach
                        </flux:select>
                    </div>

                    <!-- Fecha de Inicio -->
                    <div>
                        <flux:input type="date" name="fecha_inicio" label="Fecha de Inicio" value="{{ old('fecha_inicio') }}" required />
                    </div>

                    <!-- Fecha de Fin -->
                    <div>
                        <flux:input type="date" name="fecha_fin" label="Fecha de Fin (Opcional)" value="{{ old('fecha_fin') }}" description="Dejar en blanco si es indefinido o labor determinada." />
                    </div>

                    <!-- Salario Mensual -->
                    <div>
                        <flux:input type="number" step="0.01" min="0" name="salario_mensual" label="Salario Mensual (Bs.)" value="{{ old('salario_mensual') }}" placeholder="4455.78" required />
                    </div>

                    <!-- Estado -->
                    <div>
                        <flux:select name="estado" label="Estado del Contrato" required>
                            <option value="Activo" {{ old('estado', 'Activo') == 'Activo' ? 'selected' : '' }}>Activo</option>
                            <option value="Finalizado" {{ old('estado') == 'Finalizado' ? 'selected' : '' }}>Finalizado</option>
                            <option value="Cancelado" {{ old('estado') == 'Cancelado' ? 'selected' : '' }}>Cancelado</option>
                        </flux:select>
                    </div>

                    <!-- Archivo PDF -->
                    <div class="md:col-span-2">
                        <flux:input type="file" name="archivo_pdf" label="Documento PDF del Contrato" accept="application/pdf" description="Sube el documento escaneado firmado (Máx. 5MB)." />
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
</x-layouts::app>
