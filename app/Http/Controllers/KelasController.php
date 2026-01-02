<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Session;
use App\Models\Kelas;
use App\Models\Jurusan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DataTables;

class KelasController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $kelas = Kelas::with('jurusan')->get();
            return DataTables::of($kelas)
                ->addColumn('action', function ($kelas) {
                    $button = '<button type="button" class="edit btn icon icon-left btn-success" id="'.$kelas->id.'"><i class="bi bi-pencil-square"></i></button>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<button type="button" class="delete btn icon icon-left btn-danger" id="'.$kelas->id.'"><i class="bi bi-trash"></i></button>';     
                    return $button;
                })
                ->addColumn('nama_jurusan', function ($kelas) {
                    return $kelas->jurusan->nama_jurusan;
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }

        $dataJurusan = Jurusan::all();
        $dataKelas = Kelas::all();
        $user = User::where('nama', Session::get('nama'))->get();
        return view('admin.kelas.index', compact('dataJurusan','dataKelas','user'));
    
}


    public function create()
    {
        return view('kelas.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_kelas' => 'required|unique:kelas,nama_kelas,NULL,id,tingkat,' . $request->tingkat . ',id_jurusan,' . $request->id_jurusan,
            'tingkat' => 'required',
            'id_jurusan' => 'required',
        ], [
            'nama_kelas.unique' => 'Gagal memperbarui kelas karena kesalahan. Periksa kembali data.',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $kelas = Kelas::create($request->all());

        if ($kelas) {
            return response()->json(['success' => 'Kelas berhasil ditambahkan']);
        } else {
            return response()->json(['message' => 'Gagal menambahkan kelas karena kesalahan. Periksa kembali data.'], 500);
        }
    }

    

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_kelas' => 'required|unique:kelas,nama_kelas,' . $id . ',id,tingkat,' . $request->tingkat . ',id_jurusan,' . $request->id_jurusan,
            'tingkat' => 'required',
            'id_jurusan' => 'required'
        ], [
            'nama_kelas.unique' => 'Gagal memperbarui kelas karena kesalahan. Periksa kembali data.',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $kelas = Kelas::findOrFail($id);
        $kelas->update($request->all());

        if ($kelas) {
            return response()->json(['success' => 'Kelas berhasil diperbaharui']);
        } else {
            return response()->json(['message' => 'Gagal memperbarui kelas karena kesalahan. Periksa kembali data.'], 500);
        }
    }

    public function edit($id)
    {
        $kelas = Kelas::findOrFail($id);
        return response()->json($kelas);
    }

    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->delete();

        return response()->json(['success' => 'Kelas berhasil dihapus.']);
    }
}
