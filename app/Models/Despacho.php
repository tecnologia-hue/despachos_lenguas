<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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
        'created_by', // ← NUEVO: Quién creó el despacho
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

    // Relación: Un despacho pertenece a un usuario (ANTIGUA)
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    // ← NUEVA RELACIÓN: Quién creó realmente el despacho
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ← NUEVO: Auto-asignar creador al crear
    protected static function booted()
    {
        static::creating(function ($despacho) {
            if (Auth::check()) {
                $despacho->created_by = Auth::id();
            }
        });
    }
}
