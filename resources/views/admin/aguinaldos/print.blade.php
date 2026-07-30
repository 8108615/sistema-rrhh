<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Boleta de Aguinaldo - {{ $aguinaldo->empleado->nombre }} {{ $aguinaldo->empleado->apellido }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 20px;
        }
        .boleta-container {
            max-width: 700px;
            margin: 0 auto;
            border: 2px solid #000;
            padding: 15px;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .header-table td {
            vertical-align: middle;
        }
        .logo-img {
            max-height: 60px;
            max-width: 120px;
            object-fit: contain;
        }
        .titulo-boleta {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            text-decoration: underline;
            margin: 5px 0 15px 0;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 3px 5px;
            font-size: 11px;
        }
        .info-table td.label {
            font-weight: bold;
            width: 140px;
        }
        .seccion-tabla {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            border: 1px solid #000;
        }
        .seccion-tabla th, .seccion-tabla td {
            border: 1px solid #000;
            padding: 5px 8px;
            font-size: 11px;
            vertical-align: top;
        }
        .seccion-tabla th {
            background-color: #f2f2f2;
            text-align: left;
        }
        .bold {
            font-weight: bold;
        }
        .firmas-container {
            width: 100%;
            margin-top: 50px;
            border-collapse: collapse;
        }
        .firmas-container td {
            width: 50%;
            text-align: center;
            vertical-align: bottom;
            height: 60px;
            padding: 0 30px;
        }
        .linea-firma {
            border-top: 1px solid #000;
            padding-top: 5px;
            font-size: 11px;
            font-weight: bold;
        }
        .footer-fecha {
            text-align: center;
            margin-top: 25px;
            font-size: 11px;
        }
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
            .boleta-container { border: 2px solid #000; }
        }
    </style>
</head>
<body>

    <!-- Botón flotante para activar impresión rápida -->
    <div class="no-print" style="text-align: right; max-width: 700px; margin: 0 auto 15px auto;">
        <button onclick="window.print()" style="padding: 8px 16px; background: #2563eb; color: #fff; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">
            🖨️ Imprimir Boleta de Aguinaldo
        </button>
    </div>

    <div class="boleta-container">
        <!-- Cabecera con Logo y Título -->
        <table class="header-table">
            <tr>
                <td style="width: 20%;">
                    @if(!empty($ajuste->logo))
                        <img src="{{ asset('storage/' . $ajuste->logo) }}" alt="Logo Empresa" class="logo-img">
                    @else
                        <div style="font-weight: bold; font-size: 14px;">{{ $ajuste->nombre ?? 'EMPRESA' }}</div>
                    @endif
                </td>
                <td style="width: 80%; text-align: left;">
                    <div style="font-size: 13px; font-weight: bold;">EMPRESA: {{ strtoupper($ajuste->nombre ?? 'EMPRESA S.R.L.') }}</div>
                </td>
            </tr>
        </table>

        <div class="titulo-boleta">BOLETA DE PAGO DE {{ strtoupper($aguinaldo->tipo) }}</div>

        <!-- Datos del Empleado -->
        <table class="info-table" style="border-bottom: 1px solid #000; padding-bottom: 8px; margin-bottom: 5px;">
            <tr>
                <td class="label">NOMBRE:</td>
                <td class="bold">{{ strtoupper($aguinaldo->empleado->nombre ?? '') }} {{ strtoupper($aguinaldo->empleado->apellido ?? '') }}</td>
            </tr>
            <tr>
                <td class="label">C.I.:</td>
                <td>{{ $aguinaldo->empleado->ci ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">ÁREA:</td>
                <td>{{ $aguinaldo->empleado->area->nombre ?? 'No asignada' }}</td>
            </tr>
            <tr>
                <td class="label">GESTIÓN / AÑO:</td>
                <td>{{ $aguinaldo->gestion }}</td>
            </tr>
            <tr>
                <td class="label">ÚLTIMO SALARIO:</td>
                <td>{{ number_format($aguinaldo->ultimo_salario, 2, '.', ',') }}</td>
            </tr>
            <tr>
                <td class="label">MESES TRABAJADOS:</td>
                <td>{{ $aguinaldo->meses_trabajados }} meses</td>
            </tr>
            <tr>
                <td class="label">DÍAS TRABAJADOS:</td>
                <td>{{ $aguinaldo->dias_trabajados }} días</td>
            </tr>
        </table>

        <!-- Tabla de Detalle -->
        <table class="seccion-tabla">
            <thead>
                <tr>
                    <th style="width: 70%;">CONCEPTO</th>
                    <th style="width: 30%; text-align: right;">MONTO ({{ $simboloMoneda }})</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Pago de {{ strtolower($aguinaldo->tipo) }} correspondiente a la gestión {{ $aguinaldo->gestion }}</td>
                    <td style="text-align: right;">{{ number_format($aguinaldo->monto_pagar, 2, '.', ',') }}</td>
                </tr>
                <tr class="bold" style="background-color: #f9f9f9;">
                    <td>TOTAL LÍQUIDO PAGABLE</td>
                    <td style="text-align: right; font-size: 13px;">{{ number_format($aguinaldo->monto_pagar, 2, '.', ',') }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Fecha de impresión inferior -->
        <div class="footer-fecha">
            Santa Cruz, {{ $aguinaldo->fecha_pago ? \Carbon\Carbon::parse($aguinaldo->fecha_pago)->locale('es')->translatedFormat('d \d\e F \d\e Y') : now()->locale('es')->translatedFormat('d \d\e F \d\e Y') }}
        </div>

        <!-- Firmas -->
        <table class="firmas-container">
            <tr>
                <td>
                    <div class="linea-firma">Recibí Conforme</div>
                </td>
                <td>
                    <div class="linea-firma">{{ strtoupper($ajuste->nombre ?? 'EMPRESA S.R.L.') }}<br><span style="font-size: 9px; font-weight: normal;">DPTO. DE RECURSOS HUMANOS</span></div>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
