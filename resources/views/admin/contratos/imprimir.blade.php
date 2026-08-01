<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Contrato de Trabajo - {{ $contrato->empleado->nombre }} {{ $contrato->empleado->apellido }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 14pt;
            line-height: 1.6;
            color: #000;
            margin: 2cm;
        }
        .header-title {
            text-align: center;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 20px;
            font-size: 13pt;
        }
        .clause-title {
            font-weight: bold;
            text-decoration: underline;
            margin-top: 15px;
        }
        justify {
            text-align: justify;
        }
        .signatures {
            margin-top: 80px;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
        }
        .signature-box {
            text-align: center;
            width: 45%;
            display: inline-block;
        }
        /* Estilos específicos para impresión en papel */
        @media print {
            body {
                margin: 1.5cm;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>

    <!-- Botón flotante para imprimir (no sale en el papel) -->
    <div class="no-print" style="position: fixed; top: 20px; right: 20px; background: #fff; padding: 10px; border: 1px solid #ccc; box-shadow: 0 2px 5px rgba(0,0,0,0.1); border-radius: 8px;">
        <button onclick="window.print()" style="background: #000; color: #fff; border: none; padding: 10px 20px; font-size: 12pt; cursor: pointer; border-radius: 5px;">🖨️ Imprimir Contrato</button>
        <a href="{{ route('admin.contratos.index') }}" style="margin-left: 10px; text-decoration: none; color: #333; font-size: 11pt;">Volver al listado</a>
    </div>

    <!-- Contenido del Contrato basado en las fotos -->
    <div style="text-align: center; margin-bottom: 30px;">
        <div style="font-weight: bold; font-size: 16pt;">THE GAME</div>
    </div>

    <div class="header-title">
        CONTRATO INDIVIDUAL DE TRABAJO A CONCLUSIÓN DE SERVICIO O REALIZACIÓN DE LABOR DETERMINADA
    </div>

    <p style="text-align: justify;">
        Conste por el presente <strong>CONTRATO INDIVIDUAL DE TRABAJO A CONCLUSIÓN DE SERVICIO O REALIZACIÓN DE LABOR DETERMINADA</strong>, que surtirá efectos entre partes de conformidad a las condiciones, términos y modalidad que se estipulan a continuación:
    </p>

    <p class="clause-title">PRIMERA: PARTES.-</p>
    <p style="text-align: justify;">Las partes del contrato son las siguientes:</p>

    <ol style="text-align: justify;">
        <li style="margin-bottom: 15px;">
            <strong>THE GAME S.R.L.</strong>, una sociedad constituida bajo las leyes del Estado Plurinacional de Bolivia, identificada con NIT 159990024, con domicilio en la calle Eucalipto #60, Doble Vía a la Guardia y 4to anillo, Barrio Jenecherú, Santa Cruz - Bolivia, debidamente representada por su apoderado, el Señor José Maria Quiroga... [datos del representante legal], en lo sucesivo denominado simplemente como <strong>LA EMPRESA</strong>.
        </li>
        <li>
            El Sr. <strong>{{ strtoupper($contrato->empleado->nombre) }} {{ strtoupper($contrato->empleado->apellido) }}</strong>, mayor de edad, hábil por ley, de nacionalidad boliviana, con C.I. <strong>{{ $contrato->empleado->ci }}</strong>, con domicilio en <strong>{{ $contrato->empleado->direccion ?? 'Santa Cruz de la Sierra' }}</strong>, quien en lo sucesivo y para los efectos de este contrato se lo denominará simplemente <strong>EL TRABAJADOR</strong>.
        </li>
    </ol>

    <p class="clause-title">SEGUNDA: ANTECEDENTES.-</p>
    <p style="text-align: justify;">
        La EMPRESA ha suscrito un contrato con la Empresa L&Q SPORT'S TV RIGHTS S.R.L., para brindar los servicios de producción audiovisual... vigente desde el <strong>{{ \Carbon\Carbon::parse($contrato->fecha_inicio)->format('d de F de Y') }}</strong> al <strong>{{ $contrato->fecha_fin ? \Carbon\Carbon::parse($contrato->fecha_fin)->format('d de F de Y') : 'conclusión de la labor' }}</strong>, por lo que se efectúa la presente contratación bajo la modalidad de <strong>{{ strtoupper($contrato->tipo_contrato) }}</strong>.
    </p>

    <p class="clause-title">TERCERA: CARGO Y LUGAR DE TRABAJO.-</p>
    <p style="text-align: justify;">
        La EMPRESA contrata los servicios del TRABAJADOR bajo la modalidad de obra o labor determinada, para desempeñar las funciones de <strong>"{{ strtoupper($contrato->cargo_contrato) }}"</strong>...
    </p>

    <p class="clause-title">OCTAVA: REMUNERACIÓN BÁSICA MENSUAL.-</p>
    <p style="text-align: justify;">
        La remuneración básica mensual del TRABAJADOR que se ha pactado es de <strong>Bs. {{ number_format($contrato->salario_mensual, 2, ',', '.') }}</strong>, suma que será cancelada de forma mensual por la EMPRESA en moneda nacional.
    </p>

    <!-- Espacio para firmas -->
    <div class="signatures">
        <div class="signature-box" style="float: left;">
            <p>___________________________________</p>
            <p><strong>ING. JOSE MARIA QUIROGA</strong><br>Por la EMPRESA</p>
        </div>
        <div class="signature-box" style="float: right;">
            <p>___________________________________</p>
            <p><strong>{{ strtoupper($contrato->empleado->nombre) }} {{ strtoupper($contrato->empleado->apellido) }}</strong><br>EL TRABAJADOR</p>
        </div>
    </div>

    <div style="clear: both; margin-top: 50px; text-align: center;">
        <p><strong>INSPECTORÍA DEL TRABAJO</strong></p>
    </div>

</body>
</html>
