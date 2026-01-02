<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hari extends Model
{
    use HasFactory;
    protected $table = 'hari';

    protected $fillable = [
        'kode_hari','nama_hari',
    ];
    public function generate()
    {
    	return $this->hasMany(Generate::class, 'id_hari');
    }
}
