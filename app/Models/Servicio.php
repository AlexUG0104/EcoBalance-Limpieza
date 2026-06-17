<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Servicio extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'empleado_id',
        'nombre_contacto',
        'telefono_contacto',
        'direccion',
        'tipo_servicio',
        'fecha',
        'hora',
        'comentarios_adicionales',
        'estado'
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function monitoreo()
    {
        return $this->hasOne(Monitoreo::class);
    }

    public function evidencia()
    {
        return $this->hasOne(Evidencia::class);
    }

    public function comentario()
    {
        return $this->hasOne(Comentario::class);
    }
}
