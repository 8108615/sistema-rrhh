<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Boleta de Pago - {{ $pago->empleado->nombre }} {{ $pago->empleado->apellido }}</title>
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
        .info-table, .detalles-table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 3px 5px;
            font-size: 11px;
        }
        .info-table td.label {
            font-weight: bold;
            width: 130px;
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
        .text-right {
            text-align: right;
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

    <!-- Botón flotante para activar impresión rápida en navegador -->
    <div class="no-print" style="text-align: right; max-width: 700px; margin: 0 auto 15px auto;">
        <button onclick="window.print()" style="padding: 8px 16px; background: #2563eb; color: #fff; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">
            🖨️ Imprimir Boleta
        </button>
    </div>

    <div class="boleta-container">
        <!-- Cabecera con Logo y Título -->
        <table class="header-table">
            <tr>
                <td style="width: 20%;">
                    @if(!empty($ajuste->logo) && file_exists(public_path('storage/' . $ajuste->logo)))
                        <img src="{{ public_path('storage/' . $ajuste->logo) }}" alt="Logo Empresa" class="logo-img">
                    @else
                        <div style="font-weight: bold; font-size: 14px;">{{ $ajuste->nombre ?? 'EMPRESA' }}</div>
                    @endif
                </td>
                <td style="width: 80%; text-align: left;">
                    <div style="font-size: 13px; font-weight: bold;">EMPRESA: {{ strtoupper($ajuste->nombre ?? 'EMPRESA S.R.L.') }}</div>
                </td>
            </tr>
        </table>

        <div class="titulo-boleta">BOLETA DE PAGO</div>

        <!-- Datos del Empleado -->
        <table class="info-table" style="border-bottom: 1px solid #000; padding-bottom: 8px; margin-bottom: 5px;">
            <tr>
                <td class="label">NOMBRE:</td>
                <td class="bold">{{ strtoupper($pago->empleado->nombre ?? '') }} {{ strtoupper($pago->empleado->apellido ?? '') }}</td>
            </tr>
            <tr>
                <td class="label">C.I.:</td>
                <td>{{ $pago->empleado->ci ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">ÁREA:</td>
                <td>{{ $pago->empleado->area->nombre ?? 'No asignada' }}</td>
            </tr>
            <tr>
                <td class="label">SALARIO BÁSICO:</td>
                <td>{{ number_format($pago->salario_base, 2, '.', ',') }}</td>
            </tr>
            <tr>
                <td class="label">PERÍODO DE PAGO:</td>
                <td>{{ $pago->mes }} - {{ $pago->anio }}</td>
            </tr>
            <tr>
                <td class="label">DÍAS TRABAJADOS:</td>
                <td>30</td>
            </tr>
        </table>

        <!-- Tabla de Ingresos y Descuentos adaptada para Dompdf -->
        <table class="seccion-tabla">
            <thead>
                <tr>
                    <th style="width: 50%;">INGRESOS</th>
                    <th style="width: 50%;">DESCUENTOS</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding: 0;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <tr>
                                <td style="padding: 5px 8px; border: none;">SUELDO:</td>
                                <td style="padding: 5px 8px; border: none; text-align: right;">{{ number_format($pago->salario_base, 2, '.', ',') }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 5px 8px; border: none;">BONOS:</td>
                                <td style="padding: 5px 8px; border: none; text-align: right;">{{ number_format($pago->bonos, 2, '.', ',') }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 5px 8px; border: none;">OTROS:</td>
                                <td style="padding: 5px 8px; border: none; text-align: right;">0.00</td>
                            </tr>
                        </table>
                    </td>
                    <td style="padding: 0;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <tr>
                                <td style="padding: 5px 8px; border: none;">ANTICIPOS:</td>
                                <td style="padding: 5px 8px; border: none; text-align: right;">{{ number_format($pago->anticipos, 2, '.', ',') }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 5px 8px; border: none;">AFP.:</td>
                                <td style="padding: 5px 8px; border: none; text-align: right;">{{ number_format($pago->descuento_afp, 2, '.', ',') }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 5px 8px; border: none;">OTROS / RC-IVA:</td>
                                <td style="padding: 5px 8px; border: none; text-align: right;">{{ number_format($pago->otros_descuentos, 2, '.', ',') }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                @php
                    $totalGanado = $pago->salario_base + $pago->bonos;
                    $totalDescuentos = $pago->descuento_afp + $pago->anticipos + $pago->otros_descuentos;
                    $liquidoPagable = $totalGanado - $totalDescuentos;
                @endphp
                <tr class="bold">
                    <td>TOTAL GANADO Bs. <span style="float: right;">{{ number_format($totalGanado, 2, '.', ',') }}</span></td>
                    <td>TOTAL DESC. Bs. <span style="float: right;">{{ number_format($totalDescuentos, 2, '.', ',') }}</span></td>
                </tr>
                <tr class="bold" style="background-color: #f9f9f9;">
                    <td colspan="2">
                        LIQUIDO PAGABLE Bs. <span style="float: right; font-size: 13px;">{{ number_format($liquidoPagable, 2, '.', ',') }}</span>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Fecha de impresión inferior -->
        <div class="footer-fecha">
            Santa Cruz, {{ \Carbon\Carbon::parse($pago->fecha_pago)->locale('es')->translatedFormat('d \d\e F \d\e Y') }}
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
