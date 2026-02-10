<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Crear Usuario') }}
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
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form method="POST" action="{{ route('users.store') }}" class="space-y-6">
                        @csrf

                        {{-- Nombre --}}
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700">
                                Nombre <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="first_name" 
                                id="first_name" 
                                value="{{ old('first_name') }}"
                                required
                                autofocus
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('first_name') border-red-500 @enderror"
                                placeholder="Juan"
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
                                value="{{ old('last_name') }}"
                                required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('last_name') border-red-500 @enderror"
                                placeholder="Mendoza"
                            >
                            @error('last_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">
                                El sistema generará automáticamente el usuario: <strong id="username-preview">nombre.apellido</strong>
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
                                <option value="">Seleccionar rol...</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ old('role') === $role->name ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">
                                <strong>Admin:</strong> Acceso total | <strong>Operador:</strong> Crear y ver despachos | <strong>Visualizador:</strong> Solo ver
                            </p>
                        </div>

                        {{-- Contraseña --}}
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">
                                Contraseña <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="password" 
                                name="password" 
                                id="password" 
                                required
                                minlength="6"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('password') border-red-500 @enderror"
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
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Repite la contraseña"
                            >
                        </div>

                        {{-- Botones --}}
                        <div class="flex items-center justify-end gap-4 pt-4 border-t">
                            <a href="{{ route('users.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                Cancelar
                            </a>
                            <button 
                                type="submit"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Crear Usuario
                            </button>
                        </div>
                    </form>

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
                    // Remover tildes
                    const removeAccents = (str) => {
                        return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
                    };
                    
                    const username = removeAccents(firstName) + '.' + removeAccents(lastName);
                    usernamePreview.textContent = username;
                } else if (firstName) {
                    usernamePreview.textContent = firstName + '.apellido';
                } else {
                    usernamePreview.textContent = 'nombre.apellido';
                }
            }

            firstNameInput.addEventListener('input', updateUsernamePreview);
            lastNameInput.addEventListener('input', updateUsernamePreview);
        });
    </script>
</x-app-layout>
