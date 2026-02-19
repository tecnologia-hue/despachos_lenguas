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
                'created_by' => $this->usuarioId, // <-- ESTE ES EL CAMBIO
            ]);

            $contadorLenguas = 0;

            // ✅ PROCESAR TODOS LOS PRODUCTOS (no solo -1001)
            for ($i = 14; $i < $rows->count(); $i++) {
                $row = $rows[$i];

                if (empty($row[0])) continue;

                $codigoCompleto = trim($row[0] ?? '');
                if (empty($codigoCompleto)) continue;

                // Extraer solo el código (sin descripción)
                $partes = explode(' ', $codigoCompleto, 2);
                $codigo = $partes[0] ?? $codigoCompleto;

                // Validar formato: XXXX-XXXXX-XXXX
                if (!preg_match('/^\d{4}-\d{5}-\d{4}$/', $codigo)) {
                    continue;
                }

                // Obtener código base: 2601-11413-1001 -> 2601-11413
                $codigoBase = $this->obtenerCodigoBase($codigo);

                // ✅ DETERMINAR SI ES LENGUA (-1001) O COLA (-1002)
                $esLengua = str_ends_with($codigo, '-1001');
                
                if ($esLengua) {
                    // Convertir -1001 a -6000 (solo lenguas)
                    $codigoFinal = $codigoBase . '-6000';
                    $descripcion = 'LENGUA';
                    $contadorLenguas++;
                } else {
                    // Mantener código original para -1002 y otros
                    $codigoFinal = $codigo;
                    $descripcion = str_ends_with($codigo, '-1002') ? 'COLA' : 'PRODUCTO';
                }

                // Parsear valores
                $decomisos = trim($row[4] ?? '');
                $destinoEspecifico = trim($row[5] ?? '');
                $fechaBeneficio = $this->parseFecha($row[6] ?? null);

                // ✅ GUARDAR TODOS LOS PRODUCTOS (lenguas Y colas)
                DespachoProducto::create([
                    'despacho_id' => $despacho->id,
                    'codigo_producto' => $codigoFinal,
                    'descripcion_producto' => $descripcion,
                    'peso_frio' => 0,
                    'peso_caliente' => 0,
                    'temperatura' => null,
                    'decomisos' => $decomisos,
                    'destino_especifico' => $destinoEspecifico,
                    'fecha_beneficio' => $fechaBeneficio,
                ]);
            }

            // Actualizar cantidad de lenguas (solo las que vienen de -1001)
            $despacho->update(['lenguas' => $contadorLenguas]);

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