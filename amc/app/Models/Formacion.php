<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Equipo;

class Formacion extends Model
{
    protected $fillable = ['nombre'];

    public function equipos()
    {
        return $this->hasMany(Equipo::class, 'id_formacion');
    }
}
