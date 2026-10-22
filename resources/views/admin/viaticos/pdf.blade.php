<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante_Viaticos_Viaje_#{{ $viaje->id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
                color: black !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .page-container {
                box-shadow: none !important;
                border: none !important;
                padding: 0 !important;
                margin: 0 !important;
                width: 100% !important;
            }
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-800 antialiased font-sans min-h-screen py-6 px-4">

    <!-- Barra Superior de Control (Se oculta al imprimir/guardar PDF) -->
    <div class="max-w-4xl mx-auto mb-4 flex justify-between items-center no-print">
        <a href="{{ route('admin.viaticos.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold text-sm rounded-lg shadow transition flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Volver al Listado
        </a>
        <div class="flex gap-2">
            <button onclick="window.print()" class="px-5 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-bold text-sm rounded-lg shadow-lg transition flex items-center gap-2 cursor-pointer">
                <i class="fas fa-print"></i> Imprimir / Guardar como PDF
            </button>
        </div>
    </div>

    <!-- Documento / Comprobante Oficial -->
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-lg border border-gray-200 page-container space-y-6">

        <!-- Encabezado Institucional -->
        <div class="flex justify-between items-center border-b-2 border-zinc-800 pb-4">
            <div class="flex items-center gap-4">
                <img src="{{ asset('img/logo_thegame1.png') }}" alt="Logo The Game" class="h-16 w-auto object-contain">
                <div>
                    <h1 class="text-xl font-black tracking-wider text-zinc-900 uppercase">THE GAME S.R.L.</h1>
                    <p class="text-xs font-semibold text-zinc-600">EVENTOS DEPORTIVOS Y SERVICIOS TÉCNICOS</p>
                    <p class="text-xs text-zinc-500">Santa Cruz de la Sierra - Bolivia</p>
                </div>
            </div>
            <div class="text-right border-l-2 border-zinc-200 pl-4">
                <h2 class="text-base font-extrabold text-indigo-700 uppercase">COMPROBANTE DE VIÁTICOS</h2>
                <p class="text-xs font-bold text-zinc-700 mt-0.5">N° REGISTRO: <span class="text-red-600">#{{ str_pad($viaje->id, 5, '0', STR_PAD_LEFT) }}</span></p>
                <p class="text-xs text-zinc-500">Emisión: {{ date('d/m/Y H:i') }}</p>
                <span class="inline-block mt-1 px-2.5 py-0.5 bg-emerald-100 text-emerald-800 text-[10px] font-black rounded uppercase border border-emerald-300">
                    ESTADO: PAGADO
                </span>
            </div>
        </div>

        <!-- Información General del Viaje -->
        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 grid grid-cols-2 md:grid-cols-4 gap-4 text-xs">
            <div>
                <span class="text-gray-500 font-bold uppercase block">Origen:</span>
                <span class="text-sm font-black text-gray-800">{{ $viaje->origen }}</span>
            </div>
            <div>
                <span class="text-gray-500 font-bold uppercase block">Destino:</span>
                <span class="text-sm font-black text-indigo-700">{{ $viaje->destino }}</span>
            </div>
            <div>
                <span class="text-gray-500 font-bold uppercase block">Fecha Salida:</span>
                <span class="text-sm font-bold text-gray-800">{{ \Carbon\Carbon::parse($viaje->fecha_inicio)->format('d/m/Y') }}</span>
            </div>
            <div>
                <span class="text-gray-500 font-bold uppercase block">Fecha Retorno:</span>
                <span class="text-sm font-bold text-gray-800">{{ \Carbon\Carbon::parse($viaje->fecha_fin)->format('d/m/Y') }}</span>
            </div>
        </div>

        <!-- Detalles Operativos y Recinto -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
            <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                <span class="text-gray-500 font-bold uppercase block mb-1">Motivo / Evento / Partido:</span>
                <p class="text-sm font-bold text-gray-900">{{ $viaje->motivo ?: 'No especificado' }}</p>
            </div>
            <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                <span class="text-gray-500 font-bold uppercase block mb-1">Estadio / Recinto Deportivo:</span>
                <p class="text-sm font-bold text-gray-900">{{ $viaje->estadio ?: 'No especificado' }}</p>
            </div>
        </div>

        <!-- Tabla de Personal Comisionado -->
        <div>
            <h3 class="text-xs font-black uppercase text-gray-700 mb-2 tracking-wide">
                DESGLOSE Y PLANILLA DE RECEPCIÓN DE VIÁTICOS ({{ $viaje->empleados->count() }} PERSONAS)
            </h3>
            <table class="w-full border-collapse border border-gray-300 text-xs">
                <thead>
                    <tr class="bg-zinc-800 text-white uppercase text-[11px]">
                        <th class="py-2 px-2 border border-gray-300 text-center w-10">N°</th>
                        <th class="py-2 px-3 border border-gray-300 text-left">Personal Comisionado</th>
                        <th class="py-2 px-2 border border-gray-300 text-center w-24">C.I.</th>
                        <th class="py-2 px-2 border border-gray-300 text-center w-20">Días</th>
                        <th class="py-2 px-2 border border-gray-300 text-right w-24">Monto p/p</th>
                        <th class="py-2 px-3 border border-gray-300 text-center w-44">Firma de Conformidad</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-300">
                    @foreach ($viaje->empleados as $empleado)
                        <tr>
                            <td class="py-3 px-2 border border-gray-300 text-center font-bold text-gray-600">{{ $loop->iteration }}</td>
                            <td class="py-3 px-3 border border-gray-300 font-bold text-gray-900">
                                {{ $empleado->nombre }} {{ $empleado->apellido }}
                            </td>
                            <td class="py-3 px-2 border border-gray-300 text-center font-semibold text-gray-700">
                                {{ $empleado->ci }}
                            </td>
                            <td class="py-3 px-2 border border-gray-300 text-center font-semibold">
                                {{ $viaje->dias_totales }} d
                            </td>
                            <td class="py-3 px-2 border border-gray-300 text-right font-black text-gray-900">
                                Bs. {{ number_format($empleado->pivot->monto_viatico ?? $viaje->total_por_persona, 2) }}
                            </td>
                            <td class="py-3 px-3 border border-gray-300 text-center">
                                <!-- Espacio para firma manuscrita de cada empleado comisionado -->
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-gray-100 font-black text-xs">
                        <td colspan="4" class="py-2.5 px-3 border border-gray-300 text-right uppercase text-gray-700">TOTAL DESEMBOLSADO:</td>
                        <td class="py-2.5 px-2 border border-gray-300 text-right text-indigo-700 text-sm">
                            Bs. {{ number_format($viaje->monto_total_viaje, 2) }}
                        </td>
                        <td class="border border-gray-300 bg-gray-50"></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Sección Uniforme de 3 Firmas Alineadas a la misma altura -->
        <div class="pt-20 grid grid-cols-3 gap-6 items-start text-center text-xs">

            <!-- Firma 1: Entregado Por -->
            <div>
                <div class="border-t-2 border-zinc-800 pt-2 mx-2">
                    <p class="font-black text-gray-900 uppercase">ENTREGADO CONFORME</p>
                    <p class="text-gray-500 font-medium">Caja / Administración</p>
                    <p class="text-[10px] text-gray-400 mt-0.5">THE GAME S.R.L.</p>
                </div>
            </div>

            <!-- Firma 2: Autorizado Por -->
            <div>
                <div class="border-t-2 border-zinc-800 pt-2 mx-2">
                    <p class="font-black text-gray-900 uppercase">AUTORIZADO POR</p>
                    <p class="text-gray-500 font-medium">Gerencia General</p>
                    <p class="text-[10px] text-gray-400 mt-0.5">THE GAME S.R.L.</p>
                </div>
            </div>

            <!-- Firma 3: Recibí Conforme -->
            <div>
                <div class="border-t-2 border-zinc-800 pt-2 mx-2">
                    <p class="font-black text-gray-900 uppercase">RECIBÍ CONFORME</p>
                    <div class="text-[11px] text-gray-600 font-semibold space-y-1 mt-1.5 text-left px-1">
                        <p>Nombre: <span class="border-b border-gray-400 inline-block w-32"></span></p>
                        <p>C.I.: <span class="border-b border-gray-400 inline-block w-36"></span></p>
                        <p>Fecha: <span class="border-b border-gray-400 inline-block w-32"></span></p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Pie de página institucional -->
        <div class="border-t border-gray-200 pt-3 text-center text-[10px] text-gray-400">
            Este comprobante es un documento de control interno emitido por el Sistema de Recursos Humanos de THE GAME S.R.L.
        </div>
    </div>

</body>
</html>
