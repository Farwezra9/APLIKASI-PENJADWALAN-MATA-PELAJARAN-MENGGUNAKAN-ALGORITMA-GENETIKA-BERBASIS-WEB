<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelajaran extends Model
{
    use HasFactory;
    protected $table = 'mata_pelajaran';

    protected $fillable = [
        'kode_pel',
        'nama_pel',
        'jenis',
        'sks',
        'id_jurusan',
    ];
 
    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'id_jurusan');
    }
    public function mengajar()
    {
    	return $this->hasMany(Mengajar::class, 'id_pel');
    }
}
