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
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Equipos donde el usuario es propietario
    public function equipos()
    {
        return $this->hasMany(Equipo::class, 'id_usuario');
    }

    // Equipos donde el usuario es entrenador
    public function equiposComoEntrenador()
    {
        return $this->hasMany(Equipo::class, 'id_usuario2');
    }

    protected $casts = [
        'email_verified_at' => 'datetime',
        'deleted_at' => 'datetime',  // Cast para soft deletes
        'password' => 'hashed',
    ];

    public function getProfilePhotoUrlAttribute()
    {
        return $this->profile_photo_path
            ? asset('storage/' . $this->profile_photo_path)
            : asset('storage/images/users/default-user.png');
    }

    public function plantillaActual()
    {
        return $this->hasOne(\App\Models\TemporadaPlantilla::class, 'id_jugador')
            ->whereNull('fecha_salida'); // Asegúrate de que esta lógica coincida con la tuya
    }

    public function plantilla()
    {
        return $this->hasOne(\App\Models\Plantilla::class, 'id_jugador');
    }
}
