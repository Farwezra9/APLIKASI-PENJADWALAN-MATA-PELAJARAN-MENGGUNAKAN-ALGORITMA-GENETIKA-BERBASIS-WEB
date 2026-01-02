<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;
    protected $table = 'guru';

    protected $fillable = [
        'nip',
        'nama',
        'email',
        'pangkat',
        'alamat',
        'notelp',
        'id_user',

    ];
    public function mengajar()
    {
    	return $this->hasMany(Mengajar::class, 'id_guru');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
