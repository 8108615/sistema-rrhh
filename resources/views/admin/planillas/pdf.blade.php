<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Planilla de Sueldos - {{ $planilla->mes }} {{ $planilla->anio }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #111;
            margin: 0;
            padding: 10px;
            font-size: 11px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0;
            color: #555;
            font-size: 13px;
        }
        /* Reemplazo de .info-box para compatibilidad total con Dompdf */
        .info-table {
            width: 100%;
            margin-bottom: 15px;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 8px 10px;
            font-size: 12px;
            border: none;
        }
        table.content-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table.content-table th, table.content-table td {
            border: 1px solid #ccc;
            padding: 6px 8px;
            text-align: left;
        }
        table.content-table th {
            background-color: #f2f2f2;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
        }
        table.content-table td {
            font-size: 10px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        /* Reemplazo del footer flex por tabla para alinear firmas en PDF */
        .footer {
            margin-top: 40px;
            width: 100%;
        }
        .signature-table {
            width: 100%;
            border-collapse: collapse;
        }
        .signature-table td {
            width: 50%;
            text-align: center;
            border: none;
        }
        .signature-line {
            width: 200px;
            margin: 0 auto;
            border-top: 1px solid #333;
            padding-top: 5px;
            font-size: 11px;
        }
    </style>
</head>
<body>

    @php
        $simboloMoneda = $ajuste->divisa ?? 'Bs.';
    @endphp

    <div class="header">
        <h1>Reporte de Planilla de Sueldos y Salarios</h1>
        <p>Periodo: <strong>{{ $planilla->mes }} de {{ $planilla->anio }}</strong></p>
    </div>

    <!-- Caja de información (Optimizada para Dompdf) -->
    <table class="info-table">
        <tr>
            <td><strong>Estado:</strong> {{ $planilla->estado }}</td>
            <td><strong>Total Empleados:</strong> {{ $planilla->detalles->count() }}</td>
            <td class="text-right"><strong>Monto Total:</strong> {{ number_format($planilla->total_pagado, 2, ',', '.') }} {{ $simboloMoneda }}</td>
        </tr>
    </table>

    <table class="content-table">
        <thead>
            <tr>
                <th style="width: 25px;" class="text-center">N°</th>
                <th>Empleado</th>
                <th>Cédula de Identidad (CI)</th>
                <th>Cargo / Departamento</th>
                <th class="text-right">Salario Base</th>
                <th class="text-right">Bonos</th>
                <th class="text-right">Descuentos</th>
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
                <td colspan="4" class="text-right" style="font-size: 12px;">{{ number_format($planilla->total_pagado, 2, ',', '.') }} {{ $simboloMoneda }}</td>
            </tr>
        </tfoot>
    </table>

    <!-- Firmas institucionales (Optimizadas para Dompdf) -->
    <div class="footer">
        <table class="signature-table">
            <tr>
                <td>
                    <div class="signature-line">Elaborado por (RRHH)</div>
                </td>
                <td>
                    <div class="signature-line">Aprobado por (Gerencia)</div>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
