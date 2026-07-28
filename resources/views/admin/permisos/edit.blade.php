<x-layouts::app title="Editar Permiso o Vacación">
    <div class="relative mb-6 w-full flex justify-between items-center">
        <div>
            <flux:heading size="xl" level="1">Editar Solicitud</flux:heading>
            <flux:subheading>Modifique los datos de la solicitud de ausencia.</flux:subheading>
        </div>
        <div>
            <a href="{{ route('admin.permisos.index') }}">
                <flux:button variant="subtle" icon="arrow-left">Volver</flux:button>
            </a>
        </div>
    </div>

    <flux:separator variant="subtle" class="mb-6" />

    <div class="max-w-3xl bg-white dark:bg-zinc-800 p-6 rounded-lg border border-gray-200 dark:border-zinc-700 shadow-sm">
        <form action="{{ route('admin.permisos.update', $permiso->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Selección de Empleado -->
            <div>
                <flux:field>
                    <flux:label>Empleado</flux:label>
                    <select name="empleado_id" class="w-full rounded-lg border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 px-3 py-2 text-sm text-gray-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Seleccione un empleado...</option>
                        @foreach($empleados as $empleado)
                            <option value="{{ $empleado->id }}" {{ old('empleado_id', $permiso->empleado_id) == $empleado->id ? 'selected' : '' }}>
                                {{ $empleado->nombre }} {{ $empleado->apellido }} (CI: {{ $empleado->ci }})
                            </option>
                        @endforeach
                    </select>
                    <flux:error name="empleado_id" />
                </flux:field>
            </div>

            <!-- Tipo de Solicitud y Estado -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:field>
                    <flux:label>Tipo de Solicitud</flux:label>
                    <select name="tipo" class="w-full rounded-lg border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 px-3 py-2 text-sm text-gray-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Seleccione el tipo...</option>
                        <option value="Vacaciones" {{ old('tipo', $permiso->tipo) == 'Vacaciones' ? 'selected' : '' }}>Vacaciones (Ley: 15 días hábiles)</option>
                        <option value="Permiso Personal" {{ old('tipo', $permiso->tipo) == 'Permiso Personal' ? 'selected' : '' }}>Permiso Personal</option>
                        <option value="Baja Médica" {{ old('tipo', $permiso->tipo) == 'Baja Médica' ? 'selected' : '' }}>Baja Médica</option>
                    </select>
                    <flux:error name="tipo" />
                </flux:field>

                <flux:field>
                    <flux:label>Estado</flux:label>
                    <select name="estado" class="w-full rounded-lg border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 px-3 py-2 text-sm text-gray-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="Pendiente" {{ old('estado', $permiso->estado) == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="Aprobado" {{ old('estado', $permiso->estado) == 'Aprobado' ? 'selected' : '' }}>Aprobado</option>
                        <option value="Rechazado" {{ old('estado', $permiso->estado) == 'Rechazado' ? 'selected' : '' }}>Rechazado</option>
                    </select>
                    <flux:error name="estado" />
                </flux:field>
            </div>

            <!-- Días Solicitados y Fechas Automatizadas -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
                <flux:field>
                    <flux:input type="number" id="dias_solicitados" name="dias_solicitados" label="Cantidad de Días" value="{{ old('dias_solicitados', $permiso->dias_solicitados ?? 1) }}" min="0.5" step="0.5" required />
                    <flux:error name="dias_solicitados" />
                    <p class="text-[11px] text-gray-500 mt-1">Vacación base: 15 días/año.</p>
                </flux:field>

                <flux:field>
                    <flux:input type="date" id="fecha_inicio" name="fecha_inicio" label="Fecha de Inicio" value="{{ old('fecha_inicio', $permiso->fecha_inicio ?? date('Y-m-d')) }}" required />
                    <flux:error name="fecha_inicio" />
                </flux:field>

                <flux:field>
                    <flux:input type="date" id="fecha_fin" name="fecha_fin" label="Fecha Fin (Último día)" value="{{ old('fecha_fin', $permiso->fecha_fin ?? '') }}" required />
                    <flux:error name="fecha_fin" />
                </flux:field>
            </div>

            <!-- Fecha de Retorno -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:input type="date" id="fecha_retorno" name="fecha_retorno" label="Fecha de Retorno a Trabajar" value="{{ old('fecha_retorno', $permiso->fecha_retorno) }}" required />
            </div>

            <!-- Motivo -->
            <div>
                <flux:field>
                    <flux:label>Motivo o Justificación</flux:label>
                    <textarea name="motivo" rows="3" class="w-full rounded-lg border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 p-3 text-sm text-gray-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('motivo', $permiso->motivo) }}</textarea>
                    <flux:error name="motivo" />
                </flux:field>
            </div>

            <!-- Botones de Acción -->
            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('admin.permisos.index') }}">
                    <flux:button variant="subtle" type="button">Cancelar</flux:button>
                </a>
                <flux:button variant="primary" type="submit" color="green">Actualizar Solicitud</flux:button>
            </div>
        </form>
    </div>

    <!-- Script para autocalcular fechas en edición -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const diasInput = document.getElementById('dias_solicitados');
            const fechaInicioInput = document.getElementById('fecha_inicio');
            const fechaFinInput = document.getElementById('fecha_fin');
            const fechaRetornoInput = document.getElementById('fecha_retorno');

            // Feriados fijos nacionales de Bolivia (Formato 'MM-DD')
            const feriadosFijos = [
                '01-01', // Año Nuevo
                '01-22', // Día del Estado Plurinacional
                '06-21', // Año Nuevo Aymara Amazónico y del Chaco
                '05-01', // Día del Trabajo
                '08-06', // Independencia de Bolivia
                '11-02', // Todos los Difuntos
                '12-25', // Navidad
            ];

            // Función matemática para calcular el Domingo de Pascua de cualquier año (Algoritmo de Meeus/Jones/Butcher)
            function calcularPascua(anio) {
                const a = anio % 19;
                const b = Math.floor(anio / 100);
                const c = anio % 100;
                const d = Math.floor(b / 4);
                const e = b % 4;
                const f = Math.floor((b + 8) / 25);
                const g = Math.floor((b - f + 1) / 3);
                const h = (19 * a + b - d - g + 15) % 30;
                const i = Math.floor(c / 4);
                const k = c % 4;
                const l = (32 + 2 * e + 2 * i - h - k) % 7;
                const m = Math.floor((a + 11 * h + 22 * l) / 451);
                const mes = Math.floor((h + l - 7 * m + 114) / 31);
                const dia = ((h + l - 7 * m + 114) % 31) + 1;

                return new Date(anio, mes - 1, dia);
            }

            // Función para obtener dinámicamente los feriados móviles de CUALQUIER año
            function obtenerFeriadosMovibles(anio) {
                const pascua = calcularPascua(anio);

                // Viernes Santo (2 días antes del Domingo de Pascua)
                let viernesSanto = new Date(pascua);
                viernesSanto.setDate(pascua.getDate() - 2);

                // Lunes de Carnaval (48 días antes del Domingo de Pascua)
                let lunesCarnaval = new Date(pascua);
                lunesCarnaval.setDate(pascua.getDate() - 48);

                // Martes de Carnaval (47 días antes del Domingo de Pascua)
                let martesCarnaval = new Date(pascua);
                martesCarnaval.setDate(pascua.getDate() - 47);

                // Corpus Christi (60 días después del Domingo de Pascua)
                let corpusChristi = new Date(pascua);
                corpusChristi.setDate(pascua.getDate() + 60);

                return [
                    lunesCarnaval.toISOString().split('T')[0],
                    martesCarnaval.toISOString().split('T')[0],
                    viernesSanto.toISOString().split('T')[0],
                    corpusChristi.toISOString().split('T')[0]
                ];
            }

            // Función para verificar si una fecha es feriado
            function esFeriado(date) {
                const anio = date.getFullYear();
                const mes = String(date.getMonth() + 1).padStart(2, '0');
                const dia = String(date.getDate()).padStart(2, '0');
                const mesDia = `${mes}-${dia}`;
                const yyyyMmDd = `${anio}-${mesDia}`;

                // Validar si es feriado fijo
                if (feriadosFijos.includes(mesDia)) return true;

                // Validar si es feriado móvil calculado para ese año
                const feriadosMoviblesAnio = obtenerFeriadosMovibles(anio);
                if (feriadosMoviblesAnio.includes(yyyyMmDd)) return true;

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
                    let diaSemana = fechaActual.getDay(); // 0 = Dom, 1 = Lun, ..., 6 = Sáb
                    let valorDia = 1;

                    if (diaSemana === 0 || esFeriado(fechaActual)) {
                        valorDia = 0; // Domingo o Feriado no cuentan
                    } else if (diaSemana === 6) {
                        valorDia = 0.5; // Sábado medio día
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

                // 1. Fecha Fin (Último día de ausencia)
                fechaFinInput.value = ultimoDiaHabil.toISOString().split('T')[0];

                // 2. Fecha de Retorno (Primer día hábil siguiente)
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
