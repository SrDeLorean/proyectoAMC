<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plantilla extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id_equipo',
        'id_jugador',
        'posicion',
        'numero',
    ];

    public function equipo()
    {
        return $this->belongsTo(Equipo::class, 'id_equipo');
    }

    public function jugador()
    {
        return $this->belongsTo(User::class, 'id_jugador');
    }
}
