<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Iniciar Sesión - {{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    
    {{-- FONDO COMPLETO CON IMAGEN --}}
    <div class="min-h-screen flex items-center justify-center relative bg-cover bg-center bg-no-repeat overflow-hidden"
         style="background-image: url('{{ asset('images/fondo.png') }}');">
        
        {{-- Overlay oscuro para contraste --}}
        <div class="absolute inset-0 bg-gradient-to-br from-slate-900/60 via-slate-800/50 to-slate-900/60"></div>

        {{-- Contenido del login --}}
        <div class="relative z-10 w-full max-w-md px-4">
            
            {{-- Logo + título --}}
            <div class="text-center mb-8">
                <div class="mx-auto h-24 w-24 rounded-2xl bg-slate-800/90 backdrop-blur-md flex items-center justify-center shadow-2xl border border-slate-700/50 p-3">
                    <img 
                        src="{{ asset('images/logo.png') }}" 
                        alt="Logo Colbeef" 
                        class="w-full h-full object-contain"
                    >
                </div>
                <h2 class="mt-6 text-3xl font-bold text-white tracking-tight drop-shadow-2xl">
                    Inicia sesión
                </h2>
                <p class="mt-2 text-sm text-gray-200 drop-shadow-lg">
                    Accede al sistema de despachos
                </p>
            </div>

            {{-- Tarjeta de login --}}
            <div class="bg-slate-800/95 backdrop-blur-xl shadow-2xl rounded-2xl px-8 py-8 border border-slate-700/70">
                
                @if (session('status'))
                    <div class="mb-4 p-3 text-sm text-emerald-400 bg-emerald-900/50 border border-emerald-700/50 rounded-lg">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    {{-- Username --}}
<div>
    <label class="block text-sm font-semibold text-slate-200 mb-2" for="username">
        Usuario
    </label>
    <input
        id="username"
        type="text"
        name="username"
        value="{{ old('username') }}"
        required
        autofocus
        autocomplete="username"
        class="block w-full rounded-lg border border-slate-600 bg-slate-900/60 text-slate-100 placeholder-slate-500 text-sm px-4 py-3
               focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition shadow-sm"
        placeholder="admin"
    >
    @error('username')
        <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
    @enderror
</div>
                    {{-- Password --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-200 mb-2" for="password">
                            Contraseña
                        </label>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            class="block w-full rounded-lg border border-slate-600 bg-slate-900/60 text-slate-100 placeholder-slate-500 text-sm px-4 py-3
                                   focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition shadow-sm"
                            placeholder="••••••••"
                        >
                        @error('password')
                            <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Remember + Forgot --}}
                    <div class="flex items-center justify-between text-sm pt-1">
                        <label class="inline-flex items-center gap-2 text-slate-200 cursor-pointer">
                            <input
                                type="checkbox"
                                name="remember"
                                class="h-4 w-4 rounded border-slate-600 bg-slate-900/60 text-cyan-500 focus:ring-cyan-500 focus:ring-offset-slate-800"
                            >
                            <span class="font-medium">Recordarme</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-cyan-400 hover:text-cyan-300 hover:underline font-medium">
                                ¿Olvidaste tu contraseña?
                            </a>
                        @endif
                    </div>

                    {{-- Botón --}}
                    <div class="pt-2">
                        <button
                            type="submit"
                            class="w-full flex justify-center items-center gap-2 rounded-lg bg-gradient-to-r from-cyan-500 to-blue-600 text-white text-sm font-semibold py-3.5
                                   hover:from-cyan-400 hover:to-blue-500 focus:outline-none focus:ring-4 focus:ring-cyan-500/50
                                   transition-all duration-200 shadow-lg shadow-cyan-500/30 hover:shadow-xl"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                            Ingresar
                        </button>
                    </div>
                </form>
            </div>

            {{-- Footer --}}
            <p class="mt-6 text-center text-sm text-slate-300 drop-shadow-lg">
                Sistema de Despachos · {{ date('Y') }}
            </p>
        </div>
    </div>
    
</body>
</html>
