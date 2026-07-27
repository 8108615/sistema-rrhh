<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planilla de Sueldos - {{ $planilla->mes }} {{ $planilla->anio }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #111;
            margin: 0;
            padding: 20px;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0;
            color: #555;
            font-size: 14px;
        }
        .info-box {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            background: #f9f9f9;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .info-box div {
            font-size: 13px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
        }
        td {
            font-size: 11px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            text-align: center;
        }
        .signature-line {
            width: 200px;
            border-top: 1px solid #333;
            margin-top: 50px;
            padding-top: 5px;
            font-size: 11px;
        }
        @media print {
            .no-print {
                display: none;
            }
            body {
                padding: 0;
            }
        }
    </style>
</head>
<body onload="window.print();">

    @php
        $simboloMoneda = $ajuste->divisa ?? 'Bs.';
    @endphp

    <!-- Botón flotante para reintentar impresión si es necesario -->
    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print();" style="padding: 10px 20px; background: #0284c7; color: #fff; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">
            Imprimir / Guardar PDF Nuevamente
        </button>
    </div>

    <div class="header">
        <h1>Reporte de Planilla de Sueldos y Salarios</h1>
        <p>Periodo: <strong>{{ $planilla->mes }} de {{ $planilla->anio }}</strong></p>
    </div>

    <div class="info-box">
        <div><strong>Estado de la Planilla:</strong> {{ $planilla->estado }}</div>
        <div><strong>Total Empleados:</strong> {{ $planilla->detalles->count() }}</div>
        <div><strong>Monto Total General:</strong> {{ number_format($planilla->total_pagado, 2, ',', '.') }} {{ $simboloMoneda }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>N°</th>
                <th>Empleado</th>
                <th>Cédula de Identidad (CI)</th>
                <th>Cargo / Departamento</th>
                <th class="text-right">Salario Base</th>
                <th class="text-right">Bonos</th>
                <th class="text-right">Descuento AFP (12.71%)</th>
                <th class="text-right">Líquido Pagable</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($planilla->detalles as $index => $detalle)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td><strong>{{ $detalle->empleado->nombre }} {{ $detalle->empleado->apellido }}</strong></td>
                    <td>{{ $detalle->empleado->ci }}</td>
                    <td>{{ $detalle->empleado->departamento->nombre ?? 'N/A' }}</td>
                    <td class="text-right">{{ number_format($detalle->salario_base, 2, ',', '.') }}</td>
                    <td class="text-right">+ {{ number_format($detalle->bonos, 2, ',', '.') }}</td>
                    <td class="text-right">- {{ number_format($detalle->descuentos, 2, ',', '.') }}</td>
                    <td class="text-right"><strong>{{ number_format($detalle->liquido_pagable, 2, ',', '.') }} {{ $simboloMoneda }}</strong></td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #f9f9f9; font-weight: bold;">
                <td colspan="4" class="text-right">TOTAL GENERAL:</td>
                <td colspan="4" class="text-right" style="font-size: 13px;">{{ number_format($planilla->total_pagado, 2, ',', '.') }} {{ $simboloMoneda }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <div>
            <div class="signature-line">Elaborado por (RRHH)</div>
        </div>
        <div>
            <div class="signature-line">Aprobado por (Gerencia / Administración)</div>
        </div>
    </div>

</body>
</html>
