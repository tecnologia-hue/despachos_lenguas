<?php

namespace App\Services;

use App\Models\Despacho;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImagenLlavesService
{
    protected ImageManager $imageManager;
    
    // Rutas de fuentes
    private string $fuenteRegular;
    private string $fuenteBold;
    
    // Colores exactos del Excel
    private const COLOR_HEADER_VERDE = '#2d7c2e';
    private const COLOR_HEADER_TEXT = '#ffffff';
    private const COLOR_FILA_PAR = '#f0f0f0';
    private const COLOR_FILA_IMPAR = '#ffffff';
    private const COLOR_BORDE = '#000000';
    private const COLOR_TEXTO = '#000000';
    
    // Dimensiones compactas - 🔴 AUMENTADAS
    private const ANCHO_IMAGEN = 1400;
    private const ALTO_INFO = 140; // 🔴 CAMBIADO: de 120 a 140
    private const ALTO_TABLA_HEADER = 60; // 🔴 CAMBIADO: de 50 a 60
    private const ALTO_FILA = 55; // 🔴 CAMBIADO: de 45 a 55
    private const PADDING = 30;
    
    // Anchos de columnas
    private const ANCHO_COL_DESTINO = 200;
    private const ANCHO_COL_DIRECCION = 1140;

    public function __construct()
    {
        $this->imageManager = new ImageManager(new Driver());
        
        // Configurar rutas de fuentes
        $this->fuenteRegular = public_path('storage/fonts/Roboto-Regular.ttf');
        $this->fuenteBold = public_path('storage/fonts/Roboto-Bold.ttf');
        
        // Verificar que existen las fuentes
        if (!file_exists($this->fuenteRegular)) {
            throw new \Exception('Fuente Roboto-Regular.ttf no encontrada en storage/fonts/');
        }
        if (!file_exists($this->fuenteBold)) {
            throw new \Exception('Fuente Roboto-Bold.ttf no encontrada en storage/fonts/');
        }
    }

    /**
     * Genera una imagen PNG con los destinos únicos del despacho
     */
    public function generarImagenLlaves(Despacho $despacho): string
    {
        // Usar ->productos (sin paréntesis) para usar la colección filtrada
        $destinosProcesados = $despacho->productos
            ->pluck('destino_especifico')
            ->filter(function($destino) {
                return !empty($destino);
            })
            ->map(function ($destino) {
                return $this->parsearDestino($destino);
            })
            ->filter()
            ->unique('codigo')
            ->sortBy('codigo')
            ->values();

        if ($destinosProcesados->isEmpty()) {
            throw new \Exception('No hay destinos para generar la imagen de llaves.');
        }

        $altoTotal = self::ALTO_INFO + self::ALTO_TABLA_HEADER + 
                     (count($destinosProcesados) * self::ALTO_FILA) + 
                     (self::PADDING * 2);

        $imagen = $this->imageManager->create(self::ANCHO_IMAGEN, $altoTotal)
            ->fill('#ffffff');

        $this->dibujarInfoBasica($imagen, $despacho, count($destinosProcesados));
        $this->dibujarTabla($imagen, $destinosProcesados);

        $nombreArchivo = 'llaves-despacho-' . $despacho->id . '-' . now()->timestamp . '.png';
        $rutaCompleta = storage_path('app/public/despachos/' . $nombreArchivo);

        if (!file_exists(dirname($rutaCompleta))) {
            mkdir(dirname($rutaCompleta), 0755, true);
        }

        $imagen->toPng()->save($rutaCompleta);

        return 'despachos/' . $nombreArchivo;
    }

    private function parsearDestino(string $destino): ?array
    {
        $partes = array_map('trim', explode('/', $destino));
        
        if (count($partes) > 2) {
            array_shift($partes);
            array_shift($partes);
        }

        if (empty($partes)) {
            return null;
        }

        $codigo = array_shift($partes);
        $direccion = implode(' / ', $partes);

        return [
            'codigo' => strtoupper($codigo),
            'direccion' => $direccion ?: 'N/A'
        ];
    }

    private function dibujarInfoBasica($imagen, Despacho $despacho, int $totalDestinos): void
    {
        $y = self::PADDING;
        $x = self::PADDING;

        // Conductor - 🔴 TAMAÑO AUMENTADO
        $imagen->text('Conductor', $x, $y, function ($font) {
            $font->filename($this->fuenteBold); // 🔴 CAMBIADO a Bold
            $font->size(22); // 🔴 CAMBIADO: de 18 a 22
            $font->color('#000000');
            $font->align('left');
            $font->valign('top');
        });

        $imagen->text($despacho->conductor ?? 'N/A', $x + 150, $y, function ($font) {
            $font->filename($this->fuenteRegular);
            $font->size(22); // 🔴 CAMBIADO: de 18 a 22
            $font->color('#000000');
            $font->align('left');
            $font->valign('top');
        });

        // Vehículo - 🔴 TAMAÑO AUMENTADO
        $y += 38; // 🔴 CAMBIADO: de 30 a 38 (más espacio)
        $imagen->text('Vehículo', $x, $y, function ($font) {
            $font->filename($this->fuenteBold); // 🔴 CAMBIADO a Bold
            $font->size(22); // 🔴 CAMBIADO: de 18 a 22
            $font->color('#000000');
            $font->align('left');
            $font->valign('top');
        });

        $imagen->text($despacho->placa_remolque ?? 'N/A', $x + 150, $y, function ($font) {
            $font->filename($this->fuenteRegular);
            $font->size(22); // 🔴 CAMBIADO: de 18 a 22
            $font->color('#000000');
            $font->align('left');
            $font->valign('top');
        });

        // N° Destinos (derecha) - 🔴 TAMAÑO AUMENTADO
        $xDerecha = self::ANCHO_IMAGEN - self::PADDING - 280; // 🔴 AJUSTADO
        $y = self::PADDING;

        $imagen->text('N° Destinos', $xDerecha, $y, function ($font) {
            $font->filename($this->fuenteBold); // 🔴 CAMBIADO a Bold
            $font->size(22); // 🔴 CAMBIADO: de 18 a 22
            $font->color('#000000');
            $font->align('left');
            $font->valign('top');
        });

        $imagen->text((string)$totalDestinos, $xDerecha + 150, $y, function ($font) {
            $font->filename($this->fuenteBold); // 🔴 CAMBIADO a Bold
            $font->size(22); // 🔴 CAMBIADO: de 18 a 22
            $font->color('#000000');
            $font->align('right');
            $font->valign('top');
        });
    }

    private function dibujarTabla($imagen, $destinos): void
    {
        $yTabla = self::ALTO_INFO + self::PADDING;
        $xInicio = self::PADDING;

        $this->dibujarHeaderTabla($imagen, $xInicio, $yTabla);

        $yFila = $yTabla + self::ALTO_TABLA_HEADER;

        foreach ($destinos as $index => $destino) {
            $this->dibujarFilaTabla($imagen, $xInicio, $yFila, $destino, $index);
            $yFila += self::ALTO_FILA;
        }
    }

    private function dibujarHeaderTabla($imagen, int $x, int $y): void
    {
        $anchoTotal = self::ANCHO_COL_DESTINO + self::ANCHO_COL_DIRECCION;

        $imagen->drawRectangle($x, $y, function ($rectangle) use ($anchoTotal) {
            $rectangle->size($anchoTotal, self::ALTO_TABLA_HEADER);
            $rectangle->background(self::COLOR_HEADER_VERDE);
            $rectangle->border(self::COLOR_BORDE, 2);
        });

        $imagen->drawRectangle($x + self::ANCHO_COL_DESTINO, $y, function ($rectangle) {
            $rectangle->size(2, self::ALTO_TABLA_HEADER);
            $rectangle->background(self::COLOR_BORDE);
        });

        // Header "Destino" - 🔴 TAMAÑO AUMENTADO
        $imagen->text('Destino', $x + (self::ANCHO_COL_DESTINO / 2), $y + (self::ALTO_TABLA_HEADER / 2) - 1, function ($font) {
            $font->filename($this->fuenteBold);
            $font->size(24); // 🔴 CAMBIADO: de 20 a 24
            $font->color(self::COLOR_HEADER_TEXT);
            $font->align('center');
            $font->valign('middle');
        });

        // Header "Dirección" - 🔴 TAMAÑO AUMENTADO
        $imagen->text('Dirección', $x + self::ANCHO_COL_DESTINO + (self::ANCHO_COL_DIRECCION / 2), $y + (self::ALTO_TABLA_HEADER / 2) - 1, function ($font) {
            $font->filename($this->fuenteBold);
            $font->size(24); // 🔴 CAMBIADO: de 20 a 24
            $font->color(self::COLOR_HEADER_TEXT);
            $font->align('center');
            $font->valign('middle');
        });
    }

    private function dibujarFilaTabla($imagen, int $x, int $y, array $destino, int $index): void
    {
        $anchoTotal = self::ANCHO_COL_DESTINO + self::ANCHO_COL_DIRECCION;
        $colorFondo = $index % 2 === 0 ? self::COLOR_FILA_PAR : self::COLOR_FILA_IMPAR;

        $imagen->drawRectangle($x, $y, function ($rectangle) use ($anchoTotal, $colorFondo) {
            $rectangle->size($anchoTotal, self::ALTO_FILA);
            $rectangle->background($colorFondo);
            $rectangle->border(self::COLOR_BORDE, 1);
        });

        $imagen->drawRectangle($x + self::ANCHO_COL_DESTINO, $y, function ($rectangle) {
            $rectangle->size(1, self::ALTO_FILA);
            $rectangle->background(self::COLOR_BORDE);
        });

        // Código de destino - 🔴 TAMAÑO AUMENTADO
        $imagen->text($destino['codigo'], $x + (self::ANCHO_COL_DESTINO / 2), $y + (self::ALTO_FILA / 2) - 1, function ($font) {
            $font->filename($this->fuenteBold); // 🔴 CAMBIADO a Bold para destacar
            $font->size(21); // 🔴 CAMBIADO: de 18 a 21
            $font->color(self::COLOR_TEXTO);
            $font->align('center');
            $font->valign('middle');
        });

        // Dirección - 🔴 TAMAÑO AUMENTADO
        $textoDir = strlen($destino['direccion']) > 85  // 🔴 AJUSTADO: de 90 a 85 por letra más grande
            ? substr($destino['direccion'], 0, 82) . '...' 
            : $destino['direccion'];

        $imagen->text($textoDir, $x + self::ANCHO_COL_DESTINO + 15, $y + (self::ALTO_FILA / 2) - 1, function ($font) {
            $font->filename($this->fuenteRegular);
            $font->size(20); // 🔴 CAMBIADO: de 17 a 20
            $font->color(self::COLOR_TEXTO);
            $font->align('left');
            $font->valign('middle');
        });
    }

    public function limpiarImagenesAntiguas(int $despachoId): void
    {
        $patron = storage_path('app/public/despachos/llaves-despacho-' . $despachoId . '-*.png');
        
        foreach (glob($patron) as $archivo) {
            if (file_exists($archivo)) {
                @unlink($archivo);
            }
        }
    }
}
