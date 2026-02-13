<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalle del Despacho #') }}{{ $despacho->id }}
            </h2>
            <div class="flex gap-2">
                {{-- Botones COMPLETOS (originales) --}}
                <a 
                    href="{{ route('despachos.pdf', $despacho->id) }}"
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition duration-200 shadow-md"
                    target="_blank"
                >
                    📄 Descargar PDF
                </a>
                <a 
                    href="{{ route('despachos.llaves', $despacho->id) }}"
                    class="px-4 py-2 bg-pink-500 hover:bg-pink-600 text-white font-semibold rounded-lg transition duration-200 shadow-md"
                    target="_blank"
                >
                    🔑 Generar Llaves
                </a>

                {{-- Botones PERSONALIZADOS (nuevos) --}}
                <button 
                    type="button"
                    id="btnPdfPersonalizado"
                    class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg transition duration-200 shadow-md disabled:bg-gray-400 disabled:cursor-not-allowed"
                    disabled
                    title="Selecciona productos en la tabla para habilitar"
                >
                    📄 PDF Adicionales
                </button>
                <button 
                    type="button"
                    id="btnLlavesPersonalizadas"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition duration-200 shadow-md disabled:bg-gray-400 disabled:cursor-not-allowed"
                    disabled
                    title="Selecciona productos en la tabla para habilitar"
                >
                    🔑 Llaves Adicionales
                </button>

                {{-- Botón Volver --}}
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

            <!-- Banner informativo sobre exportación personalizada -->
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <p class="text-sm text-blue-800">
                            <strong>💡 Tip:</strong> Selecciona productos específicos en la tabla usando los checkboxes para habilitar los botones de <strong>PDF Personalizado</strong> y <strong>Llaves Personalizadas</strong>.
                        </p>
                        <p class="text-xs text-blue-600 mt-1">
                            <span id="contadorSeleccionados" class="font-semibold">0 productos seleccionados</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Productos del Despacho con Selección -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4 border-b pb-2">
                        <h3 class="text-lg font-semibold">📦 Productos / Lenguas</h3>
                        <span class="text-sm bg-green-100 text-green-800 px-3 py-1 rounded-full font-semibold">
                            Solo Lenguas (-6000)
                        </span>
                    </div>
                    
                    @php
                        // Filtrar solo lenguas (productos con código terminado en -6000)
                        $lenguas = $despacho->productos->filter(function($producto) {
                            return str_ends_with($producto->codigo_producto, '-6000');
                        });
                    @endphp

                    @if($lenguas->count() > 0)
                        <!-- Controles de selección -->
                        <div class="mb-4 flex items-center justify-between bg-gray-50 p-4 rounded-lg">
                            <div class="flex items-center gap-3">
                                <button 
                                    type="button" 
                                    id="btnSeleccionarTodos"
                                    class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold rounded-lg transition"
                                >
                                    ✓ Seleccionar Todos
                                </button>
                                <button 
                                    type="button" 
                                    id="btnDeseleccionarTodos"
                                    class="px-4 py-2 bg-gray-400 hover:bg-gray-500 text-white text-sm font-semibold rounded-lg transition"
                                >
                                    ✗ Deseleccionar Todos
                                </button>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-green-50">
                                    <tr>
                                        <th class="px-4 py-3 text-center w-12">
                                            <input 
                                                type="checkbox" 
                                                id="checkboxMaestro"
                                                class="w-4 h-4 text-green-600 rounded focus:ring-green-500"
                                            >
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase">Código</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase">Descripción</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase">Destino</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase">Fecha Beneficio</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase">Peso (Kg)</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($lenguas as $producto)
                                        <tr class="hover:bg-green-50 transition-colors">
                                            <td class="px-4 py-3 text-center">
                                                <input 
                                                    type="checkbox" 
                                                    name="productos_seleccionados[]" 
                                                    value="{{ $producto->id }}"
                                                    class="checkbox-producto w-4 h-4 text-green-600 rounded focus:ring-green-500"
                                                >
                                            </td>
                                            <td class="px-4 py-3 text-sm font-mono font-semibold text-green-700">{{ $producto->codigo_producto }}</td>
                                            <td class="px-4 py-3 text-sm">{{ $producto->descripcion_producto }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-600">
                                                {{ \Illuminate\Support\Str::limit($producto->destino_especifico, 50) }}
                                            </td>
                                            <td class="px-4 py-3 text-sm">
                                                {{ $producto->fecha_beneficio ? $producto->fecha_beneficio->format('d/m/Y') : '-' }}
                                            </td>
                                            <td class="px-4 py-3 text-sm">
                                                <span class="text-gray-600">Frío:</span> <span class="font-semibold">{{ $producto->peso_frio }}</span> <br>
                                                <span class="text-gray-600">Caliente:</span> <span class="font-semibold">{{ $producto->peso_caliente }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4 flex justify-between items-center">
                            <div class="text-sm text-gray-600">
                                <strong>Total de lenguas:</strong> <span class="text-lg font-bold text-green-600">{{ $lenguas->count() }}</span>
                            </div>
                            <div class="text-xs text-gray-500">
                                (Se filtran solo productos con código terminado en -6000)
                            </div>
                        </div>

                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            <p class="mt-2 text-sm text-gray-600">No hay lenguas en este despacho.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    {{-- 🔴 SCRIPT MOVIDO AQUÍ (fuera de @push) --}}
    <script>
        (function() {
            console.log('🔍 Script cargado - Iniciando...');
            
            function inicializarSeleccion() {
                const checkboxes = document.querySelectorAll('.checkbox-producto');
                const checkboxMaestro = document.getElementById('checkboxMaestro');
                const btnSeleccionarTodos = document.getElementById('btnSeleccionarTodos');
                const btnDeseleccionarTodos = document.getElementById('btnDeseleccionarTodos');
                const contadorSeleccionados = document.getElementById('contadorSeleccionados');
                const btnPdfPersonalizado = document.getElementById('btnPdfPersonalizado');
                const btnLlavesPersonalizadas = document.getElementById('btnLlavesPersonalizadas');

                console.log('📦 Checkboxes encontrados:', checkboxes.length);
                console.log('🟪 Botón PDF:', btnPdfPersonalizado);
                console.log('🟦 Botón Llaves:', btnLlavesPersonalizadas);

                if (checkboxes.length === 0) {
                    console.error('❌ No se encontraron checkboxes');
                    return;
                }

                // Actualizar contador y botones
                function actualizarEstado() {
                    const seleccionados = Array.from(checkboxes).filter(cb => cb.checked);
                    const cantidad = seleccionados.length;
                    
                    console.log('✅ Productos seleccionados:', cantidad);
                    
                    if (contadorSeleccionados) {
                        contadorSeleccionados.textContent = `${cantidad} producto${cantidad !== 1 ? 's' : ''} seleccionado${cantidad !== 1 ? 's' : ''}`;
                    }
                    
                    // Habilitar/deshabilitar botones
                    const haySeleccion = cantidad > 0;
                    
                    if (btnPdfPersonalizado) {
                        btnPdfPersonalizado.disabled = !haySeleccion;
                        console.log('🟪 PDF habilitado:', !btnPdfPersonalizado.disabled);
                    }
                    
                    if (btnLlavesPersonalizadas) {
                        btnLlavesPersonalizadas.disabled = !haySeleccion;
                        console.log('🟦 Llaves habilitadas:', !btnLlavesPersonalizadas.disabled);
                    }
                    
                    // Actualizar checkbox maestro
                    if (checkboxMaestro) {
                        if (cantidad === 0) {
                            checkboxMaestro.checked = false;
                            checkboxMaestro.indeterminate = false;
                        } else if (cantidad === checkboxes.length) {
                            checkboxMaestro.checked = true;
                            checkboxMaestro.indeterminate = false;
                        } else {
                            checkboxMaestro.checked = false;
                            checkboxMaestro.indeterminate = true;
                        }
                    }
                }

                // Checkbox maestro
                if (checkboxMaestro) {
                    checkboxMaestro.addEventListener('change', function() {
                        console.log('🎯 Checkbox maestro clickeado');
                        checkboxes.forEach(cb => cb.checked = this.checked);
                        actualizarEstado();
                    });
                }

                // Checkboxes individuales
                checkboxes.forEach((cb, index) => {
                    cb.addEventListener('change', function() {
                        console.log(`🔲 Checkbox ${index} cambiado a:`, this.checked);
                        actualizarEstado();
                    });
                });

                // Botón seleccionar todos
                if (btnSeleccionarTodos) {
                    btnSeleccionarTodos.addEventListener('click', function() {
                        console.log('✅ Seleccionar todos');
                        checkboxes.forEach(cb => cb.checked = true);
                        actualizarEstado();
                    });
                }

                // Botón deseleccionar todos
                if (btnDeseleccionarTodos) {
                    btnDeseleccionarTodos.addEventListener('click', function() {
                        console.log('❌ Deseleccionar todos');
                        checkboxes.forEach(cb => cb.checked = false);
                        actualizarEstado();
                    });
                }

                // Generar PDF personalizado
                if (btnPdfPersonalizado) {
                    btnPdfPersonalizado.addEventListener('click', function() {
                        console.log('🟪 Click en PDF Personalizado');
                        
                        const seleccionados = Array.from(checkboxes)
                            .filter(cb => cb.checked)
                            .map(cb => cb.value);
                        
                        console.log('📋 IDs seleccionados:', seleccionados);
                        
                        if (seleccionados.length === 0) {
                            alert('Por favor selecciona al menos un producto');
                            return;
                        }

                        const url = "{{ route('despachos.pdf.personalizado', $despacho->id) }}" + '?productos=' + seleccionados.join(',');
                        console.log('🔗 URL generada:', url);
                        window.open(url, '_blank');
                    });
                }

                // Generar llaves personalizadas
                if (btnLlavesPersonalizadas) {
                    btnLlavesPersonalizadas.addEventListener('click', function() {
                        console.log('🟦 Click en Llaves Personalizadas');
                        
                        const seleccionados = Array.from(checkboxes)
                            .filter(cb => cb.checked)
                            .map(cb => cb.value);
                        
                        console.log('🔑 IDs seleccionados:', seleccionados);
                        
                        if (seleccionados.length === 0) {
                            alert('Por favor selecciona al menos un producto');
                            return;
                        }

                        const url = "{{ route('despachos.llaves.personalizadas', $despacho->id) }}" + '?productos=' + seleccionados.join(',');
                        console.log('🔗 URL generada:', url);
                        window.open(url, '_blank');
                    });
                }

                // Estado inicial
                console.log('🚀 Ejecutando estado inicial...');
                actualizarEstado();
                console.log('✅ Script completamente cargado');
            }

            // Ejecutar cuando el DOM esté listo
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', inicializarSeleccion);
            } else {
                inicializarSeleccion();
            }
        })();
    </script>
</x-app-layout>
