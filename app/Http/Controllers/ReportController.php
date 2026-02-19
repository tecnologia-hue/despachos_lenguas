<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Despacho;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Mostrar despachos por usuario (SOLO ADMIN)
     */
    public function despachosPorUsuario(Request $request): View
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Acceso denegado. Solo administradores.');
        }

        $usuarios = User::query()
            ->where('active', true)
            ->withCount('despachosCreados as total_despachos')
            ->orderByDesc('total_despachos')
            ->get()
            ->filter(fn($user) => $user->total_despachos > 0);

        $totalGeneral = $usuarios->sum('total_despachos');

        return view('reports.despachos-por-usuario', compact('usuarios', 'totalGeneral'));
    }

    /**
     * ← NUEVO: Histórico COMPLETO de TODOS los despachos
     */
    public function historicoCompleto(Request $request): View
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Acceso denegado. Solo administradores.');
        }

        $query = Despacho::with(['creator:name,first_name,last_name,username', 'usuario'])
            ->latest('created_at');

        // Filtros opcionales
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('conductor', 'like', '%'.$request->search.'%')
                  ->orWhere('destino_general', 'like', '%'.$request->search.'%');
            });
        }

        $despachos = $query->paginate(25);

        return view('reports.historico-completo', compact('despachos'));
    }
}
