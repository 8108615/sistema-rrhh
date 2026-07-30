<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Planilla General de {{ strtoupper($tipo) }} - Gestión {{ $gestion }}</title>
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
            max-width: 100%;
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
        .titulo-planilla {
            text-align: center;
            font-size: 15px;
            font-weight: bold;
            text-decoration: underline;
            margin: 5px 0 5px 0;
            text-transform: uppercase;
        }
        .subtitulo-planilla {
            text-align: center;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 15px;
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
            text-transform: uppercase;
        }
        .bold {
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
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
            text-align: right;
            margin-bottom: 10px;
            font-size: 10px;
        }
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
            .planilla-container { border: 2px solid #000; }
        }
    </style>
</head>
<body>

    <!-- Botón flotante para activar impresión rápida -->
    <div class="no-print" style="text-align: right; max-width: 100%; margin: 0 auto 15px auto;">
        <button onclick="window.print()" style="padding: 8px 16px; background: #2563eb; color: #fff; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">
            🖨️ Imprimir Planilla General
        </button>
    </div>

    <div class="planilla-container">
        <!-- Cabecera con Logo y Nombre de Empresa -->
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
                    <div style="font-size: 11px; color: #333;">NIT: {{ $ajuste->nit ?? 'N/A' }}</div>
                </td>
            </tr>
        </table>

        <div class="div-fechas">
            <div class="footer-fecha">
                Fecha de Impresión: {{ date('d/m/Y H:i') }}
            </div>
        </div>

        <div class="titulo-planilla">PLANILLA GENERAL DE {{ strtoupper($tipo) }}</div>
        <div class="subtitulo-planilla">GESTIÓN: {{ $gestion }}</div>

        <!-- Tabla de Detalle General -->
        <table class="seccion-tabla">
            <thead>
                <tr>
                    <th style="width: 4%;">N°</th>
                    <th style="width: 24%;">APELLIDOS Y NOMBRES</th>
                    <th style="width: 10%;">C.I.</th>
                    <th style="width: 15%;">ÁREA</th>
                    <th style="width: 10%;">ÚLTIMO SALARIO</th>
                    <th style="width: 10%;">TIEMPO TRAB.</th>
                    <th style="width: 12%;">LÍQUIDO PAGABLE ({{ $simboloMoneda }})</th>
                    <th style="width: 15%;">ESTADO</th>
                </tr>
            </thead>
            <tbody>
                @php $totalGeneral = 0; @endphp
                @forelse ($aguinaldos as $item)
                    @php $totalGeneral += $item->monto_pagar; @endphp
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ strtoupper($item->empleado->apellido ?? '') }} {{ strtoupper($item->empleado->nombre ?? '') }}</td>
                        <td class="text-center">{{ $item->empleado->ci ?? 'N/A' }}</td>
                        <td>{{ $item->empleado->area->nombre ?? 'S/A' }}</td>
                        <td class="text-right">{{ number_format($item->ultimo_salario, 2, '.', ',') }}</td>
                        <td class="text-center">{{ $item->meses_trabajados }}m / {{ $item->dias_trabajados }}d</td>
                        <td class="text-right bold">{{ number_format($item->monto_pagar, 2, '.', ',') }}</td>
                        <td class="text-center">{{ $item->estado }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center" style="padding: 20px;">No existen registros calculados para esta gestión y tipo.</td>
                    </tr>
                @endforelse
            </tbody>
            @if($aguinaldos->count() > 0)
            <tfoot>
                <tr class="bold" style="background-color: #f9f9f9;">
                    <td colspan="6" class="text-right">TOTAL GENERAL:</td>
                    <td class="text-right" style="font-size: 12px;">{{ number_format($totalGeneral, 2, '.', ',') }}</td>
                    <td></td>
                </tr>
            </tfoot>
            @endif
        </table>

        <!-- Firmas -->
        <table class="firmas-container">
            <tr>
                <td>
                    <div class="linea-firma">Elaborado por</div>
                </td>
                <td>
                    <div class="linea-firma">{{ strtoupper($ajuste->nombre ?? 'EMPRESA S.R.L.') }}<br><span style="font-size: 9px; font-weight: normal;">GERENCIA / ADMINISTRACIÓN</span></div>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
