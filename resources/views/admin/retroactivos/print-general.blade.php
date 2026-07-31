<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Planilla General de Retroactivos - Gestión {{ $gestion }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 15px;
        }
        .planilla-container {
            max-width: 1000px;
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
            max-height: 50px;
            max-width: 110px;
            object-fit: contain;
        }
        .titulo-planilla {
            text-align: center;
            font-size: 15px;
            font-weight: bold;
            text-decoration: underline;
            margin: 5px 0 15px 0;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .info-table td {
            padding: 3px 5px;
            font-size: 11px;
        }
        .info-table td.label {
            font-weight: bold;
            width: 110px;
        }
        .seccion-tabla {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            border: 1px solid #000;
        }
        .seccion-tabla th, .seccion-tabla td {
            border: 1px solid #000;
            padding: 5px 6px;
            font-size: 10px;
            vertical-align: middle;
        }
        .seccion-tabla th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .bold {
            font-weight: bold;
        }
        .firmas-container {
            width: 100%;
            margin-top: 45px;
            border-collapse: collapse;
        }
        .firmas-container td {
            width: 50%;
            text-align: center;
            vertical-align: bottom;
            height: 55px;
            padding: 0 40px;
        }
        .linea-firma {
            border-top: 1px solid #000;
            padding-top: 5px;
            font-size: 11px;
            font-weight: bold;
        }
        .footer-fecha {
            text-align: right;
            margin-top: 15px;
            font-size: 10px;
        }
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
            .planilla-container { border: 2px solid #000; max-width: 100%; }
            @page { size: landscape; margin: 1cm; }
        }
    </style>
</head>
<body>

    <!-- Botón flotante para activar impresión rápida -->
    <div class="no-print" style="text-align: right; max-width: 1000px; margin: 0 auto 15px auto;">
        <a href="{{ route('admin.retroactivos.index') }}" style="padding: 7px 14px; background: #4b5563; color: #fff; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 11px; margin-right: 5px;">
            ⬅ Volver
        </a>
        <button onclick="window.print()" style="padding: 7px 14px; background: #2563eb; color: #fff; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; font-size: 11px;">
            🖨️ Imprimir Planilla General
        </button>
    </div>

    <div class="planilla-container">
        <!-- Cabecera con Logo y Empresa -->
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

        <div class="titulo-planilla">PLANILLA GENERAL DE PAGOS RETROACTIVOS</div>

        <!-- Datos Generales de la Planilla -->
        <table class="info-table" style="border-bottom: 1px solid #000; padding-bottom: 5px;">
            <tr>
                <td class="label">GESTIÓN (AÑO):</td>
                <td class="bold">{{ $gestion }}</td>
                <td class="label" style="text-align: right;">MONEDA:</td>
                <td style="width: 80px;">{{ $simboloMoneda ?? 'Bs.' }}</td>
            </tr>
            <tr>
                <td class="label">TOTAL REGISTROS:</td>
                <td>{{ isset($retroactivos) ? count($retroactivos) : 0 }} empleado(s)</td>
                <td class="label" style="text-align: right;">FECHA EMISIÓN:</td>
                <td>{{ date('d/m/Y') }}</td>
            </tr>
        </table>

        <!-- Tabla Detallada General -->
        <table class="seccion-tabla">
            <thead>
                <tr>
                    <th style="width: 30px;">#</th>
                    <th>APELLIDOS Y NOMBRES</th>
                    <th style="width: 75px;">C.I.</th>
                    <th>CARGO / ÁREA</th>
                    <th style="width: 75px; text-align: right;">SUELDO ANT.</th>
                    <th style="width: 55px; text-align: center;">% INC.</th>
                    <th style="width: 75px; text-align: right;">SUELDO NUEVO</th>
                    <th style="width: 75px; text-align: right;">DIF. MENSUAL</th>
                    <th style="width: 45px; text-align: center;">MESES</th>
                    <th style="width: 85px; text-align: right;">TOTAL RETROACTIVO</th>
                </tr>
            </thead>
            <tbody>
                @php 
                    $totalGralSueldoAnt = 0;
                    $totalGralSueldoNuevo = 0;
                    $totalGralDifMensual = 0;
                    $totalGralPagar = 0;
                @endphp

                @forelse($retroactivos as $index => $item)
                    @php
                        $totalGralSueldoAnt += $item->sueldo_anterior;
                        $totalGralSueldoNuevo += $item->sueldo_nuevo;
                        $totalGralDifMensual += $item->diferencia_mensual;
                        $totalGralPagar += $item->monto_pagar;
                    @endphp
                    <tr>
                        <td class="text-center text-gray-500">{{ $index + 1 }}</td>
                        <td class="bold">{{ strtoupper(($item->empleado->nombre ?? '') . ' ' . ($item->empleado->apellido ?? '')) }}</td>
                        <td>{{ $item->empleado->ci ?? 'N/A' }}</td>
                        <td>{{ $item->empleado->area->nombre ?? ($item->empleado->cargo ?? 'No asignada') }}</td>
                        <td class="text-right">{{ number_format($item->sueldo_anterior, 2, '.', ',') }}</td>
                        <td class="text-center">{{ number_format($item->porcentaje, 2, '.', ',') }}%</td>
                        <td class="text-right">{{ number_format($item->sueldo_nuevo, 2, '.', ',') }}</td>
                        <td class="text-right">{{ number_format($item->diferencia_mensual, 2, '.', ',') }}</td>
                        <td class="text-center">{{ $item->meses_aplicados }}</td>
                        <td class="text-right bold">{{ number_format($item->monto_pagar, 2, '.', ',') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center" style="padding: 15px; font-style: italic;">
                            No se encontraron registros de retroactivos para la gestión {{ $gestion }}.
                        </td>
                    </tr>
                @endforelse
            </tbody>

            @if(isset($retroactivos) && count($retroactivos) > 0)
                <tfoot>
                    <tr class="bold" style="background-color: #f9f9f9;">
                        <td colspan="4" class="text-right" style="padding-right: 10px;">TOTALES GENERALES:</td>
                        <td class="text-right">{{ number_format($totalGralSueldoAnt, 2, '.', ',') }}</td>
                        <td></td>
                        <td class="text-right">{{ number_format($totalGralSueldoNuevo, 2, '.', ',') }}</td>
                        <td class="text-right">{{ number_format($totalGralDifMensual, 2, '.', ',') }}</td>
                        <td></td>
                        <td class="text-right" style="font-size: 11px;">{{ number_format($totalGralPagar, 2, '.', ',') }}</td>
                    </tr>
                </tfoot>
            @endif
        </table>

        <!-- Fecha de impresión inferior -->
        <div class="footer-fecha">
            Santa Cruz, {{ \Carbon\Carbon::now()->locale('es')->translatedFormat('d \d\e F \d\e Y') }}
        </div>

        <!-- Firmas -->
        <table class="firmas-container">
            <tr>
                <td>
                    <div class="linea-firma">Elaborado por (Contabilidad / RRHH)</div>
                </td>
                <td>
                    <div class="linea-firma">{{ strtoupper($ajuste->nombre ?? 'EMPRESA S.R.L.') }}<br><span style="font-size: 9px; font-weight: normal;">GERENCIA / ADMINISTRACIÓN</span></div>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>