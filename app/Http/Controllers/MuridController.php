<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Support\Facades\Session;
use App\Models\Jurusan;
use App\Models\Murid; 
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\MuridImport;
// app\Http\Controllers\MuridController.php

use DataTables;

class MuridController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $murid = Murid::with('kelas')->get(); // Gunakan with('kelas') untuk mengambil relasi kelas
            return DataTables::of($murid) 
                ->addColumn('kelas', function ($data) {
                    return $data->kelas->nama_kelas; // Mengambil nama_kelas dari relasi kelas
                })
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

        $dataKelas = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        $user = User::where('nama', Session::get('nama'))->get();
        return view('admin.murid.index', compact('user', 'dataKelas')); 
    }

    public function create()
    {
        return view('murid.create'); 
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nisn' => 'required',
            'nama' => 'required',
            'jk' => 'required',
            'email' => 'required|email|unique:murid,email',
            'kelas' => 'required',
        ], [
            'email.unique' => 'Gagal menambahkan murid karena kesalahan. Periksa kembali data',
        ]);
        
    
        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => $validator->errors()], 400);
            } else {
                return redirect()->back()->withErrors($validator)->withInput();
            }
        }
    
        // Create a new User record
        $user = User::create([
            'nama' => $request->input('nama'),
            'jk' => $request->input('jk'),
            'email' => $request->input('email'),
            'role' => 'murid',
            'password' => Hash::make($request->input('nisn')), // Use 'nisn' as the password
        ]);
    
        // Create a new Murid record and associate it with the User
        $murid = Murid::create([
            'nisn' => $request->input('nisn'),
            'nama' => $request->input('nama'),
            'jk' => $request->input('jk'),
            'email' => $request->input('email'),
            'alamat' => $request->input('alamat'),
            'notelp' => $request->input('notelp'),
            'id_kelas' => $request->input('kelas'),
            'id_user' => $user->id,
        ]);
    
        // Upload the default profile image for each user
        $defaultImagePath = public_path('img/student.png');
        if (file_exists($defaultImagePath)) {
            $defaultImageName = 'student.png';
            $uniqueImageName = uniqid() . '_' . $defaultImageName;

            // Save the default image to the public storage directory
            $storagePath = 'storage/' . $uniqueImageName;
            copy($defaultImagePath, public_path($storagePath));

            // Update the user's profile_image field with the default image path
            $user->update([
                'profile_image' => $storagePath,
            ]);
        }
    
        if ($request->expectsJson()) {
            return response()->json(['success' => 'Murid berhasil ditambahkan.']);
        } else {
            return redirect()->back()->with('success', 'Murid berhasil ditambahkan.');
        }
    }    

    public function edit($id)
    {
        $murid = Murid::findOrFail($id); 
        return response()->json($murid);
    }

    public function update(Request $request, $id)
    {
        $murid = Murid::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nisn' => 'required',
            'nama' => 'required',
            'jk' => 'required',
            'email' => 'required|email|unique:murid,email,' . $murid->id,
            'kelas' => 'required',
        ], [
            'email.unique' => 'Gagal menambahkan murid karena kesalahan. Periksa kembali data',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => $validator->errors()], 400);
            } else {
                return redirect()->back()->withErrors($validator)->withInput();
            }
        }

        // Update User record
        $murid->user->update([
            'password' => Hash::make($request->input('nisn')),
            'nama' => $request->input('nama'),
            'email' => $request->input('email'),
        ]);

        // Update Murid record
        $murid->update([
            'nisn' => $request->input('nisn'),
            'nama' => $request->input('nama'),
            'jk' => $request->input('jk'),
            'email' => $request->input('email'),
            'alamat' => $request->input('alamat'),
            'notelp' => $request->input('notelp'),
            'id_kelas' => $request->input('kelas'),
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => 'Murid berhasil diperbarui.', 'murid' => $murid]);
        } else {
            return redirect()->back()->with('success', 'Murid berhasil diperbarui.');
        }
    }
    public function import(Request $request)
    {
        // Validate the file
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv'
        ]);

        $dataMurid = Murid::all();
        foreach ($dataMurid as $murid) {
            $user = User::find($murid->id_user);
            if ($user) {
                $user->delete(); 
            }
            $murid->delete();
        }

        Excel::import(new MuridImport, $request->file('file'));

        return response()->json(['success' => "Data murid berhasil diimport."]);
    }


    public function destroy($id)
    {
        $murid = Murid::findOrFail($id);
        $user = User::find($murid->id_user);
        if ($user) {
            $user->delete();
        }
        $murid->delete();
        return response()->json(['success' => 'Murid berhasil dihapus.']);
    }    

}
