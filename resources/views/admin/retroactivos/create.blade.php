<x-layouts::app title="Nuevo Registro Retroactivo">
    <div class="relative mb-6 w-full flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <flux:heading size="xl" level="1">Registrar Nuevo Retroactivo</flux:heading>
            <flux:subheading>Asigna manualmente o calcula el incremento salarial retroactivo de un empleado.</flux:subheading>
        </div>
        <div>
            <a href="{{ route('admin.retroactivos.index') }}">
                <flux:button variant="subtle" icon="arrow-left">Volver</flux:button>
            </a>
        </div>
    </div>

    <div class="rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-6 shadow-sm w-full">
        <form action="{{ route('admin.retroactivos.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="empleado_id" class="block text-xs font-bold uppercase text-gray-500 mb-1">Empleado <span class="text-red-500">*</span></label>
                    <select id="empleado_id" name="empleado_id" required class="w-full rounded-lg border border-gray-300 bg-white py-2.5 px-3 text-sm text-gray-950 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white" onchange="seleccionarEmpleado(this)">
                        <option value="">Seleccione un empleado...</option>
                        @foreach($empleados as $empleado)
                            <option value="{{ $empleado->id }}" data-salario="{{ $empleado->salario ?? 0 }}" {{ old('empleado_id') == $empleado->id ? 'selected' : '' }}>
                                {{ $empleado->nombre }} {{ $empleado->apellido }} (CI: {{ $empleado->ci ?? 'N/D' }})
                            </option>
                        @endforeach
                    </select>
                    @error('empleado_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="gestion" class="block text-xs font-bold uppercase text-gray-500 mb-1">Gestión (Año) <span class="text-red-500">*</span></label>
                    <input type="number" id="gestion" name="gestion" value="{{ old('gestion', date('Y')) }}" required class="w-full rounded-lg border border-gray-300 bg-white py-2.5 px-3 text-sm text-gray-950 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white">
                    @error('gestion')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="porcentaje" class="block text-xs font-bold uppercase text-gray-500 mb-1">% Incremento Salarial <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" id="porcentaje" name="porcentaje" value="{{ old('porcentaje', '3.00') }}" required class="w-full rounded-lg border border-gray-300 bg-white py-2.5 px-3 text-sm text-gray-950 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white" oninput="calcularRetroactivo()">
                    @error('porcentaje')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="sueldo_anterior" class="block text-xs font-bold uppercase text-gray-500 mb-1">Sueldo Anterior (BOB) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" id="sueldo_anterior" name="sueldo_anterior" value="{{ old('sueldo_anterior', 0) }}" required class="w-full rounded-lg border border-gray-300 bg-white py-2.5 px-3 text-sm text-gray-950 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white" oninput="calcularRetroactivo()">
                    @error('sueldo_anterior')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="meses_aplicados" class="block text-xs font-bold uppercase text-gray-500 mb-1">Meses Aplicados <span class="text-red-500">*</span></label>
                    <input type="number" id="meses_aplicados" name="meses_aplicados" value="{{ old('meses_aplicados', 5) }}" required class="w-full rounded-lg border border-gray-300 bg-white py-2.5 px-3 text-sm text-gray-950 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white" oninput="calcularRetroactivo()">
                    @error('meses_aplicados')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Sueldo Nuevo (Automático)</label>
                    <input type="number" step="0.01" id="sueldo_nuevo_view" value="{{ old('sueldo_nuevo', 0) }}" disabled class="w-full rounded-lg border border-gray-300 bg-gray-100 py-2.5 px-3 text-sm text-gray-800 shadow-sm dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 font-mono font-bold">
                    <input type="hidden" id="sueldo_nuevo" name="sueldo_nuevo" value="{{ old('sueldo_nuevo', 0) }}">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-bold uppercase text-emerald-600 dark:text-emerald-400 mb-1">Monto Total a Pagar (BOB) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" id="monto_pagar_view" value="{{ old('monto_pagar', 0) }}" disabled class="w-full rounded-lg border border-emerald-300 bg-emerald-50/50 py-2.5 px-3 text-sm text-emerald-700 shadow-sm dark:border-emerald-900/50 dark:bg-zinc-900 dark:text-emerald-300 font-mono font-bold">
                    <input type="hidden" id="monto_pagar" name="monto_pagar" value="{{ old('monto_pagar', 0) }}">
                </div>

                <div>
                    <label for="fecha_pago" class="block text-xs font-bold uppercase text-gray-500 mb-1">Fecha de Pago</label>
                    <input type="date" id="fecha_pago" name="fecha_pago" value="{{ old('fecha_pago', date('Y-m-d')) }}" class="w-full rounded-lg border border-gray-300 bg-white py-2.5 px-3 text-sm text-gray-950 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white">
                </div>

                <div>
                    <label for="estado" class="block text-xs font-bold uppercase text-gray-500 mb-1">Estado del Pago <span class="text-red-500">*</span></label>
                    <select id="estado" name="estado" required class="w-full rounded-lg border border-gray-300 bg-white py-2.5 px-3 text-sm text-gray-950 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white">
                        <option value="Pendiente" {{ old('estado') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="Pagado" {{ old('estado') == 'Pagado' ? 'selected' : '' }}>Pagado</option>
                    </select>
                    @error('estado')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="observaciones" class="block text-xs font-bold uppercase text-gray-500 mb-1">Observaciones</label>
                <textarea id="observaciones" name="observaciones" rows="2" placeholder="Opcional..." class="w-full rounded-lg border border-gray-300 bg-white py-2 px-3 text-sm text-gray-950 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white">{{ old('observaciones') }}</textarea>
                @error('observaciones')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-zinc-700">
                <flux:button href="{{ route('admin.retroactivos.index') }}" variant="subtle">
                    Cancelar
                </flux:button>

                <flux:button type="submit" variant="primary" icon="arrow-down-tray" color="blue" class="cursor-pointer">
                    Guardar Registro
                </flux:button>
            </div>
        </form>
    </div>

    <script>
        function seleccionarEmpleado(select) {
            const option = select.options[select.selectedIndex];
            const salarioBase = option.getAttribute('data-salario') || 0;

            document.getElementById('sueldo_anterior').value = parseFloat(salarioBase).toFixed(2);
            calcularRetroactivo();
        }

        function calcularRetroactivo() {
            const sueldoAnterior = parseFloat(document.getElementById('sueldo_anterior').value) || 0;
            const porcentaje = parseFloat(document.getElementById('porcentaje').value) || 0;
            const meses = parseInt(document.getElementById('meses_aplicados').value) || 0;

            const sueldoNuevo = sueldoAnterior + (sueldoAnterior * (porcentaje / 100));
            const diferenciaMensual = sueldoNuevo - sueldoAnterior;
            const montoPagar = diferenciaMensual * meses;

            document.getElementById('sueldo_nuevo_view').value = sueldoNuevo.toFixed(2);
            document.getElementById('monto_pagar_view').value = montoPagar.toFixed(2);

            document.getElementById('sueldo_nuevo').value = sueldoNuevo.toFixed(2);
            document.getElementById('monto_pagar').value = montoPagar.toFixed(2);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const sueldoAnteriorVal = document.getElementById('sueldo_anterior').value;
            if(sueldoAnteriorVal && parseFloat(sueldoAnteriorVal) > 0) {
                calcularRetroactivo();
            }
        });
    </script>
</x-layouts::app>