<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprobante de Finiquito - {{ $finiquito->empleado->nombre }} {{ $finiquito->empleado->apellido }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 15px;
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
            margin-bottom: 5px;
        }
        .header-table td {
            vertical-align: middle;
        }
        .logo-img {
            max-height: 50px;
            max-width: 100px;
            object-fit: contain;
        }
        .titulo-boleta {
            text-align: center;
            font-size: 15px;
            font-weight: bold;
            text-decoration: underline;
            margin: 5px 0 10px 0;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
            margin-bottom: 8px;
        }
        .info-table td {
            padding: 2px 4px;
            font-size: 10.5px;
        }
        .info-table td.label {
            font-weight: bold;
            width: 150px;
        }
        .seccion-tabla {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            border: 1px solid #000;
        }
        .seccion-tabla th, .seccion-tabla td {
            border: 1px solid #000;
            padding: 4px 6px;
            font-size: 10.5px;
            vertical-align: top;
        }
        .seccion-tabla th {
            background-color: #f2f2f2;
            text-align: left;
        }
        .text-right {
            text-align: right;
        }
        .bold {
            font-weight: bold;
        }
        .firmas-container {
            width: 100%;
            margin-top: 40px;
            border-collapse: collapse;
        }
        .firmas-container td {
            width: 50%;
            text-align: center;
            vertical-align: bottom;
            height: 50px;
            padding: 0 20px;
        }
        .linea-firma {
            border-top: 1px solid #000;
            padding-top: 4px;
            font-size: 10.5px;
            font-weight: bold;
        }
        .footer-fecha {
            text-align: center;
            margin-top: 15px;
            font-size: 10.5px;
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
    <div class="no-print" style="text-align: right; max-width: 700px; margin: 0 auto 10px auto;">
        <button onclick="window.print()" style="padding: 6px 14px; background: #2563eb; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; font-size: 11px;">
            🖨️ Imprimir Finiquito
        </button>
    </div>

    <div class="boleta-container">
        <!-- Cabecera con Logo y Título de la Empresa -->
        <table class="header-table">
            <tr>
                <td style="width: 20%;">
                    @if(!empty($ajuste->logo))
                        <img src="{{ asset('storage/' . $ajuste->logo) }}" alt="Logo Empresa" class="logo-img">
                    @else
                        <div style="font-weight: bold; font-size: 13px;">{{ $ajuste->nombre ?? 'EMPRESA' }}</div>
                    @endif
                </td>
                <td style="width: 80%; text-align: left;">
                    <div style="font-size: 12px; font-weight: bold;">EMPRESA: {{ strtoupper($ajuste->nombre ?? 'EMPRESA S.R.L.') }}</div>
                </td>
            </tr>
        </table>

        <div class="titulo-boleta">CERTIFICADO / HOJA DE LIQUIDACIÓN DE BENEFICIOS SOCIALES</div>

        <!-- Datos del Empleado y del Finiquito -->
        <table class="info-table">
            <tr>
                <td class="label">TRABAJADOR:</td>
                <td class="bold">{{ strtoupper($finiquito->empleado->nombre ?? '') }} {{ strtoupper($finiquito->empleado->apellido ?? '') }}</td>
            </tr>
            <tr>
                <td class="label">C.I.:</td>
                <td>{{ $finiquito->empleado->ci ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">ÁREA / DEPARTAMENTO:</td>
                <td>{{ $finiquito->empleado->area->nombre ?? 'No asignada' }}</td>
            </tr>
            <tr>
                <td class="label">FECHA DE INGRESO:</td>
                <td>{{ \Carbon\Carbon::parse($finiquito->fecha_ingreso)->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td class="label">FECHA DE RETIRO:</td>
                <td>{{ \Carbon\Carbon::parse($finiquito->fecha_retiro)->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td class="label">TIEMPO DE SERVICIO:</td>
                <td>{{ $finiquito->anos_servicio }} años equivalentes</td>
            </tr>
            <tr>
                <td class="label">CAUSAL DE RETIRO:</td>
                <td class="bold">{{ strtoupper($finiquito->causal_retiro) }}</td>
            </tr>
            <tr>
                <td class="label">BASES SALARIALES:</td>
                <td>Último Salario: {{ $simboloMoneda }} {{ number_format($finiquito->ultimo_salario, 2, '.', ',') }} | Promedio 3 Meses: {{ $simboloMoneda }} {{ number_format($finiquito->promedio_tres_salarios, 2, '.', ',') }}</td>
            </tr>
        </table>

        <!-- Tabla de Desglose de Beneficios Sociales -->
        <table class="seccion-tabla">
            <thead>
                <tr>
                    <th style="width: 70%;">CONCEPTO DE BENEFICIOS SOCIALES</th>
                    <th style="width: 30%; text-align: right;">MONTO ({{ $simboloMoneda }})</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Indemnización por Tiempo de Servicios</td>
                    <td class="text-right">{{ number_format($finiquito->monto_indemnizacion, 2, '.', ',') }}</td>
                </tr>
                <tr>
                    <td>Desahucio (3 Meses - Si corresponde)</td>
                    <td class="text-right">{{ number_format($finiquito->monto_desahucio, 2, '.', ',') }}</td>
                </tr>
                <tr>
                    <td>Aguinaldo Proporcional</td>
                    <td class="text-right">{{ number_format($finiquito->monto_aguinaldo, 2, '.', ',') }}</td>
                </tr>
                <tr>
                    <td>Vacación Compensatoria / Proporcional</td>
                    <td class="text-right">{{ number_format($finiquito->monto_vacacion, 2, '.', ',') }}</td>
                </tr>
                <tr class="bold" style="background-color: #f9f9f9; font-size: 11.5px;">
                    <td>TOTAL GENERAL A PAGAR</td>
                    <td class="text-right">{{ $simboloMoneda }} {{ number_format($finiquito->total_beneficios, 2, '.', ',') }}</td>
                </tr>
            </tbody>
        </table>

        @if($finiquito->observaciones)
        <div style="margin-top: 8px; font-size: 10px;">
            <strong>Observaciones:</strong> {{ $finiquito->observaciones }}
        </div>
        @endif

        <!-- Fecha de impresión inferior -->
        <div class="footer-fecha">
            Santa Cruz, {{ \Carbon\Carbon::parse($finiquito->fecha_retiro)->locale('es')->translatedFormat('d \d\e F \d\e Y') }}
        </div>

        <!-- Firmas -->
        <table class="firmas-container">
            <tr>
                <td>
                    <div class="linea-firma">Recibí Conforme (Trabajador)</div>
                </td>
                <td>
                    <div class="linea-firma">{{ strtoupper($ajuste->nombre ?? 'EMPRESA S.R.L.') }}<br><span style="font-size: 8.5px; font-weight: normal;">DPTO. DE RECURSOS HUMANOS</span></div>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
