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
        try {
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
                'lenguas' => 0,
                'archivo_original' => request()->file('excel_file')->getClientOriginalName(),
                'usuario_id' => $this->usuarioId,
            ]);

            // Almacenar productos agrupados por código base
            $lenguasAgrupadas = [];

            // Procesar productos (a partir de la fila 14)
            for ($i = 14; $i < $rows->count(); $i++) {
                $row = $rows[$i];

                if (empty($row[0])) continue;

                $codigoCompleto = trim($row[0] ?? '');
                if (empty($codigoCompleto)) continue;

                // Extraer solo el código (sin descripción)
                $partes = explode(' ', $codigoCompleto, 2);
                $codigo = $partes[0] ?? $codigoCompleto;

                // Obtener código base: 2601-11413-1001 -> 2601-11413
                $codigoBase = $this->obtenerCodigoBase($codigo);

                // Convertir a código lengua: 2601-11413 -> 2601-11413-6000
                $codigoLengua = $codigoBase . '-6000';

                // Parsear valores
                $decomisos = trim($row[4] ?? '');
                $destinoEspecifico = trim($row[5] ?? '');
                $fechaBeneficio = $this->parseFecha($row[6] ?? null);

                // Agrupar por código base para evitar duplicados
                if (!isset($lenguasAgrupadas[$codigoBase])) {
                    $lenguasAgrupadas[$codigoBase] = [
                        'codigo' => $codigoLengua,
                        'destino' => $destinoEspecifico,
                        'fecha' => $fechaBeneficio,
                        'decomisos' => $decomisos,
                    ];
                }
            }

            // Guardar las lenguas (sin duplicados)
            foreach ($lenguasAgrupadas as $lengua) {
                DespachoProducto::create([
                    'despacho_id' => $despacho->id,
                    'codigo_producto' => $lengua['codigo'],
                    'descripcion_producto' => 'LENGUA',
                    'peso_frio' => 0,
                    'peso_caliente' => 0,
                    'temperatura' => null,
                    'decomisos' => $lengua['decomisos'],
                    'destino_especifico' => $lengua['destino'],
                    'fecha_beneficio' => $lengua['fecha'],
                ]);
            }

            // Actualizar cantidad de lenguas (1 por animal)
            $despacho->update(['lenguas' => count($lenguasAgrupadas)]);

        } catch (\Exception $e) {
            throw new \Exception('Error al procesar el archivo: ' . $e->getMessage());
        }
    }

    /**
     * Obtener código base del animal (sin sufijo)
     * Ejemplo: 2601-11413-1001 -> 2601-11413
     */
    private function obtenerCodigoBase($codigo)
    {
        // Dividir por guiones
        $partes = explode('-', $codigo);

        // Si tiene 3 partes, tomar las primeras 2
        if (count($partes) >= 3) {
            return $partes[0] . '-' . $partes[1];
        }

        return $codigo;
    }

    /**
     * Parsear un valor decimal de forma segura
     */
    private function parseDecimal($valor)
    {
        if ($valor === null || $valor === '') {
            return null;
        }

        $valor = (string)$valor;
        $valor = str_replace(',', '.', $valor);
        $valor = trim($valor);
        $resultado = floatval($valor);

        if ($resultado == 0 && !in_array($valor, ['0', '0.0', '0,0'])) {
            return null;
        }

        return $resultado;
    }

    /**
     * Parsear fechas en múltiples formatos
     */
    private function parseFecha($fecha)
    {
        if (empty($fecha)) {
            return null;
        }

        try {
            if (is_numeric($fecha)) {
                return Carbon::instance(
                    \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($fecha)
                );
            }

            if (is_string($fecha)) {
                $formatos = [
                    'd/m/Y H:i:s',
                    'd/m/Y H:i',
                    'd/m/Y',
                    'Y-m-d H:i:s',
                    'Y-m-d',
                    'd-m-Y',
                ];

                foreach ($formatos as $formato) {
                    try {
                        return Carbon::createFromFormat($formato, trim($fecha));
                    } catch (\Exception $e) {
                        continue;
                    }
                }

                return Carbon::parse($fecha);
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }
}
