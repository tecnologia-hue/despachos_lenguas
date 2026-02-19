<?php

namespace App\Http\Controllers;

use App\Imports\DespachoImport;
use App\Models\Despacho;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\ImagenLlavesService;
use Illuminate\Support\Facades\Auth;

class DespachoController extends Controller
{
    /**
     * Listado de despachos.
     */
    public function index(Request $request)
    {
        $query = Despacho::with(['creator', 'usuario'])->latest();

        // LÓGICA DE FILTRADO:
        
        // 1. Si es ADMIN y quiere ver despachos de OTRO (desde reporte)
        if (Auth::user()->hasRole('admin') && $request->filled('created_by')) {
            $query->where('created_by', $request->created_by);
        }
        
        // 2. Si NO es admin, SOLO ve los suyos (propiedad 'usuario_id' o 'created_by')
        elseif (!Auth::user()->hasRole('admin')) {
            $query->where(function($q) {
                $q->where('usuario_id', Auth::id())
                  ->orWhere('created_by', Auth::id());
            });
        }
        
        // 3. Si es ADMIN y NO filtra, ve TODO (comportamiento por defecto)
        // Si prefieres que por defecto solo vea los suyos, descomenta esto:
        /*
        elseif (Auth::user()->hasRole('admin') && !$request->filled('created_by')) {
             $query->where('created_by', Auth::id());
        }
        */

        $despachos = $query->paginate(10);

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
        // PERMITIR VER SI: Es dueño O es Admin
        if ($despacho->usuario_id !== auth()->id() && !Auth::user()->hasRole('admin')) {
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
        // PERMITIR VER SI: Es dueño O es Admin
        if ($despacho->usuario_id !== auth()->id() && !Auth::user()->hasRole('admin')) {
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
        // PERMITIR VER SI: Es dueño O es Admin
        if ($despacho->usuario_id !== auth()->id() && !Auth::user()->hasRole('admin')) {
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
        // PERMITIR VER SI: Es dueño O es Admin
        if ($despacho->usuario_id !== auth()->id() && !Auth::user()->hasRole('admin')) {
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
        // PERMITIR VER SI: Es dueño O es Admin
        if ($despacho->usuario_id !== auth()->id() && !Auth::user()->hasRole('admin')) {
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
