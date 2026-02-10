<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Mostrar lista de usuarios (solo admin)
     */
    public function index()
    {
        $users = User::with('roles')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('users.index', compact('users'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    /**
     * Guardar nuevo usuario
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|exists:roles,name',
        ], [
            'first_name.required' => 'El nombre es obligatorio',
            'last_name.required' => 'El apellido es obligatorio',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'role.required' => 'Debe seleccionar un rol',
        ]);

        // Generar username automáticamente
        $username = User::generateUsername($request->first_name, $request->last_name);

        $user = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $username,
            'password' => Hash::make($request->password),
            'active' => true,
        ]);

        $user->assignRole($request->role);

        return redirect()
            ->route('users.index')
            ->with('success', "Usuario creado correctamente. Username: {$username}");
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(User $user)
    {
        // No permitir editar al propio admin
        if ($user->id === auth()->id()) {
            return redirect()
                ->route('users.index')
                ->with('warning', 'No puedes editar tu propio usuario desde aquí');
        }

        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Actualizar usuario
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'role' => 'required|exists:roles,name',
        ]);

        // Regenerar username si cambió nombre o apellido
        $username = User::generateUsername($request->first_name, $request->last_name);

        $user->update([
            'name' => $request->first_name . ' ' . $request->last_name,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $username,
        ]);

        $user->syncRoles([$request->role]);

        return redirect()
            ->route('users.index')
            ->with('success', 'Usuario actualizado correctamente');
    }

    /**
     * Cambiar contraseña
     */
    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed',
        ], [
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()
            ->route('users.index')
            ->with('success', 'Contraseña restablecida correctamente');
    }

    /**
     * Activar/Desactivar usuario
     */
    public function toggleStatus(User $user)
    {
        // No permitir desactivarse a sí mismo
        if ($user->id === auth()->id()) {
            return redirect()
                ->route('users.index')
                ->with('error', 'No puedes desactivar tu propio usuario');
        }

        $user->update([
            'active' => !$user->active,
        ]);

        $status = $user->active ? 'activado' : 'desactivado';

        return redirect()
            ->route('users.index')
            ->with('success', "Usuario {$status} correctamente");
    }
}
