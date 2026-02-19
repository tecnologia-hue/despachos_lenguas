<x-app-layout>
    <x-slot name="title">📋 Histórico Completo</x-slot>

    <div class="p-6 max-w-7xl mx-auto space-y-6">
        {{-- Header --}}
        <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4 mb-8">
            <div>
                <h1 class="text-3xl lg:text-4xl font-bold bg-gradient-to-r from-orange-500 to-red-500 bg-clip-text text-transparent">
                    📋 Histórico Completo
                </h1>
                <p class="text-lg text-gray-600">Todos los despachos registrados ({{ $despachos->total() }})</p>
            </div>
            <a href="{{ route('reports.despachos-por-usuario') }}" 
               class="bg-blue-600 text-white px-6 py-3 rounded-xl hover:bg-blue-700 font-medium whitespace-nowrap">
                ← Por Usuario
            </a>
        </div>

        <div class="bg-white shadow-2xl rounded-3xl overflow-hidden">
            {{-- Header tabla --}}
            <div class="px-6 py-4 bg-gradient-to-r from-orange-50 to-red-50 border-b">
                <h2 class="text-xl font-bold text-gray-800">📜 Todos los Registros</h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider w-16">ID</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Creador</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider min-w-[120px]">Conductor</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider min-w-[200px]">Destino</th>
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-700 uppercase tracking-wider w-20">Lenguas</th>
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-700 uppercase tracking-wider w-32">Fecha</th>
                            <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider w-28">Detalle</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($despachos as $despacho)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-4 text-center">
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-bold">
                                    {{ $despacho->id }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <div class="font-semibold text-gray-900">
                                    {{ $despacho->creator?->name ?? 'Sin asignar' }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $despacho->creator?->username ?? '' }}
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="font-medium text-gray-900">
                                    {{ Str::limit($despacho->conductor, 25) }}
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="font-medium text-gray-900 truncate max-w-[180px]">
                                    {{ Str::limit($despacho->destino_general, 35) }}
                                </div>
                                <div class="text-sm text-gray-500 truncate">
                                    {{ $despacho->placa_remolque }}
                                </div>
                            </td>
                            <td class="px-4 py-4 text-right">
                                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-bold">
                                    {{ $despacho->lenguas }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-right">
                                <div class="text-sm font-semibold text-gray-900">
                                    {{ $despacho->created_at->format('d/m/y') }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $despacho->created_at->format('H:i') }}
                                </div>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <a href="{{ route('despachos.show', $despacho) }}" 
                                   class="inline-flex items-center justify-center gap-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all shadow-md hover:shadow-lg w-full max-w-[100px] mx-auto block">
                                    Ver Detalle
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-8 py-16 text-center">
                                <div class="text-6xl mb-6 opacity-25 mx-auto">📦</div>
                                <h3 class="text-2xl font-bold text-gray-600 mb-2">Sin despachos registrados</h3>
                                <p class="text-gray-500 mb-6">El histórico aparecerá aquí cuando crees despachos</p>
                                <a href="{{ route('despachos.index') }}" class="bg-blue-600 text-white px-8 py-3 rounded-xl hover:bg-blue-700 font-semibold inline-block">
                                    ➕ Crear Primer Despacho
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($despachos->hasPages())
            <div class="px-6 py-6 bg-gray-50 border-t">
                {{ $despachos->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
