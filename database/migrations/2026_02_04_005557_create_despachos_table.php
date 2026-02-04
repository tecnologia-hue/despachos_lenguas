<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('despachos', function (Blueprint $table) {
            $table->id();
            $table->string('conductor')->nullable();
            $table->string('destino_original')->nullable();
            $table->string('destino_simplificado')->nullable();
            $table->string('codigo_producto')->nullable();
            $table->string('producto')->nullable();
            $table->decimal('peso_frio', 8, 2)->nullable();
            $table->decimal('peso_caliente', 8, 2)->nullable();
            $table->string('codigo_unificado')->nullable();
            $table->integer('lenguas')->default(10);
            $table->string('plaza_remolque')->default('SXT 135');
            $table->date('fecha_beneficio')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('despachos');
    }
};
