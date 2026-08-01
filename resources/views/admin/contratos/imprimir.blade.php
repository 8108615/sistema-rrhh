<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Contrato - {{ $contrato->empleado->nombre ?? '' }} {{ $contrato->empleado->apellido ?? '' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11pt;
            line-height: 1.35;
            color: #000;
            background-color: white;
            margin: 0;
            padding: 0;
        }

        .justified-text {
            text-align: justify;
            text-justify: inter-word;
        }

        .page-sheet {
            width: 21.59cm;
            min-height: 27.94cm;
            margin: 0 auto;
            background: white;
            padding: 2.5cm;
            box-sizing: border-box;
        }

        @media print {
            body {
                background: white !important;
                -webkit-print-color-adjust: exact;
            }
            .no-print {
                display: none !important;
            }
            .page-sheet {
                padding: 0 !important;
                margin: 0 !important;
                width: 100% !important;
                min-height: auto !important;
                box-shadow: none !important;
            }
            @page {
                size: letter;
                margin: 2cm; /* Empareja el margen físico de la impresora */
            }
        }
    </style>
</head>
<body onload="window.print()">

    <!-- Panel superior (Oculto al imprimir) -->
    <div class="bg-zinc-800 text-white py-3 px-6 flex justify-between items-center no-print shadow-md">
        <a href="{{ route('admin.contratos.index') }}" class="text-sm font-semibold hover:underline">
            &larr; Volver a Contratos
        </a>
        <span class="text-xs text-zinc-300">Vista previa del documento</span>
        <button onclick="window.print()" class="px-4 py-1.5 bg-blue-600 text-white rounded text-sm font-semibold hover:bg-blue-700 shadow">
            🖨️ Imprimir
        </button>
    </div>

    <!-- Hoja del Contrato -->
    <div class="page-sheet space-y-4 text-justify">
        
        <!-- Logo superior derecho -->
        <div class="flex justify-end mb-4">
            @if(isset($ajuste) && $ajuste->logo)
                <img src="{{ asset('storage/' . $ajuste->logo) }}" alt="Logo Empresa" class="h-16 w-auto object-contain">
            @else
                <div class="border border-black px-2 py-1 text-xs font-bold text-center">
                    THE GAME
                </div>
            @endif
        </div>

        <!-- Título Principal -->
        <div class="text-center font-bold uppercase mb-6 text-sm">
            CONTRATO INDIVIDUAL DE TRABAJO A CONCLUSIÓN DE SERVICIO O REALIZACIÓN DE LABOR DETERMINADA
        </div>

        <p class="justified-text">
            Conste por el presente <strong>CONTRATO INDIVIDUAL DE TRABAJO A CONCLUSIÓN DE SERVICIO O REALIZACIÓN DE LABOR DETERMINADA</strong>, que surtirá efectos entre partes de conformidad a las condiciones, términos y modalidad que se estipulan a continuación:
        </p>

        <!-- PRIMERA -->
        <div class="space-y-2">
            <p class="justified-text"><strong><u>PRIMERA: PARTES.-</u></strong> Las partes del contrato son las siguientes:</p>
            <p class="justified-text pl-4">
                <strong>1. THE GAME S.R.L</strong>, una sociedad constituida bajo las leyes del Estado Plurinacional de Bolivia, identificada con NIT 159990024, con domicilio en la calle Eucalipto #60, Doble Vía a la Guardia y 4to anillo, Barrio Jenecherú, Santa Cruz - Bolivia, debidamente representada por su apoderado, el Señor José María Quiroga, mayor de edad, hábil por ley, con cédula de identidad de extranjero E-5386893, según autorización conferida mediante Instrumento de Poder N° 093/2022 de 25 de Marzo de 2022, otorgado ante Notaria de Fe Pública N°16, a cargo de la Dra. Mary Dolly Guardia Pérez, del Distrito Judicial de Santa Cruz, en lo sucesivo denominado simplemente como <strong>LA EMPRESA</strong>.
            </p>
            <p class="justified-text pl-4">
                <strong>2. El Sr. {{ strtoupper($contrato->empleado->nombre ?? '') }} {{ strtoupper($contrato->empleado->apellido ?? '') }}</strong>, mayor de edad, hábil por ley, de nacionalidad boliviana, con C.I. <strong>{{ $contrato->empleado->ci ?? '' }} SC</strong>, de {{ $contrato->empleado->edad ?? '33' }} años de edad, estado civil {{ $contrato->empleado->estado_civil ?? 'soltero' }} con domicilio en {{ $contrato->empleado->direccion ?? 'Santa Cruz' }}, de esta ciudad de Santa Cruz de la Sierra, quien, en lo sucesivo y para los efectos de este contrato se lo denominará simplemente <strong>EL TRABAJADOR</strong>.
            </p>
        </div>

        <!-- SEGUNDA -->
        <div>
            <p class="justified-text"><strong><u>SEGUNDA: ANTECEDENTES.-</u></strong> La EMPRESA ha suscrito un contrato con la Empresa <strong>L&Q SPORT'S TV RIGHTS S.R.L.</strong>, para brindar los servicios de producción audiovisual para la televisación de partidos de fútbol del torneo de La Liga Profesional de Fútbol Español (LALIGA EA SPORTS) y la Liga de Segunda División de Fútbol Español (LALIGA Hypermotion), donde incluye el control técnico de emisión de dichos partidos, desde el 01 de Agosto del 2025 al 30 de Junio de 2026, para lo cual se requiere del personal necesario, apto y especializado, por lo que conforme a la normativa laboral vigente, la EMPRESA debe efectuar la presente contratación bajo la modalidad de CONTRATO INDIVIDUAL DE TRABAJO POR REALIZACIÓN DE LABOR DETERMINADA.</p>
        </div>

        <!-- TERCERA -->
        <div>
            <p class="justified-text"><strong><u>TERCERA: CARGO Y LUGAR DE TRABAJO.-</u></strong> La EMPRESA conforme a los antecedentes indicados precedentemente, contrata los servicios del TRABAJADOR bajo la modalidad de obra o labor determinada, para desempeñar las funciones de <strong>"{{ strtoupper($contrato->cargo_contrato ?? 'TÉCNICO') }}"</strong>, para la labor determinada de producción audiovisual por el tiempo que dure el contrato que se tiene suscrito con la empresa L&Q SPORT'S TV RIGHTS S.R.L., conforme a los requerimientos, los cuales están sujetos a la programación realizada por la mencionada empresa, que se desarrollará de acuerdo a las necesidades de dicha empresa, por lo que EL TRABAJADOR deberá cumplir sus labores en el lugar que le indique su inmediato superior o las jefaturas pertinentes de la EMPRESA, para cuyo efecto, expresa su consentimiento para los traslados que sean necesarios, comprometiéndose a cumplir a cabalidad con las condiciones, obligaciones, sugerencias e instrucciones que le sean dadas por la EMPRESA, la misma que está obligada a cubrir los gastos correspondientes de viáticos para dichos traslados.</p>
        </div>

        <!-- CUARTA -->
        <div>
            <p class="justified-text"><strong><u>CUARTA: FUNCIONES DEL TRABAJADOR.-</u></strong> El trabajador deberá cumplir con las siguientes funciones principales:</p>
            <ol class="list-decimal pl-6 space-y-1 mt-1">
                <li>Cumplir con instructivas y/o tareas de su inmediato superior y las que le indique la Empresa.</li>
                <li>Cumplir con la asistencia en los días y horarios que se la requiera.</li>
                <li>Realizar sus actividades con responsabilidad y respeto hacia los colegas.</li>
                <li>Cumplir con lo establecido en las comunicaciones internas de la Empresa.</li>
                <li>Cumplir con todas las demás funciones específicas asignadas a su cargo por la EMPRESA con diligencia y cabalidad.</li>
            </ol>
        </div>

        <!-- QUINTA -->
        <div class="space-y-2">
            <p class="justified-text"><strong><u>QUINTA: VIGENCIA DEL CONTRATO.-</u></strong> El presente contrato se encuentra vigente desde el 1 de agosto 2025 hasta el cumplimiento total del contrato suscrito entre THE GAME S.R.L. y L&Q SPORT'S TV RIGHTS S.R.L., conforme a los requerimientos que solicite la empresa, los cuales están sujetos a la programación establecida, conforme se tiene descrito en la cláusula TERCERA de este contrato. Concluido el plazo y cumplido el contrato suscrito con L&Q SPORT'S TV RIGHTS S.R.L., la relación laboral quedará extinguida entre las partes, sin necesidad de aviso alguno.</p>
            <p class="justified-text">Se aclara que, por la naturaleza del presente contrato, en lo pertinente a la inamovilidad laboral se estará a lo establecido en el Art. 5º, parágrafo II, del DS N° 0012 del 19 de Febrero de 2009.</p>
        </div>

        <!-- SEXTA -->
        <div>
            <p class="justified-text"><strong><u>SEXTA: JORNADA LABORAL.-</u></strong> Sujeto a las necesidades operativas de la EMPRESA conforme al Art. 48 de la Ley General del Trabajo.</p>
        </div>

        <!-- SÉPTIMA -->
        <div>
            <p class="justified-text"><strong><u>SÉPTIMA: HORAS EXTRAORDINARIAS.-</u></strong> Sujeto a autorización previa del inmediato superior conforme al Art. 55° de la Ley General del Trabajo.</p>
        </div>

        <!-- OCTAVA -->
        <div>
            <p class="justified-text"><strong><u>OCTAVA: REMUNERACIÓN BÁSICA MENSUAL.-</u></strong> La remuneración mensual pactada es de <strong>Bs. 5.000,00</strong>, actuando la EMPRESA como Agente de Retención de Ley.</p>
        </div>

        <!-- Fecha -->
        <p class="text-center font-semibold pt-6">
            Santa Cruz, 1 de agosto de 2025
        </p>

        <!-- Firmas -->
        <div class="pt-16 grid grid-cols-2 gap-16 text-center mt-8">
            <div class="border-t border-black pt-1">
                <p class="font-bold text-xs">ING. JOSE MARIA QUIROGA</p>
                <p class="text-[9pt]">Por la EMPRESA</p>
            </div>
            <div class="border-t border-black pt-1">
                <p class="font-bold text-xs">ERICK FERNANDO MORALES GIL</p>
                <p class="text-[9pt]">EL TRABAJADOR</p>
            </div>
        </div>

        <!-- Inspectoría -->
        <div class="text-center pt-8">
            <p class="text-xs font-semibold tracking-wider">INSPECTORÍA DEL TRABAJO</p>
        </div>

    </div>

</body>
</html>