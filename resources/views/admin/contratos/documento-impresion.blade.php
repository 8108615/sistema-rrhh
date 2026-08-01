<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Contrato - {{ $contrato->empleado->nombre ?? '' }} {{ $contrato->empleado->apellido ?? '' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body {
                background-color: white !important;
                -webkit-print-color-adjust: exact;
            }
            .no-print {
                display: none !important;
            }
            @page {
                size: letter;
                margin: 2cm;
            }
        }
    </style>
</head>
<body class="bg-zinc-100 text-zinc-900 font-sans p-6" onload="window.print()">

    <div class="max-w-4xl mx-auto mb-6 flex justify-between items-center no-print">
        <a href="{{ route('admin.contratos.index') }}" class="px-4 py-2 bg-zinc-200 text-zinc-800 rounded-lg text-sm font-semibold hover:bg-zinc-300">
            &larr; Volver a Contratos
        </a>
        <button onclick="window.print()" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 shadow">
            🖨️ Imprimir Nuevamente
        </button>
    </div>

    <div class="max-w-4xl mx-auto bg-white p-10 shadow-lg rounded-xl border border-zinc-200 space-y-6 text-justify leading-relaxed text-sm">
        
        <div class="flex justify-end mb-4">
            @if(isset($ajuste) && $ajuste->logo)
                <img src="{{ asset('storage/' . $ajuste->logo) }}" alt="Logo Empresa" class="h-16 w-auto object-contain">
            @else
                <div class="border border-zinc-400 px-3 py-1 rounded-full text-xs font-bold tracking-widest text-center">
                    THE GAME
                </div>
            @endif
        </div>

        <div class="text-center font-bold uppercase space-y-1 mb-8">
            <h2 class="text-base">CONTRATO INDIVIDUAL DE TRABAJO A CONCLUSIÓN DE SERVICIO O REALIZACIÓN DE LABOR DETERMINADA</h2>
        </div>

        <p>
            Conste por el presente <strong>CONTRATO INDIVIDUAL DE TRABAJO A CONCLUSIÓN DE SERVICIO O REALIZACIÓN DE LABOR DETERMINADA</strong>, que surtirá efectos entre partes de conformidad a las condiciones, términos y modalidad que se estipulan a continuación:
        </p>

        <div>
            <p class="font-bold uppercase"><u>PRIMERA: PARTES.-</u> Las partes del contrato son las siguientes:</p>
            <ol class="list-decimal list-inside space-y-2 mt-2">
                <li><strong>{{ isset($ajuste) ? strtoupper($ajuste->nombre) : 'THE GAME S.R.L.' }}</strong>, con domicilio en {{ isset($ajuste) ? $ajuste->direccion : 'la calle Eucalipto #60' }}, representada por su apoderado el Señor José Maria Quiroga..., en lo sucesivo <strong>LA EMPRESA</strong>.</li>
                <li>El Sr. <strong>{{ strtoupper($contrato->empleado->nombre ?? '') }} {{ strtoupper($contrato->empleado->apellido ?? '') }}</strong>, con C.I. <strong>{{ $contrato->empleado->ci ?? '' }} SC</strong>, domiciliado en {{ $contrato->empleado->direccion ?? 'Santa Cruz' }}, en lo sucesivo <strong>EL TRABAJADOR</strong>.</li>
            </ol>
        </div>

        <div>
            <p class="font-bold uppercase"><u>SEGUNDA: ANTECEDENTES.-</u></p>
            <p>La EMPRESA ha suscrito un contrato con la Empresa <strong>L&Q SPORT'S TV RIGHTS S.R.L.</strong>, para brindar servicios de producción audiovisual...</p>
        </div>

        <div>
            <p class="font-bold uppercase"><u>TERCERA: CARGO Y LUGAR DE TRABAJO.-</u></p>
            <p>Se contrata al TRABAJADOR bajo la modalidad de obra o labor determinada para desempeñar las funciones de <strong>"{{ strtoupper($contrato->cargo_contrato) }}"</strong>.</p>
        </div>

        <div>
            <p class="font-bold uppercase"><u>CUARTA: FUNCIONES DEL TRABAJADOR.-</u></p>
            <p>Cumplir con las instructivas de su inmediato superior, asistencia regular y mantener estricta reserva profesional.</p>
        </div>

        <div>
            <p class="font-bold uppercase"><u>QUINTA: VIGENCIA DEL CONTRATO.-</u></p>
            <p>Rige desde el <strong>{{ \Carbon\Carbon::parse($contrato->fecha_inicio)->format('d/m/Y') }}</strong> hasta el cumplimiento total del servicio.</p>
        </div>

        <div>
            <p class="font-bold uppercase"><u>SEXTA: JORNADA LABORAL.-</u></p>
            <p>Sujeto a las necesidades operativas de la EMPRESA conforme al Art. 48 de la Ley General del Trabajo.</p>
        </div>

        <div>
            <p class="font-bold uppercase"><u>SÉPTIMA: HORAS EXTRAORDINARIAS.-</u></p>
            <p>Sujeto a autorización previa del inmediato superior conforme al Art. 55° de la Ley General del Trabajo.</p>
        </div>

        <div>
            <p class="font-bold uppercase"><u>OCTAVA: REMUNERACIÓN BÁSICA MENSUAL.-</u></p>
            <p>La remuneración mensual pactada es de <strong>Bs. {{ number_format($contrato->salario_mensual, 2, ',', '.') }}</strong>, actuando la EMPRESA como Agente de Retención de Ley.</p>
        </div>

        <div>
            <p class="font-bold uppercase"><u>NOVENA: DERECHOS Y OBLIGACIONES.-</u></p>
            <p>Gozará de los beneficios de la Ley General del Trabajo, seguro médico en la Caja Nacional de Salud y la obligación de precautelar los bienes de la EMPRESA.</p>
        </div>

        <div>
            <p class="font-bold uppercase"><u>DÉCIMA A VIGÉSIMA: DISPOSICIONES FINALES.-</u></p>
            <p>Sujeción plena a las prohibiciones, régimen de sanciones, ley contra el racismo (Ley N° 45), herederos y domicilios legales establecidos.</p>
        </div>

        <p class="text-center font-semibold pt-4">
            Santa Cruz, {{ \Carbon\Carbon::parse($contrato->fecha_inicio)->format('d') }} de 
            @php
                $meses = ['01' => 'enero', '02' => 'febrero', '03' => 'marzo', '04' => 'abril', '05' => 'mayo', '06' => 'junio', '07' => 'julio', '08' => 'agosto', '09' => 'septiembre', '10' => 'octubre', '11' => 'noviembre', '12' => 'diciembre'];
                $mesNum = \Carbon\Carbon::parse($contrato->fecha_inicio)->format('m');
                $nombreMes = $meses[$mesNum] ?? '';
            @endphp
            {{ $nombreMes }} de {{ \Carbon\Carbon::parse($contrato->fecha_inicio)->format('Y') }}
        </p>

        <div class="pt-12 grid grid-cols-2 gap-8 text-center mt-8">
            <div class="border-t border-zinc-400 pt-2">
                <p class="font-bold">ING. JOSE MARIA QUIROGA</p>
                <p class="text-xs text-zinc-500">Por la EMPRESA</p>
            </div>
            <div class="border-t border-zinc-400 pt-2">
                <p class="font-bold uppercase">{{ strtoupper($contrato->empleado->nombre ?? '') }} {{ strtoupper($contrato->empleado->apellido ?? '') }}</p>
                <p class="text-xs text-zinc-500">EL TRABAJADOR</p>
            </div>
        </div>
        
        <div class="text-center pt-6">
            <p class="text-xs text-zinc-400 uppercase tracking-widest">Inspectoría del Trabajo</p>
        </div>

    </div>

</body>
</html>