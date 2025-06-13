<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Formacion extends Model
{
    protected $table = 'formaciones'; // 👈 esto es lo que faltaba

    protected $fillable = ['nombre'];
}
