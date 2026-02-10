<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // PASO 1: Agregar columnas como NULLABLE primero
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('name');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('username')->nullable()->after('last_name');
            $table->boolean('active')->default(true)->after('password');
        });

        // PASO 2: Migrar TODOS los usuarios existentes
        $users = DB::table('users')->get();
        
        foreach ($users as $user) {
            // Dividir el nombre en first_name y last_name
            $nameParts = explode(' ', trim($user->name), 2);
            $firstName = $nameParts[0] ?? 'Usuario';
            $lastName = $nameParts[1] ?? 'Sistema';
            
            // Generar username base
            $usernameBase = strtolower($firstName . '.' . $lastName);
            $usernameBase = $this->removeAccents($usernameBase);
            $usernameBase = str_replace(' ', '', $usernameBase);
            
            // Verificar si el username ya existe y agregar número si es necesario
            $username = $usernameBase;
            $counter = 1;
            
            while (DB::table('users')->where('username', $username)->where('id', '!=', $user->id)->exists()) {
                $username = $usernameBase . $counter;
                $counter++;
            }
            
            // Actualizar el usuario
            DB::table('users')->where('id', $user->id)->update([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'username' => $username,
            ]);
        }

        // PASO 3: Hacer los campos obligatorios y agregar índices
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->nullable(false)->change();
            $table->string('last_name')->nullable(false)->change();
            $table->string('username')->unique()->nullable(false)->change();
            
            // Email ahora es opcional
            $table->string('email')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'last_name', 'username', 'active']);
            $table->string('email')->nullable(false)->change();
        });
    }
    
    /**
     * Remover tildes y caracteres especiales
     */
    private function removeAccents(string $string): string
    {
        $unwanted_array = [
            'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
            'Á' => 'a', 'É' => 'e', 'Í' => 'i', 'Ó' => 'o', 'Ú' => 'u',
            'ñ' => 'n', 'Ñ' => 'n'
        ];
        
        return strtr($string, $unwanted_array);
    }
};
