<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use DataTables;
use App\Models\Jadwal;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\JadwalImport;
class JadwalController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Jadwal::all();

            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
        $user = User::where('nama', Session::get('nama'))->first();
        $tahunAkademik = Jadwal::distinct()->pluck('tahun_akademik')->first();
        $semester = Jadwal::distinct()->pluck('semester')->first();
        return view('admin.jadwal.index', compact('user','tahunAkademik','semester'));
    }

    public function import(Request $request)
    {
        // Validate the file
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv'
        ]);

        // Clear existing data
        Jadwal::truncate();

        // Import the new data
        Excel::import(new JadwalImport, $request->file('file'));

        return response()->json(['success' => "Data jadwal berhasil diimport."]);
    }

}
