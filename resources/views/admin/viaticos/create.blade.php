<x-layouts::app title="Registrar Viaje y Viáticos">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Nuevo Registro de Viaje y Viáticos</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Selecciona el destino, rango de fechas y el personal comisionado.</p>
        </div>
        <a href="{{ route('admin.viaticos.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-zinc-700 text-gray-800 dark:text-gray-200 font-semibold text-sm rounded-lg hover:bg-gray-300 dark:hover:bg-zinc-600 transition">
            Volver
        </a>
    </div>

    <form action="{{ route('admin.viaticos.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        @csrf

        <!-- Columna Izquierda: Datos del Viaje -->
        <div class="lg:col-span-2 bg-white dark:bg-zinc-800 p-6 rounded-lg border border-gray-200 dark:border-zinc-700 shadow-sm space-y-4">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white border-b pb-2 border-gray-200 dark:border-zinc-700">Detalles del Viaje</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Origen -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Origen *</label>
                    <select name="origen" required class="w-full rounded-lg border-gray-300 dark:border-zinc-700 dark:bg-zinc-900 text-gray-900 dark:text-white p-2.5 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">-- Seleccionar Origen --</option>
                        <option value="Santa Cruz" {{ old('origen') == 'Santa Cruz' ? 'selected' : '' }}>Santa Cruz</option>
                        <option value="Cochabamba" {{ old('origen') == 'Cochabamba' ? 'selected' : '' }}>Cochabamba</option>
                        <option value="La Paz" {{ old('origen') == 'La Paz' ? 'selected' : '' }}>La Paz</option>
                        <option value="Chuquisaca" {{ old('origen') == 'Chuquisaca' ? 'selected' : '' }}>Chuquisaca (Sucre)</option>
                        <option value="Oruro" {{ old('origen') == 'Oruro' ? 'selected' : '' }}>Oruro</option>
                        <option value="Potosí" {{ old('origen') == 'Potosí' ? 'selected' : '' }}>Potosí</option>
                        <option value="Tarija" {{ old('origen') == 'Tarija' ? 'selected' : '' }}>Tarija</option>
                        <option value="Beni" {{ old('origen') == 'Beni' ? 'selected' : '' }}>Beni (Trinidad)</option>
                        <option value="Pando" {{ old('origen') == 'Pando' ? 'selected' : '' }}>Pando (Cobija)</option>
                    </select>
                </div>

                <!-- Destino -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Destino *</label>
                    <select name="destino" required class="w-full rounded-lg border-gray-300 dark:border-zinc-700 dark:bg-zinc-900 text-gray-900 dark:text-white p-2.5 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">-- Seleccionar Destino --</option>
                        <option value="Santa Cruz" {{ old('destino') == 'Santa Cruz' ? 'selected' : '' }}>Santa Cruz</option>
                        <option value="Cochabamba" {{ old('destino') == 'Cochabamba' ? 'selected' : '' }}>Cochabamba</option>
                        <option value="La Paz" {{ old('destino') == 'La Paz' ? 'selected' : '' }}>La Paz</option>
                        <option value="Chuquisaca" {{ old('destino') == 'Chuquisaca' ? 'selected' : '' }}>Chuquisaca (Sucre)</option>
                        <option value="Oruro" {{ old('destino') == 'Oruro' ? 'selected' : '' }}>Oruro</option>
                        <option value="Potosí" {{ old('destino') == 'Potosí' ? 'selected' : '' }}>Potosí</option>
                        <option value="Tarija" {{ old('destino') == 'Tarija' ? 'selected' : '' }}>Tarija</option>
                        <option value="Beni" {{ old('destino') == 'Beni' ? 'selected' : '' }}>Beni (Trinidad)</option>
                        <option value="Pando" {{ old('destino') == 'Pando' ? 'selected' : '' }}>Pando (Cobija)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Estadio / Recinto -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Estadio / Recinto Deportivo</label>
                    <input type="text" name="estadio" placeholder="Ej: Estadio Félix Capriles, Hernando Siles..." value="{{ old('estadio') }}" class="w-full rounded-lg border-gray-300 dark:border-zinc-700 dark:bg-zinc-900 text-gray-900 dark:text-white p-2.5 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Motivo / Evento -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Motivo / Partido / Evento</label>
                    <input type="text" name="motivo" placeholder="Ej: Transmisión Wilstermann vs. Oriente..." value="{{ old('motivo') }}" class="w-full rounded-lg border-gray-300 dark:border-zinc-700 dark:bg-zinc-900 text-gray-900 dark:text-white p-2.5 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fecha de Inicio *</label>
                    <input type="date" id="fecha_inicio" name="fecha_inicio" required value="{{ old('fecha_inicio') }}" class="w-full rounded-lg border-gray-300 dark:border-zinc-700 dark:bg-zinc-900 text-gray-900 dark:text-white p-2.5">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fecha de Fin *</label>
                    <input type="date" id="fecha_fin" name="fecha_fin" required value="{{ old('fecha_fin') }}" class="w-full rounded-lg border-gray-300 dark:border-zinc-700 dark:bg-zinc-900 text-gray-900 dark:text-white p-2.5">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Monto Diario por Persona (Bs.) *</label>
                    <input type="number" step="0.01" id="monto_diario" name="monto_diario" required placeholder="200.00" value="{{ old('monto_diario', 200) }}" class="w-full rounded-lg border-gray-300 dark:border-zinc-700 dark:bg-zinc-900 text-gray-900 dark:text-white p-2.5">
                </div>
            </div>

            <!-- Selección Múltiple de Empleados con Búsqueda en Tiempo Real -->
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Seleccionar Personal Comisionado *
                    </label>
                    <div class="flex items-center gap-3 text-xs">
                        <button type="button" id="btn_toggle_all" class="text-indigo-600 dark:text-indigo-400 font-semibold hover:underline cursor-pointer">
                            Seleccionar todos
                        </button>
                        <span class="text-gray-400">|</span>
                        <span class="text-gray-500 dark:text-gray-400 font-medium" id="lbl_conteo_filtro">
                            Mostrando {{ count($empleados) }} de {{ count($empleados) }}
                        </span>
                    </div>
                </div>

                <!-- Campo de Búsqueda Rápida -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i class="fas fa-search text-sm"></i>
                    </div>
                    <input type="text" id="input_buscar_empleado" placeholder="Buscar por nombre, apellido o C.I...." class="w-full pl-9 pr-4 py-2 text-sm rounded-lg border-gray-300 dark:border-zinc-700 dark:bg-zinc-900 text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Contenedor con Scroll de la Lista de Personal -->
                <div class="max-h-60 overflow-y-auto border border-gray-200 dark:border-zinc-700 rounded-lg p-2 space-y-1 bg-gray-50 dark:bg-zinc-900/80" id="contenedor_empleados">
                    @foreach ($empleados as $empleado)
                        <label class="item-empleado flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-200/60 dark:hover:bg-zinc-800 cursor-pointer transition" data-search="{{ strtolower($empleado->nombre . ' ' . $empleado->apellido . ' ' . $empleado->ci) }}">
                            <input type="checkbox" name="empleados[]" value="{{ $empleado->id }}" class="empleado-checkbox rounded text-indigo-600 focus:ring-indigo-500 w-4 h-4">
                            <span class="text-sm font-medium text-gray-800 dark:text-gray-200">
                                {{ $empleado->nombre }} {{ $empleado->apellido }}
                                <span class="text-xs text-gray-500 dark:text-gray-400 font-normal ml-1">(C.I. {{ $empleado->ci }})</span>
                            </span>
                        </label>
                    @endforeach

                    <div id="msg_sin_resultados" class="hidden py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                        No se encontraron empleados con esa búsqueda.
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna Derecha: Tarjeta de Resumen y Totales en Vivo -->
        <div class="bg-white dark:bg-zinc-800 p-6 rounded-lg border border-gray-200 dark:border-zinc-700 shadow-sm h-fit space-y-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white border-b pb-2 border-gray-200 dark:border-zinc-700">Resumen del Cálculo</h2>

            <div class="space-y-4 text-sm">
                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-zinc-700">
                    <span class="text-gray-500 dark:text-gray-400">Días Totales de Viaje:</span>
                    <span id="lbl_dias" class="font-bold text-gray-900 dark:text-white">0 días</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-zinc-700">
                    <span class="text-gray-500 dark:text-gray-400">Personal Seleccionado:</span>
                    <span id="lbl_personas" class="font-bold text-gray-900 dark:text-white">0 personas</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-zinc-700">
                    <span class="text-gray-500 dark:text-gray-400">Viático por Persona:</span>
                    <span id="lbl_persona_total" class="font-bold text-emerald-600 dark:text-emerald-400">Bs. 0.00</span>
                </div>
                <div class="p-4 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg flex justify-between items-center">
                    <span class="font-bold text-indigo-900 dark:text-indigo-200">Total General Viaje:</span>
                    <span id="lbl_gran_total" class="text-2xl font-black text-indigo-600 dark:text-indigo-400">Bs. 0.00</span>
                </div>
            </div>

            <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-base rounded-lg shadow transition">
                Guardar y Confirmar Viáticos
            </button>
        </div>
    </form>

    <!-- JavaScript para Cálculo en Tiempo Real -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const fechaInicio = document.getElementById('fecha_inicio');
        const fechaFin = document.getElementById('fecha_fin');
        const montoDiario = document.getElementById('monto_diario');
        const checkboxes = document.querySelectorAll('.empleado-checkbox');

        const lblDias = document.getElementById('lbl_dias');
        const lblPersonas = document.getElementById('lbl_personas');
        const lblPersonaTotal = document.getElementById('lbl_persona_total');
        const lblGranTotal = document.getElementById('lbl_gran_total');

        // Elementos del buscador de personal
        const inputBuscar = document.getElementById('input_buscar_empleado');
        const itemsEmpleado = document.querySelectorAll('.item-empleado');
        const msgSinResultados = document.getElementById('msg_sin_resultados');
        const btnToggleAll = document.getElementById('btn_toggle_all');
        const lblConteoFiltro = document.getElementById('lbl_conteo_filtro');

        // Function para calcular montos
        function calcular() {
            let dias = 0;
            if (fechaInicio.value && fechaFin.value) {
                const f1 = new Date(fechaInicio.value);
                const f2 = new Date(fechaFin.value);
                const diffTime = f2 - f1;
                if (diffTime >= 0) {
                    dias = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                }
            }

            let personas = 0;
            checkboxes.forEach(cb => {
                if (cb.checked) personas++;
            });

            const diario = parseFloat(montoDiario.value) || 0;
            const totalPersona = dias * diario;
            const granTotal = totalPersona * personas;

            lblDias.textContent = `${dias} días`;
            lblPersonas.textContent = `${personas} personas`;
            lblPersonaTotal.textContent = `Bs. ${totalPersona.toFixed(2)}`;
            lblGranTotal.textContent = `Bs. ${granTotal.toFixed(2)}`;
        }

        // Lógica de Búsqueda de Personal en tiempo real
        inputBuscar.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            let visibles = 0;

            itemsEmpleado.forEach(item => {
                const texto = item.getAttribute('data-search');
                if (texto.includes(query)) {
                    item.classList.remove('hidden');
                    visibles++;
                } else {
                    item.classList.add('hidden');
                }
            });

            if (visibles === 0) {
                msgSinResultados.classList.remove('hidden');
            } else {
                msgSinResultados.classList.add('hidden');
            }

            lblConteoFiltro.textContent = `Mostrando ${visibles} de ${itemsEmpleado.length}`;
        });

        // Seleccionar / Desmarcar todos los visibles
        let todosSeleccionados = false;
        btnToggleAll.addEventListener('click', function() {
            todosSeleccionados = !todosSeleccionados;

            itemsEmpleado.forEach(item => {
                if (!item.classList.contains('hidden')) {
                    const cb = item.querySelector('.empleado-checkbox');
                    cb.checked = todosSeleccionados;
                }
            });

            btnToggleAll.textContent = todosSeleccionados ? 'Desmarcar todos' : 'Seleccionar todos';
            calcular();
        });

        // Event listeners para recalcular
        fechaInicio.addEventListener('change', calcular);
        fechaFin.addEventListener('change', calcular);
        montoDiario.addEventListener('input', calcular);
        checkboxes.forEach(cb => cb.addEventListener('change', calcular));

        calcular();
    });
</script>
</x-layouts::app>
