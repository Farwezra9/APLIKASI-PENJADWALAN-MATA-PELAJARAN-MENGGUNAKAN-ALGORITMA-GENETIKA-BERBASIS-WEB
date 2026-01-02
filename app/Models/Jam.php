<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jam extends Model
{
    use HasFactory;
    protected $table = 'jam';

    protected $fillable = [
        'kode_jam',
        'jam_mulai',
        'jam_selesai',
        'jeda',
    ];
    public function generate()
    {
    	return $this->hasMany(Generate::class, 'id_jam');
    }
}
