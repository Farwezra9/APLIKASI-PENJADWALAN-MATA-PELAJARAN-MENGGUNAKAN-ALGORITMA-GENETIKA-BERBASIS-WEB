<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',
        'profile_image',
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];
    public function hasRole($role)
    {
        return $this->role === $role; // Adjust this based on your actual role implementation
    }
    public function murid()
    {
        return $this->hasOne(Murid::class, 'id_user'); // Adjust the foreign key column if needed
    }
    public function guru()
    {
        return $this->hasOne(Guru::class, 'id_user'); // Adjust the foreign key column if needed
    }
}
