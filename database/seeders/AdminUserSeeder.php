<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuario Admin
        $admin = User::create([
            'name' => 'Administrador',
            'email' => 'admin@despachos.com',
            'password' => Hash::make('password123'), // Cambiar esta contraseña
        ]);

        // Asignar rol de Admin
        $admin->assignRole('Admin');

        echo "✅ Usuario Admin creado exitosamente\n";
        echo "📧 Email: tecnologia@colbeef.com\n";
        echo "🔑 Password: SIRT123\n";
    }
}
