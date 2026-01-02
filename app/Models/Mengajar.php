<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mengajar extends Model
{
    use HasFactory;

    protected $table = 'mengajar';

    protected $fillable = ['id_guru', 'id_pel', 'semester','sks', 'kelas'];

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru');
    }

    public function pelajaran()
    {
        return $this->belongsTo(Pelajaran::class, 'id_pel');
    }
    public function generate()
    {
    	return $this->hasMany(Generate::class, 'id_mengajar');
    }
}
