<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Imports\DespachoImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Despacho;

class DespachoController extends Controller
{
    // Muestra el formulario de importación
    public function showImport()
    {
        return view('despachos.import');
    }

    // Procesa el Excel subido
    public function importExcel(Request $request)
    {
        // Validar que sea Excel
        $request->validate([
            'excel_file' => 'required|file|mimes:xls,xlsx|max:2048'
        ]);

        try {
            $file = $request->file('excel_file');
            
            // Importar el Excel
            Excel::import(new DespachoImport(auth()->id()), $file);
            
            return redirect()->route('despachos.index')
                ->with('success', '✅ Despacho importado exitosamente');
                
        } catch (\Exception $e) {
            Log::error('Error al importar Excel: ' . $e->getMessage());
            
            return back()->with('error', '❌ Error al procesar el archivo: ' . $e->getMessage());
        }
    }

    // Lista todos los despachos
    public function index()
    {
        $despachos = Despacho::with('usuario')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('despachos.index', compact('despachos'));
    }

    // Muestra el detalle de un despacho
    public function show($id)
    {
        $despacho = Despacho::with('productos')->findOrFail($id);
        
        return view('despachos.show', compact('despacho'));
    }
}
