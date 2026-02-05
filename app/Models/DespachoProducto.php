<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DespachoProducto extends Model
{
    use HasFactory;

    protected $table = 'despacho_productos';

    protected $fillable = [
        'despacho_id',
        'codigo_producto',
        'descripcion_producto',
        'peso_frio',
        'peso_caliente',
        'temperatura',
        'decomisos',
        'destino_especifico',
        'fecha_beneficio',
    ];

    protected $casts = [
        'fecha_beneficio' => 'datetime',
        'peso_frio' => 'decimal:2',
        'peso_caliente' => 'decimal:2',
        'temperatura' => 'decimal:2',
    ];

    // Relación: Un producto pertenece a un despacho
    public function despacho()
    {
        return $this->belongsTo(Despacho::class);
    }
}
