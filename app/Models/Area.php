<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'estado',
    ];

    public function cargos()
    {
        return $this->hasMany(Cargo::class);
    }
}
