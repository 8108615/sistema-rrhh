<x-layouts::app title="Gestión de Retroactivos">
    <div class="relative mb-6 w-full flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <flux:heading size="xl" level="1">Gestión de Retroactivos</flux:heading>
            <flux:subheading>Administra el cálculo, pago y control de incrementos salariales retroactivos por gestión.</flux:subheading>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <button type="button" onclick="openCalculoRetroactivoModal()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg shadow-sm transition flex items-center gap-2 text-sm cursor-pointer">
                <i class="fas fa-calculator"></i> Cálculo Masivo
            </button>

            @if(isset($retroactivos) && $retroactivos->total() > 0)
                <a href="{{ route('admin.retroactivos.print.general', ['gestion' => $gestion]) }}" target="_blank" class="px-4 py-2 bg-zinc-700 hover:bg-zinc-800 text-white font-semibold rounded-lg shadow-sm transition flex items-center gap-2 text-sm cursor-pointer">
                    <i class="fas fa-print"></i> Imprimir Planilla General
                </a>
            @endif

            <flux:button href="{{ route('admin.retroactivos.create') }}" variant="primary" icon="plus" color="blue">
                Nuevo Registro
            </flux:button>
        </div>
    </div>

    <div class="rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4 shadow-sm mb-6">
        <form method="GET" action="{{ route('admin.retroactivos.index') }}" class="flex flex-col lg:flex-row items-center justify-between gap-4">

            <div class="flex flex-col sm:flex-row items-end gap-3 w-full lg:w-auto">
                <div class="w-full sm:w-36">
                    <label for="gestion" class="block text-xs font-bold text-gray-500 uppercase mb-1">Gestión</label>
                    <select id="gestion" name="gestion" class="w-full rounded-lg border border-gray-300 bg-white py-2 px-3 text-sm text-gray-950 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white">
                        @for ($i = date('Y'); $i >= 2020; $i--)
                            <option value="{{ $i }}" {{ $gestion == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition flex items-center justify-center gap-2 cursor-pointer text-sm">
                        <i class="fas fa-filter"></i> Filtrar
                    </button>
                    <a href="{{ route('admin.retroactivos.index') }}" class="px-3 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg transition flex items-center justify-center text-sm cursor-pointer" title="Limpiar filtros">
                        <i class="fas fa-undo"></i>
                    </a>
                </div>
            </div>

            <div class="w-full lg:w-72">
                <label for="inputBuscador" class="block text-xs font-bold text-gray-500 uppercase mb-1">Buscar Empleado / Área</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                        <i class="fas fa-search text-xs"></i>
                    </span>
                    <input type="text" id="inputBuscador" placeholder="Escribe para filtrar..." class="w-full rounded-lg border border-gray-300 bg-white py-2 pl-9 pr-3 text-sm text-gray-950 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white">
                </div>
            </div>

        </form>
    </div>

    <flux:separator variant="subtle" class="mb-6" />

    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 shadow-sm">
        <table class="min-w-full border-collapse">
            <thead class="bg-gray-50 dark:bg-zinc-900 text-center">
                <tr>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 uppercase">Nro</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 uppercase">Empleado</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 uppercase">Área / Cargo</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 uppercase">Sueldo Antiguo</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 uppercase">% Incremento</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 uppercase">Sueldo Nuevo</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 uppercase">Monto de Retroactivo</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 uppercase">Fecha Pago</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 uppercase">Estado</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-zinc-800 text-center">
                @forelse ($retroactivos as $index => $item)
                    <tr class="hover:bg-blue-50 dark:hover:bg-zinc-700/50 transition">
                        <td class="px-3 py-3 border border-gray-200 dark:border-zinc-700 text-sm">
                            {{ $retroactivos->firstItem() + $index }}
                        </td>
                        <td class="px-4 py-3 border border-gray-200 dark:border-zinc-700 text-sm text-left font-semibold">
                            {{ $item->empleado->nombre ?? 'N/D' }} {{ $item->empleado->apellido ?? '' }}
                            <div class="text-xs text-gray-500 font-normal">CI: {{ $item->empleado->ci ?? 'N/D' }}</div>
                        </td>
                        <td class="px-4 py-3 border border-gray-200 dark:border-zinc-700 text-sm text-gray-600 dark:text-zinc-400">
                            {{ $item->empleado->area->nombre ?? ($item->empleado->cargo ?? 'Sin Área') }}
                        </td>
                        <td class="px-4 py-3 border border-gray-200 dark:border-zinc-700 text-sm font-mono text-gray-700 dark:text-zinc-300">
                            {{ $simboloMoneda }} {{ number_format($item->sueldo_anterior, 2, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 border border-gray-200 dark:border-zinc-700 text-sm font-mono font-bold text-blue-600 dark:text-blue-400">
                            {{ number_format($item->porcentaje, 2, ',', '.') }}%
                        </td>
                        <td class="px-4 py-3 border border-gray-200 dark:border-zinc-700 text-sm font-mono text-gray-700 dark:text-zinc-300">
                            {{ $simboloMoneda }} {{ number_format($item->sueldo_nuevo, 2, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 border border-gray-200 dark:border-zinc-700 text-sm font-bold font-mono text-emerald-600 dark:text-emerald-400">
                            {{ $simboloMoneda }} {{ number_format($item->monto_pagar, 2, ',', '.') }}
                            <span class="text-xs text-gray-500 font-normal block">({{ $item->meses_aplicados }} meses)</span>
                        </td>
                        <td class="px-4 py-3 border border-gray-200 dark:border-zinc-700 text-sm text-gray-600 dark:text-zinc-400">
                            @if($item->fecha_pago)
                                {{ \Carbon\Carbon::parse($item->fecha_pago)->format('d/m/Y') }}
                            @else
                                <span class="text-xs text-gray-400 italic">No registrada</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 border border-gray-200 dark:border-zinc-700 text-sm">
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $item->estado == 'Pagado' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300' }}">
                                {{ $item->estado }}
                            </span>
                        </td>
                        <td class="px-4 py-3 border border-gray-200 dark:border-zinc-700">
                            <div class="flex justify-center items-center gap-1.5">
                                <a href="{{ route('admin.retroactivos.show', $item->id) }}" class="px-2.5 py-1.5 bg-sky-500 hover:bg-sky-600 text-white text-xs font-medium rounded-md shadow-sm transition flex items-center" title="Ver">
                                    <i class="fas fa-eye mr-1"></i> Ver
                                </a>

                                <a href="{{ route('admin.retroactivos.print', $item->id) }}" target="_blank" title="Imprimir Comprobante" class="px-2.5 py-1.5 hover:bg-zinc-600 text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-200 transition">
                                    <i class="fas fa-print text-sm"></i>
                                </a>

                                <a href="{{ route('admin.retroactivos.edit', $item->id) }}" class="px-2.5 py-1.5 bg-green-500 hover:bg-green-600 text-white text-xs font-medium rounded-md shadow-sm transition flex items-center" title="Editar">
                                    <i class="fas fa-edit mr-1"></i> Editar
                                </a>

                                <form action="{{ route('admin.retroactivos.destroy', $item->id) }}" method="POST" class="inline-flex delete-form-retroactivo" id="formEliminarRetroactivo{{ $item->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2.5 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs font-medium rounded-md shadow-sm transition cursor-pointer flex items-center" onclick="confirmarEliminacionRetroactivo{{ $item->id }}(event)" title="Eliminar">
                                        <i class="fas fa-trash-alt mr-1"></i> Eliminar
                                    </button>
                                </form>
                                <script>
                                    function confirmarEliminacionRetroactivo{{ $item->id }}(e) {
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
                                                document.getElementById('formEliminarRetroactivo{{ $item->id }}').submit();
                                            }
                                        });
                                    }
                                </script>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="px-6 py-6 text-center text-sm text-gray-500 dark:text-zinc-400">
                            No se encontraron registros retroactivos para la gestión seleccionada.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $retroactivos->links() }}
    </div>

    <script>
        function openCalculoRetroactivoModal() {
            Swal.fire({
                title: 'Cálculo Masivo de Retroactivos',
                html: `
                    <div class="text-left flex flex-col gap-3 mt-3">
                        <div>
                            <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Gestión (Año)</label>
                            <input type="number" id="swal-gestion" class="swal2-input !m-0 !w-full" value="{{ $gestion }}">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Porcentaje de Incremento (%)</label>
                            <input type="number" step="0.01" id="swal-porcentaje" class="swal2-input !m-0 !w-full" placeholder="Ej. 3.0" value="3.00">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Meses Aplicados</label>
                            <input type="number" id="swal-meses" class="swal2-input !m-0 !w-full" placeholder="Ej. 5" value="5">
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
                    const porcentaje = document.getElementById('swal-porcentaje').value;
                    const meses = document.getElementById('swal-meses').value;

                    if (!gestion || !porcentaje || !meses) {
                        Swal.showValidationMessage('Por favor completa todos los campos');
                    }
                    return { gestion: gestion, porcentaje: porcentaje, meses_aplicados: meses };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const data = result.value;
                    let form = document.createElement('form');
                    form.method = 'POST';
                    form.action = "{{ route('admin.retroactivos.calcular') }}";

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

                    let porcentajeInput = document.createElement('input');
                    porcentajeInput.type = 'hidden';
                    porcentajeInput.name = 'porcentaje';
                    porcentajeInput.value = data.porcentaje;
                    form.appendChild(porcentajeInput);

                    let mesesInput = document.createElement('input');
                    mesesInput.type = 'hidden';
                    mesesInput.name = 'meses_aplicados';
                    mesesInput.value = data.meses_aplicados;
                    form.appendChild(mesesInput);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const inputBuscador = document.getElementById('inputBuscador');
            const filas = document.querySelectorAll('tbody tr');

            inputBuscador.addEventListener('keyup', function () {
                const texto = this.value.toLowerCase().trim();

                filas.forEach(fila => {
                    if (fila.cells.length < 3) return;

                    const textoEmpleado = fila.cells[1].textContent.toLowerCase();
                    const textoArea = fila.cells[2].textContent.toLowerCase();

                    if (textoEmpleado.includes(texto) || textoArea.includes(texto)) {
                        fila.style.display = '';
                    } else {
                        fila.style.display = 'none';
                    }
                });
            });
        });
    </script>
</x-layouts::app>