<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Despacho #{{ $despacho->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            color: #000;
        }
        
        .container {
            width: 100%;
            padding: 10px;
        }
        
        /* HEADER */
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        
        .header h1 {
            font-size: 16px;
            margin-bottom: 5px;
        }
        
        .header p {
            font-size: 9px;
        }
        
        /* INFO GRID */
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-cell {
            display: table-cell;
            padding: 5px;
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
            margin-top: 10px;
        }
        
        th {
            background-color: #2c3e50;
            color: white;
            padding: 8px 5px;
            text-align: left;
            font-size: 9px;
            border: 1px solid #000;
        }
        
        td {
            padding: 6px 5px;
            border: 1px solid #ddd;
            font-size: 8px;
        }
        
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        /* FOOTER */
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ccc;
            text-align: center;
            font-size: 8px;
            color: #666;
        }
        
        .totales {
            margin-top: 15px;
            padding: 10px;
            background-color: #e8f4f8;
            border: 1px solid #2c3e50;
        }
        
        .totales p {
            font-size: 11px;
            font-weight: bold;
            margin: 3px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        
        <!-- HEADER -->
        <div class="header">
            <h1>🚛 DESPACHO DE LENGUAS</h1>
            <p>Sistema de Gestión de Despachos - Reporte #{{ $despacho->id }}</p>
            <p>Generado: {{ now()->format('d/m/Y H:i:s') }}</p>
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
        
        <!-- TABLA DE PRODUCTOS - COLUMNAS REDUCIDAS -->
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
                @php
                    $totalLenguas = 0;
                @endphp
                
                @foreach($despacho->productos as $producto)
                    @php
                        $totalLenguas++;
                    @endphp
                    <tr>
                        <td>{{ $producto->codigo_producto }}</td>
                        <td>{{ $producto->descripcion_producto ?? '-' }}</td>
                        <td>{{ $producto->fecha_beneficio ? $producto->fecha_beneficio->format('d/m/Y') : '-' }}</td>
                        <td>{{ $producto->destino_especifico ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <!-- TOTALES SIMPLIFICADO -->
        <div class="totales">
            <p>📦 Total de Lenguas: {{ $despacho->lenguas }}</p>
        </div>
        
        <!-- FOOTER -->
        <div class="footer">
            <p>Documento generado automáticamente por el Sistema de Despachos</p>
            <p>Archivo original: {{ $despacho->archivo_original }}</p>
        </div>
        
    </div>
</body>
</html>
