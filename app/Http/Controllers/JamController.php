<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Jam;
use App\Models\User;
use DataTables;
use DateInterval;
use DateTime;

class JamController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $jam = Jam::all();
            return DataTables::of($jam)
                ->addColumn('action', function ($data) {
                    $button = '<button type="button" class="edit btn icon icon-left btn-success" id="'.$data->id.'"><i class="bi bi-pencil-square"></i></button>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<button type="button" class="delete btn icon icon-left btn-danger" id="'.$data->id.'"><i class="bi bi-trash"></i></button>';
                    return $button;
                })
                ->rawColumns(['action', 'jeda'])
                ->addIndexColumn()
                ->make(true);
        }
        $dataId = Jam::select('id')->get();
        $dataRange = Jam::select('range_jam')->get();
        $user = User::where('nama', Session::get('nama'))->get();
        return view('admin.jam.index', compact('dataId','dataRange','user'));
    }

    public function create()
    {
        return view('jam.create');
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_jam' => 'required|unique:jam,kode_jam',
        ], [
            'kode_jam.unique' => 'Kode jam sudah digunakan.',
        ]);
    
        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => $validator->errors()], 400);
            } else {
                return redirect()->back()->withErrors($validator)->withInput();
            }
        }
    
        $jam = Jam::create($request->all());
    
        if ($jam) {
            if ($request->expectsJson()) {
                return response()->json(['success' => 'Jam berhasil ditambahkan.', 'jam' => $jam]);
            } else {
                return redirect()->back()->with('success', 'Jam berhasil ditambahkan.');
            }
        } else {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Gagal menambahkan jam.'], 500);
            } else {
                return redirect()->back()->with('error', 'Gagal menambahkan jam karena kesalahan.');
            }
        }
    }    

    public function edit($id)
    {
        $jam = Jam::find($id);
        return response()->json($jam); 
    }
    
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kode_jam' => 'required|unique:jam,kode_jam,' . $id . ',id',
        ], [
            'kode_jam.unique' => 'Kode jam sudah digunakan.',
        ]);
        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => $validator->errors()], 400);
            } else {
                return redirect()->back()->withErrors($validator)->withInput();
            }
        }
        $jam = Jam::findOrFail($id);
        $jam->update($request->all());
    
        if ($jam) {
            if ($request->expectsJson()) {
                return response()->json(['success' => 'jam berhasil diperbarui.', 'jam' => $jam]);
            } else {
                return redirect()->back()->with('success', 'jam berhasil diperbarui.');
            }
        } else {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Gagal memperbarui jam.'], 500);
            } else {
                 return redirect()->back()->with('error', 'Gagal memperbarui jam karena kesalahan.');
            }
        }
    }

    public function destroy($id)
    {
        $jam = Jam::find($id);
        $jam->delete();

        return response()->json(['success' => 'Jam berhasil dihapus.']);
    }
}