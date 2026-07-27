<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    use HasFactory;

    protected $fillable = [
        'area_id',
        'nombre',
        'estado',
    ];

    // Relación: Un cargo pertenece a un área
    public function area()
    {
        return $this->belongsTo(Area::class);
    }
}
