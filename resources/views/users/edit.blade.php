<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Editar Usuario') }}
            </h2>
            <a href="{{ route('users.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- Card: Información del Usuario --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center mb-6">
                        <div class="h-16 w-16 rounded-full bg-blue-100 flex items-center justify-center mr-4">
                            <span class="text-blue-600 font-bold text-2xl">
                                {{ strtoupper(substr($user->first_name, 0, 1)) }}{{ strtoupper(substr($user->last_name, 0, 1)) }}
                            </span>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900">{{ $user->full_name }}</h3>
                            <p class="text-sm text-gray-500">Usuario: <strong>{{ $user->username }}</strong></p>
                            <p class="text-xs text-gray-400 mt-1">Miembro desde {{ $user->created_at->format('d/m/Y') }}</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('users.update', $user) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        {{-- Nombre --}}
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700">
                                Nombre <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="first_name" 
                                id="first_name" 
                                value="{{ old('first_name', $user->first_name) }}"
                                required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('first_name') border-red-500 @enderror"
                            >
                            @error('first_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Apellido --}}
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700">
                                Apellido <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="last_name" 
                                id="last_name" 
                                value="{{ old('last_name', $user->last_name) }}"
                                required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('last_name') border-red-500 @enderror"
                            >
                            @error('last_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">
                                Nuevo usuario: <strong id="username-preview">{{ $user->username }}</strong>
                            </p>
                        </div>

                        {{-- Rol --}}
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700">
                                Rol <span class="text-red-500">*</span>
                            </label>
                            <select 
                                name="role" 
                                id="role" 
                                required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('role') border-red-500 @enderror"
                            >
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" 
                                        {{ (old('role') ?? $user->roles->first()->name ?? '') === $role->name ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Botones --}}
                        <div class="flex items-center justify-end gap-4 pt-4 border-t">
                            <a href="{{ route('users.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Cancelar
                            </a>
                            <button 
                                type="submit"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Card: Restablecer Contraseña --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                        </svg>
                        Restablecer Contraseña
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">Cambia la contraseña del usuario. El usuario deberá usar la nueva contraseña en su próximo inicio de sesión.</p>

                    <form method="POST" action="{{ route('users.reset-password', $user) }}" class="space-y-4">
                        @csrf

                        {{-- Nueva Contraseña --}}
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">
                                Nueva Contraseña <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="password" 
                                name="password" 
                                id="password" 
                                required
                                minlength="6"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500 @error('password') border-red-500 @enderror"
                                placeholder="Mínimo 6 caracteres"
                            >
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Confirmar Contraseña --}}
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                                Confirmar Contraseña <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="password" 
                                name="password_confirmation" 
                                id="password_confirmation" 
                                required
                                minlength="6"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500"
                                placeholder="Repite la contraseña"
                            >
                        </div>

                        {{-- Botón --}}
                        <div class="flex justify-end pt-2">
                            <button 
                                type="submit"
                                onclick="return confirm('¿Estás seguro de cambiar la contraseña de este usuario?')"
                                class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                Restablecer Contraseña
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Card: Zona Peligrosa --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-2 border-red-200">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-red-600 mb-4">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        Zona Peligrosa
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Estado actual: 
                        @if($user->active)
                            <span class="font-semibold text-green-600">Activo</span>
                        @else
                            <span class="font-semibold text-red-600">Inactivo</span>
                        @endif
                    </p>

                    <form method="POST" action="{{ route('users.toggle-status', $user) }}" class="inline">
                        @csrf
                        <button 
                            type="submit"
                            onclick="return confirm('¿Estás seguro de @if($user->active) desactivar @else activar @endif este usuario?')"
                            class="inline-flex items-center px-4 py-2 @if($user->active) bg-red-600 hover:bg-red-700 @else bg-green-600 hover:bg-green-700 @endif border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150">
                            @if($user->active)
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                </svg>
                                Desactivar Usuario
                            @else
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Activar Usuario
                            @endif
                        </button>
                    </form>

                    <p class="text-xs text-gray-500 mt-3">
                        @if($user->active)
                            Al desactivar, el usuario no podrá iniciar sesión.
                        @else
                            Al activar, el usuario podrá iniciar sesión nuevamente.
                        @endif
                    </p>
                </div>
            </div>

        </div>
    </div>

    {{-- Script para preview del username --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const firstNameInput = document.getElementById('first_name');
            const lastNameInput = document.getElementById('last_name');
            const usernamePreview = document.getElementById('username-preview');

            function updateUsernamePreview() {
                const firstName = firstNameInput.value.trim().toLowerCase();
                const lastName = lastNameInput.value.trim().toLowerCase();
                
                if (firstName && lastName) {
                    const removeAccents = (str) => {
                        return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
                    };
                    
                    const username = removeAccents(firstName) + '.' + removeAccents(lastName);
                    usernamePreview.textContent = username;
                } else {
                    usernamePreview.textContent = '{{ $user->username }}';
                }
            }

            firstNameInput.addEventListener('input', updateUsernamePreview);
            lastNameInput.addEventListener('input', updateUsernamePreview);
        });
    </script>
</x-app-layout>
