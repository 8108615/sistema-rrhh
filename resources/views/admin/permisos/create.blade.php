<x-layouts::app title="Registrar Permiso o Vacación">
    <div class="relative mb-6 w-full flex justify-between items-center">
        <div>
            <flux:heading size="xl" level="1">Nueva Solicitud de Permiso / Vacación</flux:heading>
            <flux:subheading>Registre una nueva ausencia, vacación o baja médica para el personal.</flux:subheading>
        </div>
        <div>
            <a href="{{ route('admin.permisos.index') }}">
                <flux:button variant="subtle" icon="arrow-left">Volver</flux:button>
            </a>
        </div>
    </div>

    <flux:separator variant="subtle" class="mb-6" />

    <div class="max-w-3xl bg-white dark:bg-zinc-800 p-6 rounded-lg border border-gray-200 dark:border-zinc-700 shadow-sm">
        <form action="{{ route('admin.permisos.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Selección de Empleado -->
            <div>
                <flux:field>
                    <flux:label>Empleado</flux:label>
                    <select name="empleado_id" id="empleado_id" class="w-full rounded-lg border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 px-3 py-2 text-sm text-gray-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Seleccione un empleado...</option>
                        @foreach($empleados as $empleado)
                            <option value="{{ $empleado->id }}" {{ old('empleado_id') == $empleado->id ? 'selected' : '' }}>
                                {{ $empleado->nombre }} {{ $empleado->apellido }} (CI: {{ $empleado->ci }})
                            </option>
                        @endforeach
                    </select>
                    <flux:error name="empleado_id" />
                </flux:field>

                <!-- Alerta visual de días disponibles -->
                <div id="info-vacaciones" class="hidden mt-2 p-3 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 rounded-lg text-sm text-blue-800 dark:text-blue-300 flex justify-between items-center">
                    <span>📅 Días de vacaciones disponibles: <strong id="dias-disponibles-txt" class="text-base">0</strong> días</span>
                    <span class="text-xs opacity-75">Calculado según Ley Boliviana</span>
                </div>
            </div>

            <!-- Tipo de Solicitud y Estado -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:field>
                    <flux:label>Tipo de Solicitud</flux:label>
                    <select name="tipo" id="tipo_solicitud" class="w-full rounded-lg border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 px-3 py-2 text-sm text-gray-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Seleccione el tipo...</option>
                        <option value="Vacaciones" {{ old('tipo') == 'Vacaciones' ? 'selected' : '' }}>Vacaciones (Ley boliviana)</option>
                        <option value="Permiso Personal" {{ old('tipo') == 'Permiso Personal' ? 'selected' : '' }}>Permiso Personal</option>
                        <option value="Baja Médica" {{ old('tipo') == 'Baja Médica' ? 'selected' : '' }}>Baja Médica</option>
                    </select>
                    <flux:error name="tipo" />
                </flux:field>

                <flux:field>
                    <flux:label>Estado Inicial</flux:label>
                    <select name="estado" class="w-full rounded-lg border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 px-3 py-2 text-sm text-gray-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="Pendiente" {{ old('estado', 'Pendiente') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="Aprobado" {{ old('estado') == 'Aprobado' ? 'selected' : '' }}>Aprobado</option>
                        <option value="Rechazado" {{ old('estado') == 'Rechazado' ? 'selected' : '' }}>Rechazado</option>
                    </select>
                    <flux:error name="estado" />
                </flux:field>
            </div>

            <!-- Días Solicitados y Fechas Automatizadas -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
                <flux:field>
                    <flux:input type="number" id="dias_solicitados" name="dias_solicitados" label="Cantidad de Días" value="{{ old('dias_solicitados', 1) }}" min="0.5" step="0.5" required />
                </flux:field>

                <flux:field>
                    <flux:input type="date" id="fecha_inicio" name="fecha_inicio" label="Fecha de Inicio" value="{{ old('fecha_inicio', date('Y-m-d')) }}" required />
                    <flux:error name="fecha_inicio" />
                </flux:field>

                <flux:field>
                    <flux:input type="date" id="fecha_fin" name="fecha_fin" label="Fecha Fin (Último día)" value="{{ old('fecha_fin', '') }}" required />
                    <flux:error name="fecha_fin" />
                </flux:field>
            </div>

            <!-- Fecha de Retorno -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:input type="date" id="fecha_retorno" name="fecha_retorno" label="Fecha de Retorno a Trabajar" value="{{ old('fecha_retorno') }}" required />
            </div>

            <!-- Motivo -->
            <div>
                <flux:field>
                    <flux:label>Motivo o Justificación</flux:label>
                    <textarea name="motivo" rows="3" class="w-full rounded-lg border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 p-3 text-sm text-gray-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Detalle el motivo...">{{ old('motivo') }}</textarea>
                    <flux:error name="motivo" />
                </flux:field>
            </div>

            <!-- Botones de Acción -->
            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('admin.permisos.index') }}">
                    <flux:button variant="subtle" type="button">Cancelar</flux:button>
                </a>
                <flux:button variant="primary" type="submit" color="blue">Guardar Solicitud</flux:button>
            </div>
        </form>
    </div>

    <!-- Script combinado para Vacaciones dinámicas y cálculo de fechas hábiles -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const empleadoSelect = document.getElementById('empleado_id');
            const tipoSelect = document.getElementById('tipo_solicitud');
            const infoVacaciones = document.getElementById('info-vacaciones');
            const diasDisponiblesTxt = document.getElementById('dias-disponibles-txt');

            const diasInput = document.getElementById('dias_solicitados');
            const fechaInicioInput = document.getElementById('fecha_inicio');
            const fechaFinInput = document.getElementById('fecha_fin');
            const fechaRetornoInput = document.getElementById('fecha_retorno');

            // 1. Lógica para consultar días de vacaciones al cambiar de empleado o tipo
            function actualizarDisponibilidadVacaciones() {
                const empleadoId = empleadoSelect.value;
                const tipo = tipoSelect.value;

                if (empleadoId && tipo === 'Vacaciones') {
                    fetch(`/admin/permisos/empleado/${empleadoId}/vacaciones`)
                        .then(response => response.json())
                        .then(data => {
                            diasDisponiblesTxt.textContent = data.dias_disponibles;
                            infoVacaciones.classList.remove('hidden');
                        })
                        .catch(error => console.error('Error al obtener vacaciones:', error));
                } else {
                    infoVacaciones.classList.add('hidden');
                }
            }

            empleadoSelect.addEventListener('change', actualizarDisponibilidadVacaciones);
            tipoSelect.addEventListener('change', actualizarDisponibilidadVacaciones);

            // Si ya viene seleccionado por old() en caso de error de validación
            if (empleadoSelect.value && tipoSelect.value === 'Vacaciones') {
                actualizarDisponibilidadVacaciones();
            }

            // 2. Feriados y cálculo de días hábiles
            const feriadosFijos = ['01-01', '01-22', '06-21', '05-01', '08-06', '11-02', '12-25'];

            function calcularPascua(anio) {
                const a = anio % 19, b = Math.floor(anio / 100), c = anio % 100;
                const d = Math.floor(b / 4), e = b % 4, f = Math.floor((b + 8) / 25);
                const g = Math.floor((b - f + 1) / 3), h = (19 * a + b - d - g + 15) % 30;
                const i = Math.floor(c / 4), k = c % 4, l = (32 + 2 * e + 2 * i - h - k) % 7;
                const m = Math.floor((a + 11 * h + 22 * l) / 451);
                const mes = Math.floor((h + l - 7 * m + 114) / 31);
                const dia = ((h + l - 7 * m + 114) % 31) + 1;
                return new Date(anio, mes - 1, dia);
            }

            function obtenerFeriadosMovibles(anio) {
                const pascua = calcularPascua(anio);
                let viernesSanto = new Date(pascua); viernesSanto.setDate(pascua.getDate() - 2);
                let lunesCarnaval = new Date(pascua); lunesCarnaval.setDate(pascua.getDate() - 48);
                let martesCarnaval = new Date(pascua); martesCarnaval.setDate(pascua.getDate() - 47);
                let corpusChristi = new Date(pascua); corpusChristi.setDate(pascua.getDate() + 60);

                return [
                    lunesCarnaval.toISOString().split('T')[0],
                    martesCarnaval.toISOString().split('T')[0],
                    viernesSanto.toISOString().split('T')[0],
                    corpusChristi.toISOString().split('T')[0]
                ];
            }

            function esFeriado(date) {
                const anio = date.getFullYear();
                const mes = String(date.getMonth() + 1).padStart(2, '0');
                const dia = String(date.getDate()).padStart(2, '0');
                const mesDia = `${mes}-${dia}`;
                const yyyyMmDd = `${anio}-${mesDia}`;

                if (feriadosFijos.includes(mesDia)) return true;
                if (obtenerFeriadosMovibles(anio).includes(yyyyMmDd)) return true;
                return false;
            }

            function calcularFechasBolivia() {
                if (!fechaInicioInput.value || !diasInput.value) return;

                let diasSolicitados = parseFloat(diasInput.value);
                if (isNaN(diasSolicitados) || diasSolicitados < 0.5) diasSolicitados = 1;

                let fechaActual = new Date(fechaInicioInput.value + 'T00:00:00');
                let diasAcumulados = 0;
                let ultimoDiaHabil = new Date(fechaActual);

                let seguridad = 0;
                while (diasAcumulados < diasSolicitados && seguridad < 365) {
                    let diaSemana = fechaActual.getDay();
                    let valorDia = 1;

                    if (diaSemana === 0 || esFeriado(fechaActual)) {
                        valorDia = 0;
                    } else if (diaSemana === 6) {
                        valorDia = 0.5;
                    }

                    if (valorDia > 0) {
                        if (diasAcumulados + valorDia > diasSolicitados) {
                            diasAcumulados = diasSolicitados;
                        } else {
                            diasAcumulados += valorDia;
                        }
                        ultimoDiaHabil = new Date(fechaActual);
                    }

                    if (diasAcumulados < diasSolicitados) {
                        fechaActual.setDate(fechaActual.getDate() + 1);
                    }
                    seguridad++;
                }

                fechaFinInput.value = ultimoDiaHabil.toISOString().split('T')[0];

                let fechaRetorno = new Date(ultimoDiaHabil);
                fechaRetorno.setDate(fechaRetorno.getDate() + 1);

                while (fechaRetorno.getDay() === 0 || esFeriado(fechaRetorno)) {
                    fechaRetorno.setDate(fechaRetorno.getDate() + 1);
                }

                fechaRetornoInput.value = fechaRetorno.toISOString().split('T')[0];
            }

            diasInput.addEventListener('input', calcularFechasBolivia);
            fechaInicioInput.addEventListener('change', calcularFechasBolivia);

            if (fechaInicioInput.value && diasInput.value) {
                calcularFechasBolivia();
            }
        });
    </script>
</x-layouts::app>