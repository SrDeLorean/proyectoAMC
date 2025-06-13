<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipo extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nombre',
        'descripcion',
        'color_primario',
        'color_secundario',
        'logo',
        'id_formacion',
        'instagram',
        'twitch',
        'youtube',
        'id_usuario',
        'id_usuario2',
    ];

    // Relaciones que se cargarán automáticamente
    protected $with = ['formacion', 'propietario', 'entrenador'];

    public function formacion()
    {
        return $this->belongsTo(Formacion::class, 'id_formacion');
    }

    public function propietario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function entrenador()
    {
        return $this->belongsTo(User::class, 'id_usuario2');
    }
}
