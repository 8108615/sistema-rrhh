<x-layouts::app title="Gestión de Aguinaldos">
    <div class="relative mb-6 w-full flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <flux:heading size="xl" level="1">Gestión de Aguinaldos</flux:heading>
            <flux:subheading>Administra el cálculo, pago y control de aguinaldos y dobles aguinaldos por gestión.</flux:subheading>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <!-- Botón de Cálculo Masivo -->
            <button type="button" onclick="openCalculoModal()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg shadow-sm transition flex items-center gap-2 text-sm cursor-pointer">
                <i class="fas fa-calculator"></i> Cálculo Masivo
            </button>

            <!-- Botón de Imprimir Planilla General (Aparece solo si hay registros calculados) -->
            @if(isset($aguinaldos) && $aguinaldos->count() > 0)
                <a href="{{ route('admin.aguinaldos.print.general', ['gestion' => $gestion, 'tipo' => $tipo]) }}" target="_blank" class="px-4 py-2 bg-zinc-700 hover:bg-zinc-800 text-white font-semibold rounded-lg shadow-sm transition flex items-center gap-2 text-sm">
                    <i class="fas fa-print"></i> Imprimir Planilla [{{ $tipo }}]
                </a>
            @endif
            <!-- Botón Nuevo Registro Individual -->
            <a href="{{ route('admin.aguinaldos.create') }}">
                <flux:button variant="primary" icon="plus" color="blue">Nuevo Registro</flux:button>
            </a>
        </div>
    </div>

    <!-- Filtros de Búsqueda (Gestión y Tipo) -->
    <div class="rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4 shadow-sm mb-6">
        <form method="GET" action="{{ route('admin.aguinaldos.index') }}" class="flex flex-col sm:flex-row items-center gap-4">
            <div class="w-full sm:w-1/3">
                <label for="gestion" class="block text-xs font-bold text-gray-500 uppercase mb-1">Gestión (Año)</label>
                <select id="gestion" name="gestion" class="w-full rounded-lg border border-gray-300 bg-white py-2 px-3 text-sm text-gray-950 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white">
                    @for ($i = date('Y'); $i >= date('Y') - 5; $i--)
                        <option value="{{ $i }}" {{ $gestion == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>

            <div class="w-full sm:w-1/3">
                <label for="tipo" class="block text-xs font-bold text-gray-500 uppercase mb-1">Tipo de Beneficio</label>
                <select id="tipo" name="tipo" class="w-full rounded-lg border border-gray-300 bg-white py-2 px-3 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white">
                    <option value="Aguinaldo" {{ $tipo == 'Aguinaldo' ? 'selected' : '' }}>Aguinaldo</option>
                    <option value="Doble Aguinaldo" {{ $tipo == 'Doble Aguinaldo' ? 'selected' : '' }}>Doble Aguinaldo</option>
                </select>
            </div>

            <div class="w-full sm:w-1/3 flex items-end gap-2 pt-5 sm:pt-0">
                <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition flex items-center justify-center gap-2 cursor-pointer text-sm">
                    <i class="fas fa-filter"></i> Filtrar
                </button>
                <a href="{{ route('admin.aguinaldos.index') }}" class="w-full sm:w-auto px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg transition flex items-center justify-center gap-2 text-sm">
                    <i class="fas fa-undo"></i> Limpiar
                </a>
            </div>
        </form>
    </div>

    <flux:separator variant="subtle" class="mb-6" />

    <!-- Tabla -->
    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 shadow-sm">
        <table class="min-w-full border-collapse">
            <thead class="bg-gray-50 dark:bg-zinc-900 text-center">
                <tr>
                    <th class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 uppercase">Nro</th>
                    <th class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 uppercase">Empleado</th>
                    <th class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 uppercase">Área / Cargo</th>
                    <th class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 uppercase">Último Salario</th>
                    <th class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 uppercase">Meses / Días Tr.</th>
                    <th class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 uppercase">Monto a Pagar</th>
                    <th class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 uppercase">Estado</th>
                    <th class="px-6 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-zinc-800 text-center">
                @forelse ($aguinaldos as $item)
                    <tr class="hover:bg-blue-50 dark:hover:bg-zinc-700/50 transition">
                        <td class="px-3 py-3 border border-gray-200 dark:border-zinc-700 text-sm">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3 border border-gray-200 dark:border-zinc-700 text-sm text-left font-semibold">
                            {{ $item->empleado->nombre ?? 'N/D' }} {{ $item->empleado->apellido ?? '' }}
                            <div class="text-xs text-gray-500 font-normal">CI: {{ $item->empleado->ci ?? 'N/D' }}</div>
                        </td>
                        <td class="px-4 py-3 border border-gray-200 dark:border-zinc-700 text-sm text-gray-600 dark:text-zinc-400">
                            {{ $item->empleado->area->nombre ?? 'Sin Área' }}
                        </td>
                        <td class="px-4 py-3 border border-gray-200 dark:border-zinc-700 text-sm font-mono">
                            Bs. {{ number_format($item->ultimo_salario, 2, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 border border-gray-200 dark:border-zinc-700 text-sm">
                            {{ $item->meses_trabajados }} meses ({{ $item->dias_trabajados }} días)
                        </td>
                        <td class="px-4 py-3 border border-gray-200 dark:border-zinc-700 text-sm font-bold font-mono text-emerald-600 dark:text-emerald-400">
                            Bs. {{ number_format($item->monto_pagar, 2, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 border border-gray-200 dark:border-zinc-700 text-sm">
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $item->estado == 'Pagado' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300' }}">
                                {{ $item->estado }}
                            </span>
                        </td>
                        <td class="px-4 py-3 border border-gray-200 dark:border-zinc-700">
                            <div class="flex justify-center items-center gap-1.5">
                                <!-- Ver -->
                                <a href="{{ route('admin.aguinaldos.show', $item->id) }}" class="px-3 py-1.5 bg-sky-500 hover:bg-sky-600 text-white text-xs font-medium rounded-md shadow-sm transition flex items-center">
                                    <i class="fas fa-eye mr-1"></i> Ver
                                </a>

                                <a href="{{ route('admin.aguinaldos.edit', $item->id) }}" class="px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white text-xs font-medium rounded-md shadow-sm transition flex items-center">
                                    <i class="fas fa-edit mr-1"></i> Editar
                                </a>

                                <!-- Imprimir -->
                                <a href="{{ route('admin.aguinaldos.print', $item->id) }}" target="_blank" class="px-3 py-1.5 bg-zinc-600 hover:bg-zinc-700 text-white text-xs font-medium rounded-md shadow-sm transition flex items-center">
                                    <i class="fas fa-print mr-1"></i> Imprimir
                                </a>

                                <!-- Eliminar -->
                                <form action="{{ route('admin.aguinaldos.destroy', $item->id) }}" method="POST" class="inline-flex delete-form-aguinaldo" id="formEliminarAguinaldo{{ $item->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs font-medium rounded-md shadow-sm transition cursor-pointer flex items-center" onclick="confirmarEliminacionAguinaldo{{ $item->id }}(event)">
                                        <i class="fas fa-trash-alt mr-1"></i> Eliminar
                                    </button>
                                </form>
                                <script>
                                    function confirmarEliminacionAguinaldo{{ $item->id }}(e) {
                                        e.preventDefault();
                                        Swal.fire({
                                            title: '¿Estás seguro?',
                                            text: "¡No podrás revertir esta acción!",
                                            icon: 'warning',
                                            showCancelButton: true,
                                            confirmButtonColor: '#d33',
                                            cancelButtonColor: '#3085d6',
                                            confirmButtonText: 'Sí, eliminar',
                                            cancelButtonText: 'Cancelar'
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                document.getElementById('formEliminarAguinaldo{{ $item->id }}').submit();
                                            }
                                        });
                                    }
                                </script>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-6 text-center text-sm text-gray-500 dark:text-zinc-400">
                            No se encontraron registros de aguinaldos para la gestión y tipo seleccionados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Script para el Modal de Cálculo Masivo -->
    <script>
        function openCalculoModal() {
            Swal.fire({
                title: 'Cálculo Masivo de Aguinaldos',
                html: `
                    <div class="text-left flex flex-col gap-3 mt-3">
                        <div>
                            <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Gestión (Año)</label>
                            <input type="number" id="swal-gestion" class="swal2-input !m-0 !w-full" value="{{ $gestion }}">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Tipo de Beneficio</label>
                            <select id="swal-tipo" class="swal2-input !m-0 !w-full">
                                <option value="Aguinaldo" {{ $tipo == 'Aguinaldo' ? 'selected' : '' }}>Aguinaldo</option>
                                <option value="Doble Aguinaldo" {{ $tipo == 'Doble Aguinaldo' ? 'selected' : '' }}>Doble Aguinaldo</option>
                            </select>
                        </div>
                    </div>
                `,
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: 'Calcular Ahora',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#10b981',
                preConfirm: () => {
                    const gestion = document.getElementById('swal-gestion').value;
                    const tipo = document.getElementById('swal-tipo').value;

                    if (!gestion) {
                        Swal.showValidationMessage('Por favor ingresa una gestión válida');
                    }
                    return { gestion: gestion, tipo: tipo };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const data = result.value;
                    let form = document.createElement('form');
                    form.method = 'POST';
                    form.action = "{{ route('admin.aguinaldos.calcular') }}";

                    let csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = '{{ csrf_token() }}';
                    form.appendChild(csrfInput);

                    let gestionInput = document.createElement('input');
                    gestionInput.type = 'hidden';
                    gestionInput.name = 'gestion';
                    gestionInput.value = data.gestion;
                    form.appendChild(gestionInput);

                    let tipoInput = document.createElement('input');
                    tipoInput.type = 'hidden';
                    tipoInput.name = 'tipo';
                    tipoInput.value = data.tipo;
                    form.appendChild(tipoInput);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
</x-layouts::app>
