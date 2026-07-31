<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprobante de Retroactivo - {{ $retroactivo->empleado->nombre }} {{ $retroactivo->empleado->apellido }}</title>
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
            padding: 6px 8px;
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

    <!-- Botón flotante para activar impresión rápida -->
    <div class="no-print" style="text-align: right; max-width: 700px; margin: 0 auto 15px auto;">
        <button onclick="window.print()" style="padding: 8px 16px; background: #2563eb; color: #fff; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">
            🖨️ Imprimir Comprobante
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

        <div class="titulo-boleta">COMPROBANTE DE PAGO RETROACTIVO</div>

        <!-- Datos del Empleado y Gestión -->
        <table class="info-table" style="border-bottom: 1px solid #000; padding-bottom: 8px; margin-bottom: 5px;">
            <tr>
                <td class="label">NOMBRE:</td>
                <td class="bold">{{ strtoupper($retroactivo->empleado->nombre ?? '') }} {{ strtoupper($retroactivo->empleado->apellido ?? '') }}</td>
            </tr>
            <tr>
                <td class="label">C.I.:</td>
                <td>{{ $retroactivo->empleado->ci ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">ÁREA / CARGO:</td>
                <td>{{ $retroactivo->empleado->area->nombre ?? ($retroactivo->empleado->cargo ?? 'No asignada') }}</td>
            </tr>
            <tr>
                <td class="label">GESTIÓN (AÑO):</td>
                <td>{{ $retroactivo->gestion }}</td>
            </tr>
            <tr>
                <td class="label">INCREMENTO APLICADO:</td>
                <td>{{ number_format($retroactivo->porcentaje, 2, '.', ',') }}%</td>
            </tr>
            <tr>
                <td class="label">ESTADO:</td>
                <td class="bold">{{ strtoupper($retroactivo->estado) }}</td>
            </tr>
        </table>

        <!-- Tabla de Detalle Salarial y Cálculo del Retroactivo -->
        <table class="seccion-tabla">
            <thead>
                <tr>
                    <th style="width: 60%;">CONCEPTO / DETALLE</th>
                    <th style="width: 40%; text-align: right;">MONTO ({{ $simboloMoneda }})</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Sueldo Anterior</td>
                    <td class="text-right">{{ number_format($retroactivo->sueldo_anterior, 2, '.', ',') }}</td>
                </tr>
                <tr>
                    <td>Sueldo Nuevo (con {{ number_format($retroactivo->porcentaje, 2, '.', ',') }}% de incremento)</td>
                    <td class="text-right">{{ number_format($retroactivo->sueldo_nuevo, 2, '.', ',') }}</td>
                </tr>
                <tr>
                    <td>Diferencia Salarial Mensual</td>
                    <td class="text-right">{{ number_format($retroactivo->diferencia_mensual, 2, '.', ',') }}</td>
                </tr>
                <tr>
                    <td>Meses Aplicados</td>
                    <td class="text-right">{{ $retroactivo->meses_aplicados }} mes(es)</td>
                </tr>
                <tr class="bold" style="background-color: #f9f9f9; font-size: 12px;">
                    <td>TOTAL A PAGAR RETROACTIVO</td>
                    <td class="text-right" style="font-size: 13px;">{{ number_format($retroactivo->monto_pagar, 2, '.', ',') }}</td>
                </tr>
            </tbody>
        </table>

        @if($retroactivo->observaciones)
            <div style="margin-top: 10px; font-size: 11px;">
                <span class="bold">OBSERVACIONES:</span> {{ $retroactivo->observaciones }}
            </div>
        @endif

        <!-- Fecha de impresión inferior -->
        <div class="footer-fecha">
            Santa Cruz, {{ $retroactivo->fecha_pago ? \Carbon\Carbon::parse($retroactivo->fecha_pago)->locale('es')->translatedFormat('d \d\e F \d\e Y') : \Carbon\Carbon::now()->locale('es')->translatedFormat('d \d\e F \d\e Y') }}
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
