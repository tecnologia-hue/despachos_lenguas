<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Despacho-Lenguas</title>

        <!-- 🔴 FAVICON -->
        <link rel="icon" type="image/png" href="{{ asset('images/ico.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- AlpineJS (necesario para el menú) -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="font-sans antialiased bg-gray-100" x-data="{ sidebarOpen: false }">
        
        <div class="flex h-screen overflow-hidden">
            
            {{-- SIDEBAR (Menú Lateral Fijo) --}}
            <aside class="fixed inset-y-0 left-0 z-50 w-64 bg-gray-900 text-white transition-transform duration-300 transform lg:static lg:translate-x-0"
                   :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
                
                {{-- Logo --}}
                <div class="flex items-center justify-center h-16 bg-gray-800 shadow-md border-b border-gray-700">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                        <img src="{{ asset('images/ico.png') }}" class="w-8 h-8" alt="Logo">
                        <span class="text-xl font-bold bg-gradient-to-r from-blue-400 to-purple-500 bg-clip-text text-transparent">
                            Despachos Lenguas
                        </span>
                    </a>
                </div>

                {{-- Menú de Navegación --}}
                <nav class="mt-5 px-4 space-y-2 overflow-y-auto h-[calc(100vh-140px)]">
                    
                    {{-- Dashboard --}}
                    <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        Dashboard
                    </a>

                    <div class="pt-4 pb-2"><p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Gestión</p></div>
                    
                    {{-- Despachos --}}
                    <a href="{{ route('despachos.index') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('despachos.index') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path></svg>
                        Despachos
                    </a>
                    
                    {{-- Importar --}}
                    <a href="{{ route('despachos.import') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('despachos.import') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        Importar Excel
                    </a>

                    @role('admin')
                    <div class="pt-4 pb-2"><p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Administración</p></div>
                    
                    {{-- Usuarios --}}
                    <a href="{{ route('users.index') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('users.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        Usuarios
                    </a>

                    {{-- Reportes Dropdown --}}
                    <div x-data="{ open: {{ request()->routeIs('reports.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open" class="flex items-center w-full px-4 py-2 text-gray-300 hover:bg-gray-800 rounded-lg transition-colors group justify-between">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                <span>Reportes</span>
                            </div>
                            <svg class="w-4 h-4 transition-transform transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" class="mt-1 space-y-1 pl-11" x-collapse>
                            <a href="{{ route('reports.despachos-por-usuario') }}" class="block px-4 py-2 text-sm text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg {{ request()->routeIs('reports.despachos-por-usuario') ? 'bg-gray-800 text-white' : '' }}">Por Usuario</a>
                            <a href="{{ route('reports.historico-completo') }}" class="block px-4 py-2 text-sm text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg {{ request()->routeIs('reports.historico-completo') ? 'bg-gray-800 text-white' : '' }}">Histórico Completo</a>
                        </div>
                    </div>
                    @endrole
                </nav>

                {{-- Footer Sidebar (Usuario) --}}
                <div class="absolute bottom-0 w-full bg-gray-800 p-4 border-t border-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold text-lg">
                            {{ substr(Auth::user()->first_name, 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-white truncate">{{ Auth::user()->first_name }}</p>
                            <a href="{{ route('profile.edit') }}" class="text-xs text-gray-400 hover:text-blue-300 block truncate">Editar Perfil</a>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-gray-400 hover:text-red-400 p-2 rounded-full hover:bg-gray-700 transition-colors" title="Cerrar Sesión">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            {{-- Overlay Móvil (Fondo oscuro al abrir menú) --}}
            <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-black bg-opacity-50 lg:hidden" style="display: none;"></div>

            {{-- CONTENIDO PRINCIPAL --}}
            <div class="flex-1 flex flex-col overflow-hidden">
                
                {{-- Header Móvil (Solo visible en celular) --}}
                <header class="bg-white shadow-sm lg:hidden h-16 flex items-center px-4 justify-between z-30 sticky top-0">
                    <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-gray-700 focus:outline-none p-2 rounded-md hover:bg-gray-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <span class="text-xl font-bold text-gray-800">Despachos</span>
                    <div class="w-8"></div> {{-- Espaciador --}}
                </header>

                {{-- Page Heading (Original de Laravel) --}}
                @isset($header)
                    <header class="bg-white shadow hidden lg:block">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                {{-- Main Content Scrollable --}}
                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
                    <div class="py-6 px-4 sm:px-6 lg:px-8">
                        {{-- Alertas Globales --}}
                        @if (session('success')) 
                            <div class="max-w-7xl mx-auto bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm flex items-center animate-fade-in-down">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                {{ session('success') }}
                            </div> 
                        @endif
                        
                        @if (session('error')) 
                            <div class="max-w-7xl mx-auto bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm flex items-center animate-fade-in-down">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ session('error') }}
                            </div> 
                        @endif

                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>
