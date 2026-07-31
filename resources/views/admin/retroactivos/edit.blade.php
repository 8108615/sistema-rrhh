<x-layouts::app title="Editar Registro Retroactivo">
    <div class="relative mb-6 w-full flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <flux:heading size="xl" level="1">Editar Registro Retroactivo</flux:heading>
            <flux:subheading>Modifica los datos del incremento salarial para el empleado seleccionado.</flux:subheading>
        </div>
        <div>
            <a href="{{ route('admin.retroactivos.index', ['gestion' => $retroactivo->gestion]) }}">
                <flux:button variant="subtle" icon="arrow-left">Volver</flux:button>
            </a>
        </div>
    </div>

    <div class="rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-6 shadow-sm">
        <form action="{{ route('admin.retroactivos.update', $retroactivo->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Empleado -->
                <div>
                    <label for="empleado_id" class="block text-xs font-bold text-gray-500 uppercase mb-1">Empleado</label>
                    <select id="empleado_id" name="empleado_id" class="w-full rounded-lg border border-gray-300 bg-white py-2 px-3 text-sm text-gray-950 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white" required>
                        @foreach($empleados as $empleado)
                            <option value="{{ $empleado->id }}" {{ $retroactivo->empleado_id == $empleado->id ? 'selected' : '' }}>
                                {{ $empleado->nombre }} {{ $empleado->apellido }} (CI: {{ $empleado->ci }})
                            </option>
                        @endforeach
                    </select>
                    @error('empleado_id')
                        <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Gestión -->
                <div>
                    <label for="gestion" class="block text-xs font-bold text-gray-500 uppercase mb-1">Gestión (Año)</label>
                    <input type="number" id="gestion" name="gestion" value="{{ old('gestion', $retroactivo->gestion) }}" class="w-full rounded-lg border border-gray-300 bg-white py-2 px-3 text-sm text-gray-950 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white" required>
                    @error('gestion')
                        <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Porcentaje -->
                <div>
                    <label for="porcentaje" class="block text-xs font-bold text-gray-500 uppercase mb-1">Porcentaje de Incremento (%)</label>
                    <input type="number" step="0.01" id="porcentaje" name="porcentaje" value="{{ old('porcentaje', $retroactivo->porcentaje) }}" class="w-full rounded-lg border border-gray-300 bg-white py-2 px-3 text-sm text-gray-950 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white" required>
                    @error('porcentaje')
                        <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Sueldo Anterior -->
                <div>
                    <label for="sueldo_anterior" class="block text-xs font-bold text-gray-500 uppercase mb-1">Sueldo Anterior ({{ $simboloMoneda }})</label>
                    <input type="number" step="0.01" id="sueldo_anterior" name="sueldo_anterior" value="{{ old('sueldo_anterior', $retroactivo->sueldo_anterior) }}" class="w-full rounded-lg border border-gray-300 bg-white py-2 px-3 text-sm text-gray-950 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white" required>
                    @error('sueldo_anterior')
                        <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Sueldo Nuevo -->
                <div>
                    <label for="sueldo_nuevo" class="block text-xs font-bold text-gray-500 uppercase mb-1">Sueldo Nuevo ({{ $simboloMoneda }})</label>
                    <input type="number" step="0.01" id="sueldo_nuevo" name="sueldo_nuevo" value="{{ old('sueldo_nuevo', $retroactivo->sueldo_nuevo) }}" class="w-full rounded-lg border border-gray-300 bg-white py-2 px-3 text-sm text-gray-950 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white" required>
                    @error('sueldo_nuevo')
                        <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Meses Aplicados -->
                <div>
                    <label for="meses_aplicados" class="block text-xs font-bold text-gray-500 uppercase mb-1">Meses Aplicados</label>
                    <input type="number" id="meses_aplicados" name="meses_aplicados" value="{{ old('meses_aplicados', $retroactivo->meses_aplicados) }}" class="w-full rounded-lg border border-gray-300 bg-white py-2 px-3 text-sm text-gray-950 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white" min="1" max="12" required>
                    @error('meses_aplicados')
                        <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Monto a Pagar -->
                <div>
                    <label for="monto_pagar" class="block text-xs font-bold text-gray-500 uppercase mb-1">Monto Total a Pagar ({{ $simboloMoneda }})</label>
                    <input type="number" step="0.01" id="monto_pagar" name="monto_pagar" value="{{ old('monto_pagar', $retroactivo->monto_pagar) }}" class="w-full rounded-lg border border-gray-300 bg-white py-2 px-3 text-sm text-gray-950 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white font-bold text-emerald-600" required>
                    @error('monto_pagar')
                        <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Estado -->
                <div>
                    <label for="estado" class="block text-xs font-bold text-gray-500 uppercase mb-1">Estado</label>
                    <select id="estado" name="estado" class="w-full rounded-lg border border-gray-300 bg-white py-2 px-3 text-sm text-gray-950 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white" required>
                        <option value="Pendiente" {{ $retroactivo->estado == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="Pagado" {{ $retroactivo->estado == 'Pagado' ? 'selected' : '' }}>Pagado</option>
                    </select>
                    @error('estado')
                        <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Fecha de Pago -->
                <div>
                    <label for="fecha_pago" class="block text-xs font-bold text-gray-500 uppercase mb-1">Fecha de Pago</label>
                    <input type="date" id="fecha_pago" name="fecha_pago" value="{{ old('fecha_pago', $retroactivo->fecha_pago) }}" class="w-full rounded-lg border border-gray-300 bg-white py-2 px-3 text-sm text-gray-950 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white">
                    @error('fecha_pago')
                        <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Observaciones -->
            <div class="mt-4">
                <label for="observaciones" class="block text-xs font-bold text-gray-500 uppercase mb-1">Observaciones</label>
                <textarea id="observaciones" name="observaciones" rows="3" class="w-full rounded-lg border border-gray-300 bg-white py-2 px-3 text-sm text-gray-950 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white">{{ old('observaciones', $retroactivo->observaciones) }}</textarea>
                @error('observaciones')
                    <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <flux:button href="{{ route('admin.retroactivos.index', ['gestion' => $retroactivo->gestion]) }}" variant="subtle">
                    Cancelar
                </flux:button>

                <flux:button type="submit" variant="primary" icon="arrow-path" color="green" class="cursor-pointer">
                    Actualizar Registro
                </flux:button>
            </div>
        </form>
    </div>

    <!-- Script opcional para autocalcular si cambian los valores en caliente -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sueldoAnteriorInput = document.getElementById('sueldo_anterior');
            const porcentajeInput = document.getElementById('porcentaje');
            const sueldoNuevoInput = document.getElementById('sueldo_nuevo');
            const mesesInput = document.getElementById('meses_aplicados');
            const montoPagarInput = document.getElementById('monto_pagar');

            function calcularValores() {
                const anterior = parseFloat(sueldoAnteriorInput.value) || 0;
                const porcentaje = parseFloat(porcentajeInput.value) || 0;
                const meses = parseInt(mesesInput.value) || 0;

                const nuevo = anterior + (anterior * (porcentaje / 100));
                const diferenciaMensual = nuevo - anterior;
                const montoTotal = diferenciaMensual * meses;

                sueldoNuevoInput.value = nuevo.toFixed(2);
                montoPagarInput.value = montoTotal.toFixed(2);
            }

            sueldoAnteriorInput.addEventListener('input', calcularValores);
            porcentajeInput.addEventListener('input', calcularValores);
            mesesInput.addEventListener('input', calcularValores);
        });
    </script>
</x-layouts::app>
