<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // ← NUEVA COLUMNA
        Schema::table('despachos', function (Blueprint $table) {
            $table->foreignId('created_by')
                  ->nullable()
                  ->constrained('users')
                  ->after('usuario_id');
            
            $table->index('created_by');
        });

        // ← IMPORTANTE: Llenar datos existentes con usuario logueado actual
        if (DB::table('despachos')->whereNull('created_by')->exists()) {
            DB::table('despachos')
                ->whereNull('created_by')
                ->update(['created_by' => auth()->id() ?? 1]); // 1 = primer usuario (admin)
        }
    }

    public function down()
    {
        Schema::table('despachos', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropIndex(['created_by']);
            $table->dropColumn('created_by');
        });
    }
};
