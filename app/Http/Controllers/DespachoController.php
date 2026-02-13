<?php

namespace App\Http\Controllers;

use App\Imports\DespachoImport;
use App\Models\Despacho;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\ImagenLlavesService;

class DespachoController extends Controller
{
    /**
     * Listado de despachos.
     */
    public function index()
    {
        $despachos = Despacho::where('usuario_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('despachos.index', compact('despachos'));
    }

    /**
     * Formulario para importar un despacho desde Excel.
     */
    public function import()
    {
        return view('despachos.import');
    }

    /**
     * Procesar la importación del archivo Excel.
     */
    public function store(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xls,xlsx|max:2048',
        ]);

        try {
            Excel::import(new DespachoImport(auth()->id()), $request->file('excel_file'));

            return redirect()
                ->route('despachos.index')
                ->with('success', 'Despacho importado correctamente.');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Error al procesar el archivo: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar detalle de un despacho.
     */
    public function show(Despacho $despacho)
    {
        if ($despacho->usuario_id !== auth()->id()) {
            abort(403, 'No tienes permiso para ver este despacho');
        }

        $despacho->load('productos');

        return view('despachos.show', compact('despacho'));
    }

    /**
     * Generar PDF del despacho COMPLETO.
     */
    public function generatePDF(Despacho $despacho)
    {
        if ($despacho->usuario_id !== auth()->id()) {
            abort(403, 'No tienes permiso para ver este despacho');
        }

        $despacho->load('productos');

        $pdf = Pdf::loadView('despachos.pdf.despacho', compact('despacho'))
            ->setPaper('a4', 'landscape')
            ->setOption('defaultFont', 'Roboto');

        return $pdf->download('despacho-' . $despacho->id . '.pdf');
    }

    /**
     * Generar PDF PERSONALIZADO con productos seleccionados.
     */
    public function generatePDFPersonalizado(Despacho $despacho, Request $request)
    {
        // Verificar autorización
        if ($despacho->usuario_id !== auth()->id()) {
            abort(403, 'No tienes permiso para ver este despacho');
        }

        // Obtener IDs de productos seleccionados desde la query string
        $productosSeleccionados = $request->query('productos');
        
        if (empty($productosSeleccionados)) {
            return back()->with('error', 'Debes seleccionar al menos un producto.');
        }

        // Convertir string separado por comas a array
        $productosIds = explode(',', $productosSeleccionados);

        // Cargar el despacho con solo los productos seleccionados
        $despacho->load(['productos' => function ($query) use ($productosIds) {
            $query->whereIn('id', $productosIds);
        }]);

        // Validar que haya productos
        if ($despacho->productos->isEmpty()) {
            return back()->with('error', 'No se encontraron productos seleccionados.');
        }

        // Generar PDF con los productos filtrados
        $pdf = Pdf::loadView('despachos.pdf.despacho', compact('despacho'))
            ->setPaper('a4', 'landscape')
            ->setOption('defaultFont', 'Roboto');

        return $pdf->download('despacho-' . $despacho->id . '-personalizado.pdf');
    }

    /**
     * Generar imagen PNG con llaves COMPLETAS (todos los destinos únicos).
     */
    public function generateImagenLlaves(Despacho $despacho, ImagenLlavesService $imagenService)
    {
        // Verificar autorización
        if ($despacho->usuario_id !== auth()->id()) {
            abort(403, 'No tienes permiso para generar llaves de este despacho');
        }

        try {
            // Cargar relación de productos
            $despacho->load('productos');

            // Limpiar imágenes anteriores
            $imagenService->limpiarImagenesAntiguas($despacho->id);

            // Generar nueva imagen
            $rutaImagen = $imagenService->generarImagenLlaves($despacho);

            // Descargar la imagen
            return response()->download(
                storage_path('app/public/' . $rutaImagen),
                'llaves-despacho-' . $despacho->id . '.png'
            )->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return back()->with('error', 'Error al generar imagen: ' . $e->getMessage());
        }
    }

    /**
     * Generar imagen PNG con llaves PERSONALIZADAS (destinos de productos seleccionados).
     */
    public function generateImagenLlavesPersonalizadas(Despacho $despacho, ImagenLlavesService $imagenService, Request $request)
    {
        // Verificar autorización
        if ($despacho->usuario_id !== auth()->id()) {
            abort(403, 'No tienes permiso para generar llaves de este despacho');
        }

        // Obtener IDs de productos seleccionados
        $productosSeleccionados = $request->query('productos');
        
        if (empty($productosSeleccionados)) {
            return back()->with('error', 'Debes seleccionar al menos un producto.');
        }

        // Convertir string a array
        $productosIds = explode(',', $productosSeleccionados);

        try {
            // Cargar solo los productos seleccionados
            $despacho->load(['productos' => function ($query) use ($productosIds) {
                $query->whereIn('id', $productosIds);
            }]);

            // Validar que haya productos
            if ($despacho->productos->isEmpty()) {
                return back()->with('error', 'No se encontraron productos seleccionados.');
            }

            // Limpiar imágenes anteriores
            $imagenService->limpiarImagenesAntiguas($despacho->id);

            // Generar nueva imagen con productos filtrados
            $rutaImagen = $imagenService->generarImagenLlaves($despacho);

            // Descargar la imagen
            return response()->download(
                storage_path('app/public/' . $rutaImagen),
                'llaves-despacho-' . $despacho->id . '-personalizado.png'
            )->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return back()->with('error', 'Error al generar imagen: ' . $e->getMessage());
        }
    }
}
