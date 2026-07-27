<x-layouts::app title="Generar Planilla">
    <div class="relative mb-6 w-full flex justify-between items-center">
        <div>
            <flux:heading size="xl" level="1">Generar Nueva Planilla</flux:heading>
            <flux:subheading>Se procesará automáticamente el sueldo para todos los empleados activos.</flux:subheading>
        </div>
        <div>
            <a href="{{ route('admin.planillas.index') }}">
                <flux:button variant="subtle" icon="arrow-left">Volver al listado</flux:button>
            </a>
        </div>
    </div>

    <flux:separator variant="subtle" class="mb-6" />

    <!-- Mostrar errores de validación si los hay -->
    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 dark:bg-red-950/50 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 rounded-xl text-sm space-y-1">
            <div class="font-semibold flex items-center gap-2">
                <flux:icon name="exclamation-circle" class="h-5 w-5 text-red-500 flex-shrink-0" />
                <span>Por favor corrige los siguientes errores:</span>
            </div>
            <ul class="list-disc list-inside pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl p-6 shadow-sm max-w-2xl">
        <form action="{{ route('admin.planillas.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Selección de Mes -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Mes de la Planilla</label>
                <select name="mes" class="w-full rounded-lg border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 px-3 py-2 text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <option value="">Seleccione el mes...</option>
                    <option value="Enero" {{ old('mes') == 'Enero' ? 'selected' : '' }}>Enero</option>
                    <option value="Febrero" {{ old('mes') == 'Febrero' ? 'selected' : '' }}>Febrero</option>
                    <option value="Marzo" {{ old('mes') == 'Marzo' ? 'selected' : '' }}>Marzo</option>
                    <option value="Abril" {{ old('mes') == 'Abril' ? 'selected' : '' }}>Abril</option>
                    <option value="Mayo" {{ old('mes') == 'Mayo' ? 'selected' : '' }}>Mayo</option>
                    <option value="Junio" {{ old('mes') == 'Junio' ? 'selected' : '' }}>Junio</option>
                    <option value="Julio" {{ old('mes') == 'Julio' ? 'selected' : '' }}>Julio</option>
                    <option value="Agosto" {{ old('mes') == 'Agosto' ? 'selected' : '' }}>Agosto</option>
                    <option value="Septiembre" {{ old('mes') == 'Septiembre' ? 'selected' : '' }}>Septiembre</option>
                    <option value="Octubre" {{ old('mes') == 'Octubre' ? 'selected' : '' }}>Octubre</option>
                    <option value="Noviembre" {{ old('mes') == 'Noviembre' ? 'selected' : '' }}>Noviembre</option>
                    <option value="Diciembre" {{ old('mes') == 'Diciembre' ? 'selected' : '' }}>Diciembre</option>
                </select>
            </div>

            <!-- Año -->
            <div>
                <flux:input
                    type="number"
                    name="anio"
                    label="Año"
                    value="{{ old('anio', date('Y')) }}"
                    placeholder="Ej. 2026"
                    required
                />
            </div>

            <!-- Aviso informativo -->
            <div class="p-4 bg-sky-50 dark:bg-sky-950/40 border border-sky-200 dark:border-sky-800 text-sky-700 dark:text-sky-300 rounded-xl text-sm flex items-start gap-3">
                <flux:icon name="information-circle" class="h-5 w-5 text-sky-500 flex-shrink-0 mt-0.5" />
                <div>
                    <span class="font-semibold block mb-0.5">Nota importante:</span>
                    Al generar la planilla, el sistema tomará de forma automática a todos los empleados que se encuentren con estado <strong>Activo</strong> y registrará sus sueldos base actuales.
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 dark:border-zinc-700">
                <a href="{{ route('admin.planillas.index') }}">
                    <flux:button variant="ghost" type="button">Cancelar</flux:button>
                </a>
                <flux:button variant="primary" type="submit">Generar Planilla</flux:button>
            </div>
        </form>
    </div>
</x-layouts::app>
