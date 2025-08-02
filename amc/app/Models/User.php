<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;  // Importa SoftDeletes

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes; // Usa SoftDeletes

    protected $fillable = [
        'name',
        'id_ea',
        'email',
        'password',
        'role',
        'foto',
        'nacionalidad',
        'posicion',
        'fecha_nacimiento',
        'altura',
        'peso',
        'telefono',
        'instagram',
        'facebook',
        'twitch',
        'youtube',
        'tiktok',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'deleted_at'        => 'datetime',  // Cast para soft deletes
        'password'          => 'hashed',
        'fecha_nacimiento'  => 'date',      // Si quieres tratarla como fecha
    ];

    // Equipos donde el usuario es propietario
    public function equipos()
    {
        return $this->hasMany(\App\Models\Equipo::class, 'id_usuario');
    }

    // Equipos donde el usuario es entrenador
    public function equiposComoEntrenador()
    {
        return $this->hasMany(\App\Models\Equipo::class, 'id_usuario2');
    }

    public function getProfilePhotoUrlAttribute()
    {
        return $this->foto && $this->foto !== 'images/users/default-user.png'
            ? asset($this->foto)
            : asset('images/users/default-user.png');
    }

    public function plantillaActual()
    {
        return $this->hasOne(\App\Models\TemporadaPlantilla::class, 'id_jugador')
            ->whereNull('fecha_salida'); // Asegúrate que esta lógica coincide con tu aplicación
    }

    public function plantilla()
    {
        return $this->hasOne(\App\Models\Plantilla::class, 'id_jugador');
    }
}
