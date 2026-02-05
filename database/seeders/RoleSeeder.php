<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear roles
        $adminRole = Role::create(['name' => 'Admin']);
        $operadorRole = Role::create(['name' => 'Operador']);
        $lecturaRole = Role::create(['name' => 'Lectura']);

        // Crear permisos
        $permisos = [
            'ver despachos',
            'crear despachos',
            'editar despachos',
            'eliminar despachos',
            'importar excel',
            'generar pdf',
            'gestionar usuarios',
        ];

        foreach ($permisos as $permiso) {
            Permission::create(['name' => $permiso]);
        }

        // Asignar permisos a Admin (tiene todos)
        $adminRole->givePermissionTo(Permission::all());

        // Asignar permisos a Operador
        $operadorRole->givePermissionTo([
            'ver despachos',
            'crear despachos',
            'editar despachos',
            'importar excel',
            'generar pdf',
        ]);

        // Asignar permisos a Lectura (solo ver)
        $lecturaRole->givePermissionTo([
            'ver despachos',
            'generar pdf',
        ]);
    }
}
