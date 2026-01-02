<?php

namespace App\Http\Controllers;

use App\Models\Murid;
use Illuminate\Support\Facades\Session;
use DataTables;
use App\Models\Jadwal;
use App\Models\User;
use App\Models\Kelas;
use Illuminate\Http\Request;

class HalamanMuridController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $userId = Session::get('user_id');

            // Fetching the user by ID with the Murid relationship
            $user = User::with('murid')->find($userId);

            // Retrieving the id_kelas from the user's Murid relationship
            $idKelas = $user->murid->id_kelas;

            // Mengambil data nama_kelas pada tabel kelas yang sama berdasarkan id di tabel kelas dengan id_kelas di tabel murid
            $dataKelas = Kelas::find($idKelas);

            // Perform a join between 'jadwal' and 'kelas' tables on the 'kelas' column
            $data = Jadwal::join('kelas', 'jadwal.kelas', '=', 'kelas.nama_kelas')
                ->where('kelas.id', $idKelas)
                ->get(['jadwal.*']);

            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }

        $user = User::where('nama', Session::get('nama'))->get();

        $tahunAkademik = Jadwal::distinct()->pluck('tahun_akademik')->first();
        $semester = Jadwal::distinct()->pluck('semester')->first();
        return view('user.site.halaman_murid', compact('user', 'tahunAkademik','semester'));
    }
}
