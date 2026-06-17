<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Empleado extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'experiencia',
        'calificacion',
        'estado',
        'foto'
    ];

    public function servicios()
    {
        return $this->hasMany(Servicio::class);
    }

    public function monitoreos()
    {
        return $this->hasMany(Monitoreo::class);
    }
}
