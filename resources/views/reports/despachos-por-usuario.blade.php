<x-app-layout>
    @section('title', '📊 Despachos por Usuario')

    <div class="p-8 max-w-7xl mx-auto">
        {{-- Header --}}
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                    📊 Reporte de Despachos
                </h1>
                <p class="text-xl text-gray-600 mt-2">Actividad por usuario</p>
            </div>
            <a href="{{ route('dashboard') }}" class="bg-gray-800 text-white px-6 py-3 rounded-xl hover:bg-gray-900 transition-all">
                ← Dashboard
            </a>
        </div>

        {{-- Total General --}}
        <div class="grid md:grid-cols-3 gap-6 mb-8">
            <div class="bg-gradient-to-br from-green-400 to-green-600 text-white p-8 rounded-2xl shadow-2xl">
                <div class="text-4xl font-bold mb-2">{{ $totalGeneral }}</div>
                <div class="text-lg opacity-90">Total Despachos</div>
            </div>
            <div class="bg-gradient-to-br from-blue-400 to-blue-600 text-white p-8 rounded-2xl shadow-2xl">
                <div class="text-4xl font-bold mb-2">{{ $usuarios->count() }}</div>
                <div class="text-lg opacity-90">Usuarios Activos</div>
            </div>
            <div class="bg-gradient-to-br from-purple-400 to-purple-600 text-white p-8 rounded-2xl shadow-2xl">
                <div class="text-4xl font-bold mb-2">
                    {{ $usuarios->count() ? number_format($usuarios->first()->total_despachos / $usuarios->count(), 1) : 0 }}
                </div>
                <div class="text-lg opacity-90">Promedio por Usuario</div>
            </div>
        </div>

        {{-- Tabla --}}
        <div class="bg-white shadow-2xl rounded-3xl overflow-hidden">
            <div class="px-8 py-6 bg-gradient-to-r from-gray-50 to-gray-100 border-b">
                <h2 class="text-2xl font-bold text-gray-800">👥 Usuarios por Productividad</h2>
            </div>
            
            @if($usuarios->count())
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-gray-100 to-gray-200">
                        <tr>
                            <th class="px-8 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Usuario</th>
                            <th class="px-8 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">Despachos</th>
                            <th class="px-8 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">%</th>
                            <th class="px-8 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($usuarios as $index => $usuario)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-8 py-6">
                                <div class="flex items-center space-x-4">
                                    <div class="w-14 h-14 bg-gradient-to-br from-{{ $index % 2 ? 'blue' : 'purple' }}-400 to-{{ $index % 2 ? 'purple' : 'blue' }}-500 rounded-2xl flex items-center justify-center shadow-lg">
                                        <span class="text-2xl font-bold text-white">{{ strtoupper(substr($usuario->name, 0, 1)) }}</span>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-lg text-gray-900">{{ $usuario->name }}</div>
                                        {{-- CORREGIDO: Sin arroba --}}
                                        <div class="text-sm text-gray-500">{{ $usuario->username }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="text-3xl font-bold text-blue-600">{{ $usuario->total_despachos }}</div>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="text-lg font-semibold text-green-600">
                                    {{ number_format(($usuario->total_despachos / max(1, $totalGeneral)) * 100, 1) }}%
                                </div>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <a href="{{ route('despachos.index') }}?created_by={{ $usuario->id }}" 
                                   class="bg-blue-600 text-white px-6 py-2 rounded-xl hover:bg-blue-700 transition-all font-medium">
                                    Ver Despachos
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-20">
                <div class="text-6xl mb-4">📭</div>
                <h3 class="text-2xl font-bold text-gray-600 mb-2">Sin datos</h3>
                <p class="text-gray-500 mb-4">No hay despachos registrados aún</p>
                <a href="{{ route('despachos.index') }}" class="bg-blue-600 text-white px-6 py-2 rounded-xl hover:bg-blue-700">
                    Crear Primer Despacho
                </a>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
