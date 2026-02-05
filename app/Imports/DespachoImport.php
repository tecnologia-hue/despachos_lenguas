<?php

namespace App\Imports;

use App\Models\Despacho;
use App\Models\DespachoProducto;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class DespachoImport implements ToCollection
{
    protected $usuarioId;

    public function __construct($usuarioId)
    {
        $this->usuarioId = $usuarioId;
    }

    public function collection(Collection $rows)
    {
        // Extraer datos de la cabecera del Excel
        $fechaExpedicion = $this->parseFecha($rows[5][2] ?? null);
        $placaRemolque = $rows[5][5] ?? 'SXT 135';
        $conductor = $rows[7][2] ?? 'Sin conductor';
        $destinoGeneral = $rows[9][2] ?? 'TEMP1';

        // Crear el despacho (cabecera)
        $despacho = Despacho::create([
            'conductor' => $conductor,
            'placa_remolque' => $placaRemolque,
            'destino_general' => $destinoGeneral,
            'fecha_expedicion' => $fechaExpedicion,
            'lenguas' => 0, // Se calculará después
            'archivo_original' => request()->file('excel_file')->getClientOriginalName(),
            'usuario_id' => $this->usuarioId,
        ]);

        $contadorLenguas = 0;

        // Procesar productos (a partir de la fila 14)
        for ($i = 14; $i < $rows->count(); $i++) {
            $row = $rows[$i];

            // Verificar que tenga datos
            if (empty($row[0])) continue;

            $codigoCompleto = $row[0] ?? '';
            $pesoFrio = $row[1] ?? 0;
            $pesoCaliente = $row[2] ?? 0;
            $temperatura = $row[3] ?? null;
            $decomisos = $row[4] ?? '';
            $destinoEspecifico = $row[5] ?? '';
            $fechaBeneficio = $this->parseFecha($row[6] ?? null);

            // Extraer código y descripción
            $partes = explode(' ', trim($codigoCompleto), 2);
            $codigoProducto = $partes[0] ?? $codigoCompleto;
            $descripcionProducto = $partes[1] ?? '';

            // Crear producto del despacho
            DespachoProducto::create([
                'despacho_id' => $despacho->id,
                'codigo_producto' => $codigoProducto,
                'descripcion_producto' => $descripcionProducto,
                'peso_frio' => $pesoFrio,
                'peso_caliente' => $pesoCaliente,
                'temperatura' => $temperatura,
                'decomisos' => $decomisos,
                'destino_especifico' => $destinoEspecifico,
                'fecha_beneficio' => $fechaBeneficio,
            ]);

            $contadorLenguas++;
        }

        // Actualizar cantidad de lenguas
        $despacho->update(['lenguas' => $contadorLenguas]);
    }

    private function parseFecha($fecha)
    {
        if (empty($fecha)) return null;

        try {
            return Carbon::parse($fecha);
        } catch (\Exception $e) {
            return null;
        }
    }
}
