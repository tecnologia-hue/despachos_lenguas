<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>

    <!-- 🔴 FAVICON -->
    <link rel="icon" type="image/png" href="{{ asset('images/ico.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gradient-to-br from-blue-50 via-white to-purple-50 min-h-screen">
    
    <!-- Header con navegación -->
    <header class="absolute top-0 left-0 right-0 z-10">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex justify-between items-center">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-12 w-auto">
                    
                </div>

                <!-- Botones de autenticación -->
                @if (Route::has('login'))
                    <div class="flex items-center space-x-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" 
                               class="px-6 py-2.5 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" 
                               class="px-6 py-2.5 text-gray-700 font-semibold hover:text-blue-600 transition-colors duration-200">
                                Iniciar Sesión
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" 
                                   class="px-6 py-2.5 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                    Registrarse
                                </a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <main class="relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-32 pb-20">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                
                <!-- Contenido izquierdo -->
                <div class="space-y-8">
                    <div class="space-y-4">
                        <h1 class="text-5xl lg:text-6xl font-bold text-gray-900 leading-tight">
                            Sistema de Gestión de 
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">
                                Despachos
                            </span>
                        </h1>
                        <p class="text-xl text-gray-600 leading-relaxed">
                            Plataforma integral para la gestión eficiente de despachos de lenguas. 
                            Importa, organiza y administra toda tu información en un solo lugar.
                        </p>
                    </div>

                    <!-- Features -->
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mt-1">
                                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">Importación masiva desde Excel</h3>
                                <p class="text-gray-600">Carga múltiples despachos en segundos</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mt-1">
                                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">Gestión de usuarios y roles</h3>
                                <p class="text-gray-600">Control de acceso basado en permisos</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mt-1">
                                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">Exportación a PDF y Excel</h3>
                                <p class="text-gray-600">Genera reportes profesionales al instante</p>
                            </div>
                        </div>
                    </div>

                    <!-- CTA Buttons -->
                    @guest
                        <div class="flex flex-col sm:flex-row gap-4 pt-4">
                            <a href="{{ route('login') }}" 
                               class="inline-flex items-center justify-center px-8 py-4 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                </svg>
                                Iniciar Sesión
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" 
                                   class="inline-flex items-center justify-center px-8 py-4 bg-white text-blue-600 font-semibold rounded-lg border-2 border-blue-600 hover:bg-blue-50 transition-all duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                    </svg>
                                    Crear Cuenta
                                </a>
                            @endif
                        </div>
                    @endguest
                </div>

                <!-- Imagen/Ilustración derecha -->
                <div class="relative hidden lg:block">
                    <div class="relative">
                        <!-- Card decorativa 1 -->
                        <div class="absolute top-0 right-0 w-72 bg-white rounded-2xl shadow-2xl p-6 transform rotate-3 hover:rotate-0 transition-transform duration-300">
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900">Despachos</h3>
                                    <p class="text-sm text-gray-500">Gestión completa</p>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <div class="h-2 bg-blue-100 rounded-full"></div>
                                <div class="h-2 bg-blue-100 rounded-full w-4/5"></div>
                                <div class="h-2 bg-blue-100 rounded-full w-3/5"></div>
                            </div>
                        </div>

                        <!-- Card decorativa 2 -->
                        <div class="absolute top-32 -right-8 w-64 bg-white rounded-2xl shadow-2xl p-6 transform -rotate-3 hover:rotate-0 transition-transform duration-300">
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900">Usuarios</h3>
                                    <p class="text-sm text-gray-500">Control de acceso</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="w-8 h-8 bg-purple-200 rounded-full"></div>
                                <div class="w-8 h-8 bg-purple-200 rounded-full"></div>
                                <div class="w-8 h-8 bg-purple-200 rounded-full"></div>
                                <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center text-xs font-semibold text-gray-600">+5</div>
                            </div>
                        </div>

                        <!-- Card decorativa 3 -->
                        <div class="absolute top-64 right-12 w-56 bg-gradient-to-br from-green-400 to-blue-500 rounded-2xl shadow-2xl p-6 text-white transform rotate-6 hover:rotate-0 transition-transform duration-300">
                            <div class="text-4xl font-bold mb-2">98%</div>
                            <p class="text-sm opacity-90">Eficiencia en procesamiento</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Stats Section -->
        <div class="bg-white border-t border-gray-100 py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                    <div class="space-y-2">
                        <div class="text-4xl font-bold text-blue-600">100%</div>
                        <div class="text-gray-600 font-medium">Seguro y Confiable</div>
                    </div>
                    <div class="space-y-2">
                        <div class="text-4xl font-bold text-purple-600">24/7</div>
                        <div class="text-gray-600 font-medium">Disponibilidad</div>
                    </div>
                    <div class="space-y-2">
                        <div class="text-4xl font-bold text-green-600">Rápido</div>
                        <div class="text-gray-600 font-medium">Procesamiento Eficiente</div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-50 border-t border-gray-200 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center text-gray-600">
                <p>&copy; {{ date('Y') }} Colbeef. Todos los derechos reservados.</p>
                <p class="text-sm mt-2">Sistema de Gestión de Despachos de Lenguas</p>
            </div>
        </div>
    </footer>

</body>
</html>
