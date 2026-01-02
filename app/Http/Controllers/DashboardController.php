<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Session;
use App\Models\Pelajaran;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\jadwal;
use App\Models\mengajar;
use App\Models\Hari;
use App\Models\Jam;
use App\Models\Murid;
use App\Models\User;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totaljurusan = Jurusan::count();
        $totalkelas = Kelas::count(); 
        $totalguru = Guru::count();
        $totalpel = Pelajaran::count();
        $totalmurid = Murid::count();
        $totaljadwal = Jadwal::count();
        $totalmengajar = Mengajar::distinct()->count('id_guru');
        $totalhari = Hari::count();
        $totaljam = Jam::count();
        $user = User::where('nama', Session::get('nama'))->get();
        return view('admin.site.halaman_admin', compact('totaljurusan', 'totalkelas', 'totalguru', 'totalpel', 'totalmurid', 'totaljadwal', 'totalmengajar',  'totalhari', 'totaljam','user'));
    }
}
