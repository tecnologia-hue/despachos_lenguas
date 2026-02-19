<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Mensaje de bienvenida --}}
            <div class="bg-gradient-to-r from-blue-500 to-blue-700 rounded-lg shadow-lg p-6 mb-6 text-white">
                <h3 class="text-2xl font-bold mb-2">¡Bienvenido, {{ Auth::user()->first_name }}!</h3>
                <p class="text-blue-100">Sistema de Gestión de Despachos de Lenguas</p>
            </div>

            {{-- Tarjetas de acceso rápido --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                {{-- Card: Despachos --}}
                <a href="{{ route('despachos.index') }}" class="group">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-shadow duration-300">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex-shrink-0 bg-blue-100 rounded-lg p-3 group-hover:bg-blue-200 transition-colors">
                                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <svg class="w-6 h-6 text-gray-400 group-hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Despachos</h3>
                            <p class="text-gray-600 text-sm">Ver, crear e importar despachos de lenguas</p>
                        </div>
                    </div>
                </a>

                {{-- Card: Importar Despacho --}}
                <a href="{{ route('despachos.import') }}" class="group">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-shadow duration-300">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex-shrink-0 bg-green-100 rounded-lg p-3 group-hover:bg-green-200 transition-colors">
                                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                </div>
                                <svg class="w-6 h-6 text-gray-400 group-hover:text-green-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Importar Despacho</h3>
                            <p class="text-gray-600 text-sm">Cargar nuevo despacho desde Excel</p>
                        </div>
                    </div>
                </a>

                {{-- Card: Usuarios (Solo Admin) --}}
                @role('admin')
                <a href="{{ route('users.index') }}" class="group">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-shadow duration-300">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex-shrink-0 bg-purple-100 rounded-lg p-3 group-hover:bg-purple-200 transition-colors">
                                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                </div>
                                <svg class="w-6 h-6 text-gray-400 group-hover:text-purple-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Usuarios</h3>
                            <p class="text-gray-600 text-sm">Gestionar usuarios y permisos</p>
                        </div>
                    </div>
                </a>
                @endrole

                {{-- Card: Mi Perfil --}}
                <a href="{{ route('profile.edit') }}" class="group">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-shadow duration-300">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex-shrink-0 bg-gray-100 rounded-lg p-3 group-hover:bg-gray-200 transition-colors">
                                    <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <svg class="w-6 h-6 text-gray-400 group-hover:text-gray-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Mi Perfil</h3>
                            <p class="text-gray-600 text-sm">Ver y editar mi información</p>
                        </div>
                    </div>
                </a>

                {{-- SOLO ADMIN: Reportes --}}
                @role('admin')
                {{-- Card: Reporte Despachos por Usuario --}}
                <a href="{{ route('reports.despachos-por-usuario') }}" class="group">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-shadow duration-300 border-l-4 border-blue-500">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex-shrink-0 bg-blue-50 rounded-lg p-3 group-hover:bg-blue-100 transition-colors">
                                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3v18h18M7 13l3 3 7-7"/>
                                    </svg>
                                </div>
                                <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-3 py-1 rounded-full">
                                    Reporte
                                </span>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Despachos por Usuario</h3>
                            <p class="text-gray-600 text-sm">Ver cuántos despachos ha creado cada usuario.</p>
                        </div>
                    </div>
                </a>

                {{-- Card: Histórico Completo --}}
                <a href="{{ route('reports.historico-completo') }}" class="group">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-shadow duration-300 border-l-4 border-green-500">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex-shrink-0 bg-green-50 rounded-lg p-3 group-hover:bg-green-100 transition-colors">
                                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <span class="text-xs font-semibold text-green-600 bg-green-50 px-3 py-1 rounded-full">
                                    Histórico
                                </span>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Histórico de Despachos</h3>
                            <p class="text-gray-600 text-sm">Ver todos los despachos registrados en el sistema.</p>
                        </div>
                    </div>
                </a>
                @endrole

            </div>
        </div>
    </div>
</x-app-layout>
