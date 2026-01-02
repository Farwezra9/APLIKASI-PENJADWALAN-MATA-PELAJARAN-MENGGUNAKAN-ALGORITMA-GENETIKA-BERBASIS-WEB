<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Murid extends Model
{
    
    use HasFactory;
    protected $table = 'murid';
    protected $fillable = [
        'nisn',
        'nama',
        'jk',
        'alamat',
        'email',
        'notelp',
        'id_kelas',
        'id_user',
    ];
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
