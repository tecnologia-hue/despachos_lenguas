<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Despacho #{{ $despacho->id }}</title>
    <style>
        @page {
            size: letter;
            margin: 0.75in;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            width: 100%;
            max-width: 8.5in;
            margin: 0 auto;
            font-family: Arial, sans-serif;
            font-size: 9px;
            color: #000;
        }

        .container {
            width: 100%;
            max-width: 7in;
            margin: 0 auto;
            padding: 15px;
        }
        
        /* HEADER */
        .header {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
            margin-top: 10px;
        }
        
        .logo-container {
            display: table-cell;
            vertical-align: middle;
            width: 80px;
            padding-right: 15px;
        }
        
        .logo-container img {
            width: 70px;
            height: auto;
        }
        
        .header-content {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
        }
        
        .header h1 {
            font-size: 16px;
            margin-bottom: 8px;
        }
        
        .header p {
            font-size: 9px;
            margin-bottom: 3px;
        }
        
        /* INFO GRID */
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            margin-top: 10px;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-cell {
            display: table-cell;
            padding: 8px;
            border: 1px solid #ccc;
            vertical-align: middle;
        }
        
        .info-label {
            font-weight: bold;
            background-color: #f0f0f0;
            width: 30%;
        }
        
        .info-value {
            width: 70%;
        }
        
        /* TABLE */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            margin-bottom: 15px;
        }
        
        /* HEADER TABLA - COLOR VERDE INSTITUCIONAL */
        th {
            background-color: #7ce8ad;
            color: #000;
            padding: 10px 8px;
            text-align: left;
            font-size: 9px;
            border: 1px solid #000;
            font-weight: bold;
        }
        
        td {
            padding: 8px 8px;
            border: 1px solid #ddd;
            font-size: 8px;
        }
        
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        /* TOTALES - COLOR ROSA INSTITUCIONAL */
        .totales {
            margin-top: 20px;
            margin-bottom: 20px;
            padding: 12px;
            background-color: #f9dff8;
            border: 2px solid #f9dff8;
            border-radius: 4px;
        }
        
        .totales p {
            font-size: 11px;
            font-weight: bold;
            margin: 5px 0;
            color: #000;
        }
        
        /* FIRMAS */
        .firma-section {
            margin-top: 40px;
            padding-top: 25px;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ccc;
            text-align: center;
            font-size: 8px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        
        <!-- HEADER CON LOGO -->
        <div class="header">
            <div class="logo-container">
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/logo.png'))) }}" alt="Logo">
            </div>
            <div class="header-content">
                <h1> DESPACHO DE LENGUAS</h1>
            </div>
        </div>
        
        <!-- INFORMACIÓN GENERAL -->
        <div class="info-grid">
            <div class="info-row">
                <div class="info-cell info-label">Conductor:</div>
                <div class="info-cell info-value">{{ $despacho->conductor }}</div>
            </div>
            <div class="info-row">
                <div class="info-cell info-label">Placa / Remolque:</div>
                <div class="info-cell info-value">{{ $despacho->placa_remolque }}</div>
            </div>
            <div class="info-row">
                <div class="info-cell info-label">Destino General:</div>
                <div class="info-cell info-value">{{ $despacho->destino_general }}</div>
            </div>
            <div class="info-row">
                <div class="info-cell info-label">Fecha Expedición:</div>
                <div class="info-cell info-value">
                    {{ $despacho->fecha_expedicion ? $despacho->fecha_expedicion->format('d/m/Y H:i') : 'N/A' }}
                </div>
            </div>
            <div class="info-row">
                <div class="info-cell info-label">Total Lenguas:</div>
                <div class="info-cell info-value">{{ $despacho->lenguas }}</div>
            </div>
        </div>
        <!-- NOTA INFORMATIVA -->
<div style="margin-bottom: 15px; padding: 10px; background-color: #f0f8ff; border-left: 4px solid #7ce8ad;">
    <p style="font-size: 9px; line-height: 1.4; margin: 0;">
        <strong>Nota:</strong> Los productos relacionados a continuación, se despachan a conformidad, aptos para consumo humano, no presentan cambios en sus características organolépticas.
    </p>
</div>
        <!-- TABLA DE PRODUCTOS - SOLO LENGUAS -->
        <table>
            <thead>
                <tr>
                    <th style="width: 15%;">Código</th>
                    <th style="width: 15%;">Descripción</th>
                    <th style="width: 15%;">Fecha Beneficio</th>
                    <th style="width: 55%;">Destino</th>
                </tr>
            </thead>
            <tbody>
                @foreach($despacho->productos as $producto)
                    @if(str_ends_with($producto->codigo_producto, '-6000'))
                        @php
                            $destino = $producto->destino_especifico ?? '';
                            if ($destino !== '') {
                                $partes = explode('/', $destino);
                                if (count($partes) >= 3) {
                                    $destinoFormateado = trim(implode('/', array_slice($partes, 2)));
                                } else {
                                    $destinoFormateado = trim($destino);
                                }
                            } else {
                                $destinoFormateado = '-';
                            }
                        @endphp
                        <tr>
                            <td>{{ $producto->codigo_producto }}</td>
                            <td>{{ $producto->descripcion_producto ?? '-' }}</td>
                            <td>{{ $producto->fecha_beneficio ? $producto->fecha_beneficio->format('d/m/Y') : '-' }}</td>
                            <td>{{ $destinoFormateado }}</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
        <!-- FIRMAS -->
        <div class="firma-section">
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="width: 50%; text-align: center; padding: 50px 15px 15px 15px; border: none;">
                        <div style="border-top: 1px solid #000; padding-top: 8px;">
                            <strong>Entrega</strong>
                        </div>
                    </td>
                    <td style="width: 50%; text-align: center; padding: 50px 15px 15px 15px; border: none;">
                        <div style="border-top: 1px solid #000; padding-top: 8px;">
                            <strong>Recibe</strong>
                        </div>
                    </td>
                </tr>
            </table>
        </div>        
    </div>
</body>
</html>
