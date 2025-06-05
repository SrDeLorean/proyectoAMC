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
}
