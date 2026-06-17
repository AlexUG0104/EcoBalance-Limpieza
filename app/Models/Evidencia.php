<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Evidencia extends Model
{
    use HasFactory;

    protected $table = 'evidencias';

    protected $fillable = [
        'servicio_id',
        'antes_img',
        'durante_img',
        'despues_img'
    ];

    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }
}
