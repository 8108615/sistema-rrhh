<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Certificado de Trabajo - {{ $empleado->nombre ?? '' }} {{ $empleado->apellido ?? '' }}</title>
    <style>
        body {
            font-family: 'Helvetica, Arial, sans-serif';
            color: #1A202C;
            line-height: 1.6;
            margin: 0;
            padding: 40px;
        }
        .header-container {
            width: 100%;
            margin-bottom: 30px;
            position: relative;
            min-height: 70px;
        }
        .logo {
            position: absolute;
            right: 0;
            top: 0;
            width: 70px;
            height: auto;
        }
        .title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 40px;
            margin-bottom: 40px;
            letter-spacing: 1px;
            text-decoration: underline;
        }
        .content {
            text-align: justify;
            font-size: 13.5px;
            margin-bottom: 30px;
        }
        .content p {
            margin-bottom: 20px;
        }
        .signature-section {
            margin-top: 100px;
            text-align: right;
            padding-right: 50px;
        }
        .signature-name {
            font-weight: bold;
            font-size: 13px;
            color: #1A202C;
        }
        .signature-role {
            font-size: 11px;
            color: #4A5568;
        }
        .footer {
            position: absolute;
            bottom: 30px;
            left: 40px;
            right: 40px;
            text-align: center;
            font-size: 9px;
            color: #718096;
            border-top: 1px solid #CBD5E0;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    <div class="header-container">
        <!-- Logo oficial desde public/img/logo_thegame.png -->
        <img src="{{ public_path('img/logo_thegame.png') }}" alt="Logo The Game" class="logo">
    </div>

    <div class="title">
        CERTIFICADO DE TRABAJO
    </div>

    <div class="content">
        <p style="font-weight: bold; font-size: 14px; margin-bottom: 30px;">
            LA EMPRESA THE GAME S.R.L. CERTIFICA:
        </p>

        <p>
            Que el/la Sr.(a) <strong>{{ $empleado->nombre ?? '' }} {{ $empleado->apellido ?? '' }}</strong> con C.I. <strong>{{ $empleado->ci ?? 'N/A' }}</strong>, trabaja en la empresa perteneciente, ocupando el cargo de <strong>{{ $empleado->area->nombre ?? 'N/A' }}</strong>, función que desempeña con responsabilidad y eficiencia, desde el <strong>{{ isset($empleado->fecha_ingreso) ? \Carbon\Carbon::parse($empleado->fecha_ingreso)->format('d/m/Y') : '____/____/________' }}</strong> hasta la fecha, percibiendo un salario mensual de Bs. {{ number_format($empleado->salario, 2, ',', '.') }}.
        </p>

        <p style="margin-top: 30px;">
            Se expide el presente documento para los fines que hubiere lugar.
        </p>

        <p style="margin-top: 40px;">
            Santa Cruz, {{ now()->format('d') }} de {{ now()->translatedFormat('F') }} de {{ now()->format('Y') }}
        </p>
    </div>

    <div class="signature-section">
        <div class="signature-name">THE GAME S.R.L.</div>
        <div class="signature-role">DPTO. DE RECURSOS HUMANOS</div>
    </div>

    <div class="footer">
        4to. Anillo C/ Eucalipto #60 / Cód. Postal 1503 / Santa Cruz - Bolivia / Tel./Fax: (591-3) 355-6565
    </div>

</body>
</html>
