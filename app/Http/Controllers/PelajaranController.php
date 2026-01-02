<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Session;
use App\Models\Pelajaran;
use App\Models\Jurusan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DataTables;

class PelajaranController extends Controller
{
    // Menampilkan data pelajaran dalam bentuk DataTables
    public function index(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $pelajaran = Pelajaran::with('jurusan')->get();
            return DataTables::of($pelajaran)
                ->addColumn('action', function ($data) {
                    $button = '<button type="button" class="edit btn icon icon-left btn-success" id="'.$data->id.'"><i class="bi bi-pencil-square"></i></button>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<button type="button" class="delete btn icon icon-left btn-danger" id="'.$data->id.'"><i class="bi bi-trash"></i></button>';    
                    return $button;
                })
                ->addColumn('nama_jurusan', function ($pelajaran) {
                    return $pelajaran->jurusan->nama_jurusan;
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
        $kodePel = Pelajaran::select('kode_pel')->get();
        $dataJurusan = Jurusan::all();
        $user = User::where('nama', Session::get('nama'))->get();
        return view('admin.pelajaran.index', compact('dataJurusan','kodePel','user'));
    }

    // Menampilkan form tambah data pelajaran
    public function create()
    {
        return view('pelajaran.create');
    }

    // Menyimpan data pelajaran baru
    public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'kode_pel' => 'unique:mata_pelajaran,kode_pel',
        'nama_pel' => 'unique:mata_pelajaran,nama_pel,NULL,id,kode_pel,' . $request->kode_pel,
    ], [
        'kode_pel.unique' => 'Gagal menambahkan mata pelajaran karena kesalahan. Periksa kembali data',
        'nama_pel.unique' => 'Gagal menambahkan mata pelajaran karena kesalahan. Periksa kembali data',
    ]);

    if ($validator->fails()) {
        if ($request->expectsJson()) {
            return response()->json(['error' => $validator->errors()], 400);
        } else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    $pelajaran = Pelajaran::create($request->all());

    if ($pelajaran) {
        if ($request->expectsJson()) {
            return response()->json(['success' => 'Mata pelajaran berhasil ditambahkan.', 'pelajaran' => $pelajaran]);
        } else {
            return redirect()->back()->with('success', 'Mata pelajaran berhasil ditambahkan.');
        }
    } else {
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Gagal menambahkan Mata pelajaran.'], 500);
        } else {
            return redirect()->back()->with('error', 'Gagal menambahkan Mata pelajaran karena kesalahan.');
        }
    }
}
    public function edit($id)
    {
        $pelajaran = Pelajaran::findOrFail($id);
        return response()->json($pelajaran);
    }

    // Mengupdate data pelajaran
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kode_pel' => 'unique:mata_pelajaran,kode_pel,' . $id . ',id',
            'nama_pel' => 'unique:mata_pelajaran,nama_pel,' . $id . ',id,kode_pel,' . $request->kode_pel,
        ], [
            'kode_pel.unique' => 'Gagal memperbarui mata pelajaran karena kesalahan. Periksa kembali data',
            'nama_pel.unique' => 'Gagal memperbarui mata pelajaran karena kesalahan. Periksa kembali data',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => $validator->errors()], 400);
            } else {
                return redirect()->back()->withErrors($validator)->withInput();
            }
        }

        $pelajaran = Pelajaran::findOrFail($id);
        $pelajaran->update($request->all());

        if ($request->expectsJson()) {
            return response()->json(['success' => 'Mata pelajaran berhasil diperbarui.', 'pelajaran' => $pelajaran]);
        } else {
            return redirect()->back()->with('success', 'Mata pelajaranberhasil diperbarui.');
        }
    }

    // Menghapus data pelajaran
    public function destroy($id)
    {
        $pelajaran = Pelajaran::findOrFail($id);
        $pelajaran->delete();

        return response()->json(['success' => 'Mata pelajaran berhasil dihapus.']);
    }
}

