<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Monitoreo extends Model
{
    use HasFactory;

    protected $table = 'monitoreos';

    protected $fillable = [
        'servicio_id',
        'empleado_id',
        'estado_camara',
        'hora_inicio',
        'hora_final',
        'video_path',
        'duracion',
        'fecha'
    ];

    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
}
