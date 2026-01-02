<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    use HasFactory;

    protected $table = 'jurusan';

    protected $fillable = [
        'nama_jurusan',
    ];
    public function kelas()
    {
    	return $this->hasMany(Kelas::class, 'id_jurusan');
    }
    public function pelajaran()
    {
    	return $this->hasMany(Pelajaran::class, 'id_jurusan');
    }
}
