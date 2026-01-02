<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use DataTables;
use App\Models\Jadwal;
use App\Models\User;
use App\Models\Guru;
use Illuminate\Http\Request;

class HalamanGuruController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $userId = Session::get('user_id');

            // Fetching the Guru model directly by user_id and retrieving the guru's name
            $namaGuru = Guru::where('id_user', $userId)->value('nama');

            // Fetching data from the Jadwal table based on the guru's name
            $data = Jadwal::where('guru', $namaGuru)->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }

        $user = User::where('nama', Session::get('nama'))->get();

        $tahunAkademik = Jadwal::distinct()->pluck('tahun_akademik')->first();
        $semester = Jadwal::distinct()->pluck('semester')->first();

        return view('user.site.halaman_guru', compact('user', 'tahunAkademik','semester'));
    }
}
