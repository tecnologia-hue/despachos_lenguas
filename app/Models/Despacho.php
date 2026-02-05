<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Despacho extends Model
{
    use HasFactory;

    protected $fillable = [
        'conductor',
        'placa_remolque',
        'destino_general',
        'fecha_expedicion',
        'lenguas',
        'archivo_original',
        'usuario_id',
    ];

    protected $casts = [
        'fecha_expedicion' => 'datetime',
        'lenguas' => 'integer',
    ];

    // Relación: Un despacho tiene muchos productos
    public function productos()
    {
        return $this->hasMany(DespachoProducto::class);
    }

    // Relación: Un despacho pertenece a un usuario
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
