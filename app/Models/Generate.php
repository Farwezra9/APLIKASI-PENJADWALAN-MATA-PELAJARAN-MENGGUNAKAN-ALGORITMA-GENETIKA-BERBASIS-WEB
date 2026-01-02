<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Generate extends Model
{
    use HasFactory;
    protected $table = 'generate_jadwal';

    protected $fillable = ['id_mengajar', 'id_hari', 'id_jam', 'kelas'];

    public function mengajar()
    {
        return $this->belongsTo(Mengajar::class, 'id_mengajar');
    }

    public function hari()
    {
        return $this->belongsTo(Hari::class, 'id_hari');
    }
    public function jam()
    {
        return $this->belongsTo(Jam::class, 'id_jam');
    }
}
