<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Session;
use App\Models\Jurusan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DataTables;

class JurusanController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax() || $request->wantsJson()){
            $jurusan = Jurusan::all();
            return DataTables::of($jurusan)
            ->addColumn('action', function($data){
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
        return view('admin.jurusan.index',compact('user'));
    }
    

    public function create()
    {
        return view('jurusan.create');
    }

    public function store(Request $request)
{
    $validator = \Validator::make($request->all(), [
        'nama_jurusan' => 'required|unique:jurusan,nama_jurusan',
    ], [
        'nama_jurusan.unique' => 'Gagal menambahkan jurusan karena kesalahan. Periksa kembali data',
    ]);

    if ($validator->fails()) {
        if ($request->expectsJson()) {
            return response()->json(['error' => $validator->errors()], 400);
        } else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }
    $jurusan = Jurusan::create($request->all());

    if ($jurusan) {
        if ($request->expectsJson()) {
            return response()->json(['success' => 'jurusan berhasil ditambahkan.', 'jurusan' => $jurusan]);
        } else {
            return redirect()->back()->with('success', 'jurusan berhasil ditambahkan.');
        }
    } else {
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Gagal menambahkan jurusan.'], 500);
        } else {
             return redirect()->back()->with('error', 'Gagal menambahkan jurusan karena kesalahan.');
        }
    }
}

    public function edit($id)
    {
        $jurusan = Jurusan::findOrFail($id);
        return response()->json($jurusan);
    }

    public function update(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
            'nama_jurusan' => 'required|unique:jurusan,nama_jurusan,' . $id . ',id',
        ], [
            'nama_jurusan.unique' => 'Gagal memperbarui jurusan karena kesalahan. Periksa kembali data',
        ]);
        
        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => $validator->errors()], 400);
            } else {
                return redirect()->back()->withErrors($validator)->withInput();
            }
        }
        $jurusan = Jurusan::findOrFail($id);
        $jurusan->update($request->all());

        if ($jurusan) {
            if ($request->expectsJson()) {
                return response()->json(['success' => 'Jurusan berhasil diperbarui.', 'jurusan' => $jurusan]);
            } else {
                return redirect()->back()->with('success', 'Jurusan berhasil didiperbarui!.');
            }
        } else {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Gagal memperbarui jurusan.'], 500);
            } else {
                 return redirect()->back()->with('error', 'Gagal memperbarui jurusan karena kesalahan.');
            }
        }
    }

    public function destroy($id)
    {
        $jurusan = Jurusan::findOrFail($id);
        $jurusan->delete();

        return response()->json(['success' => 'Jurusan berhasil dihapus.']);
    }
}
