<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Session;
use App\Models\Hari;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DataTables;

class HariController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $hari = Hari::all();
            return DataTables::of($hari)
                ->addColumn('action', function ($data) {
                    $button = '<button type="button" class="edit btn icon icon-left btn-success" id="'.$data->id.'"><i class="bi bi-pencil-square"></i></button>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<button type="button" class="delete btn icon icon-left btn-danger" id="'.$data->id.'"><i class="bi bi-trash"></i></button>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
        $user = User::where('nama', Session::get('nama'))->get();
        return view('admin.hari.index', compact('user'));
    }

    public function create()
    {
        return view('hari.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_hari' => 'required|unique:hari,kode_hari',
            'nama_hari' => 'required|unique:hari,nama_hari',
        ], [
            'kode_hari.unique' => 'Kode hari sudah digunakan.',
            'nama_hari.unique' => 'Nama hari sudah digunakan.',
        ]);
    
        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => $validator->errors()], 400);
            } else {
                return redirect()->back()->withErrors($validator)->withInput();
            }
        }
    
        $hari = Hari::create($request->all());
    
        if ($hari) {
            if ($request->expectsJson()) {
                return response()->json(['success' => 'Hari berhasil ditambahkan.', 'hari' => $hari]);
            } else {
                return redirect()->back()->with('success', 'Hari berhasil ditambahkan.');
            }
        } else {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Gagal menambahkan hari.'], 500);
            } else {
                return redirect()->back()->with('error', 'Gagal menambahkan hari karena kesalahan.');
            }
        }
    }

    public function edit($id)
    {
        $hari = Hari::findOrFail($id);
        return response()->json($hari);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kode_hari' => 'required|unique:hari,kode_hari,' . $id . ',id',
            'nama_hari' => 'required|unique:hari,nama_hari,' . $id . ',id',
        ], [
            'kode_hari.unique' => 'Kode hari sudah digunakan.',
            'nama_hari.unique' => 'Nama hari sudah digunakan.',
        ]);
        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => $validator->errors()], 400);
            } else {
                return redirect()->back()->withErrors($validator)->withInput();
            }
        }
        $hari = Hari::findOrFail($id);
        $hari->update($request->all());
    
        if ($hari) {
            if ($request->expectsJson()) {
                return response()->json(['success' => 'Hari berhasil diperbarui.', 'Hari' => $hari]);
            } else {
                return redirect()->back()->with('success', 'Hari berhasil diperbarui.');
            }
        } else {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Gagal memperbarui Hari.'], 500);
            } else {
                 return redirect()->back()->with('error', 'Gagal memperbarui Hari karena kesalahan.');
            }
        }
    }

    public function destroy($id)
    {
        $hari = Hari::findOrFail($id);
        $hari->delete();

        return response()->json(['success' => 'Hari berhasil dihapus.']);
    }
}
