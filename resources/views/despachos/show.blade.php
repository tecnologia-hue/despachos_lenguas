<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalle del Despacho #') }}{{ $despacho->id }}
            </h2>
            <div class="flex gap-2">
                <a 
                    href="{{ route('despachos.pdf', $despacho->id) }}"
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition duration-200 shadow-md"
                    target="_blank"
                >
                    📄 Descargar PDF
                </a>
                <a 
                    href="{{ route('despachos.index') }}"
                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-lg transition duration-200"
                >
                    ← Volver al Listado
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Información General -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4 border-b pb-2">📋 Información del Despacho</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Conductor:</p>
                            <p class="font-semibold">{{ $despacho->conductor }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Placa / Remolque:</p>
                            <p class="font-semibold">{{ $despacho->placa_remolque }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Lenguas:</p>
                            <p class="font-semibold">{{ $despacho->lenguas }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Destino General:</p>
                            <p class="font-semibold">{{ $despacho->destino_general }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Fecha de Expedición:</p>
                            <p class="font-semibold">
                                {{ $despacho->fecha_expedicion ? $despacho->fecha_expedicion->format('d/m/Y H:i:s') : '-' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Archivo Original:</p>
                            <p class="font-semibold">{{ $despacho->archivo_original }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Productos del Despacho -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4 border-b pb-2">📦 Productos / Lenguas</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Código</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Descripción</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Destino</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha Beneficio</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Peso (Kg)</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($despacho->productos as $producto)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-sm font-mono">{{ $producto->codigo_producto }}</td>
                                        <td class="px-4 py-3 text-sm">{{ $producto->descripcion_producto }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">
                                            {{ \Illuminate\Support\Str::limit($producto->destino_especifico, 50) }}
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            {{ $producto->fecha_beneficio ? $producto->fecha_beneficio->format('d/m/Y') : '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            <span class="text-gray-600">Frío:</span> {{ $producto->peso_frio }} <br>
                                            <span class="text-gray-600">Caliente:</span> {{ $producto->peso_caliente }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 text-sm text-gray-600">
                        <strong>Total de productos:</strong> {{ $despacho->productos->count() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
