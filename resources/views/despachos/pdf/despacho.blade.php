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
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 13px;
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
            width: 100px;
            padding-right: 15px;
        }
        
        .logo-container img {
            width: 90px;
            height: auto;
        }
        
        .header-content {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
        }
        
        .header h1 {
            font-size: 22px;
            margin-bottom: 8px;
            font-weight: bold;
        }
        
        .header p {
            font-size: 13px;
            margin-bottom: 3px;
        }
        
        /* TABLA DE INFORMACIÓN */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            margin-top: 10px;
        }
        
        .info-table tr {
            border: 1px solid #000;
        }
        
        .info-table td {
            padding: 4px;
            border-right: 1px solid #000;
            vertical-align: middle;
            font-size: 13px;
        }
        
        .info-table td:last-child {
            border-right: none;
        }
        
        .info-table .info-label {
            font-weight: bold;
            background-color: #7ce8ad;
            color: #000;
        }
        
        /* WIDTHS AJUSTADOS */
        .info-table td:nth-child(1) { width: 22%; }  /* Conductor: */
        .info-table td:nth-child(2) { width: 40%; }  /* Valor conductor */
        .info-table td:nth-child(3) { width: 25%; }  /* Placa / Remolque: */
        .info-table td:nth-child(4) { width: 13%; }  /* Valor placa */

        /* TABLA DE NOTA */
        .nota-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .nota-table td {
            padding: 5px;
            border: 1px solid #000;
            font-size: 11px;
            line-height: 1.4;
            background-color: #f0f8ff;
        }
        
        /* TABLA DE PRODUCTOS */
        .table-productos {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 10px;
        }

        .table-productos thead {
            background-color: #7ce8ad;
        }

        .table-productos th {
            padding: 4px 4px;
            border: 1px solid #000;
            font-weight: bold;
            text-align: left;
            font-size: 11px;
            color: #000;
        }

        .table-productos td {
            padding: 4px 4px;
            border: 1px solid #999;
        }

        .table-productos tbody tr:nth-child(even) {
            background-color: #f5f5f5;
        }

       /* FIRMAS */
.firma-section {
    margin-top: 50px;
    display: table;
    width: 100%;
    page-break-inside: avoid;
}

.firma-cell {
    display: table-cell;
    width: 50%;
    text-align: center;
    padding: 10px 15px 0 15px; /* 🔴 REDUCIDO: de 50px a 10px */
}

.firma-line {
    width: 60%; /* 🔴 NUEVO: Ancho de la línea */
    border-top: 1px solid #000; /* 🔴 NUEVO: Línea superior */
    margin: 0 auto 5px auto; /* 🔴 NUEVO: Centrado y espacio debajo */
    padding-top: 40px; /* 🔴 NUEVO: Espacio antes de la línea */
}

.firma-label {
    font-weight: bold;
    font-size: 12px;
    margin-top: 5px;
}

    </style>
</head>
<body>
    <div class="container">
        
        <!-- HEADER -->
        <div class="header">
            <div class="logo-container">
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/logo.png'))) }}" alt="Logo Colbeef">
            </div>
            <div class="header-content">
                <h1>DESPACHO DE LENGUAS</h1>
            </div>
        </div>

        <!-- INFORMACIÓN GENERAL -->
        <table class="info-table">
            <tr>
                <td class="info-label">Conductor:</td>
                <td class="info-value">{{ $despacho->conductor ?? 'N/A' }}</td>
                <td class="info-label">Placa / Remolque:</td>
                <td class="info-value">{{ $despacho->placa_remolque ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="info-label">Fecha Expedición:</td>
                <td class="info-value">{{ $despacho->fecha_expedicion ? $despacho->fecha_expedicion->format('d/m/Y H:i') : 'N/A' }}</td>
                <td class="info-label">Total Lenguas:</td>
                <td class="info-value">{{ $despacho->lenguas ?? 'N/A' }}</td>
            </tr>
        </table>

        <!-- NOTA -->
        <table class="nota-table">
            <tr>
                <td>
                    <strong>Nota:</strong> Los productos relacionados a continuación, se despachan a conformidad, aptos para consumo humano, no presentan cambios en sus características organolépticas.
                </td>
            </tr>
        </table>

        <!-- TABLA DE PRODUCTOS -->
        <table class="table-productos">
            <thead>
                <tr>
                    <th style="width: 16%;">Código</th>
                    <th style="width: 12%;">Descripción</th>
                    <th style="width: 14%;">Fecha Beneficio</th>
                    <th style="width: 58%;">Destino</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $productosLenguas = collect($despacho->productos)
                        ->filter(function($producto) {
                            return str_ends_with($producto->codigo_producto, '-6000');
                        })
                        ->sortBy('codigo_producto')
                        ->values();
                @endphp
                
                @foreach($productosLenguas as $producto)
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
                @endforeach
            </tbody>
        </table>

        <!-- FIRMAS -->
<div class="firma-section">
    <div class="firma-cell">
        <div class="firma-line"></div>
        <div class="firma-label">Entrega</div>
    </div>
    <div class="firma-cell">
        <div class="firma-line"></div>
        <div class="firma-label">Recibe</div>
    </div>
</div>

    </div>
</body>
</html>
