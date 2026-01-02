<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Session;
use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\GuruImport;
use DataTables;

class GuruController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $guru = Guru::all();
            return DataTables::of($guru)
                ->addColumn('action', function ($guru) {
                    $button = '<button type="button" class="edit btn icon icon-left btn-success" id="'.$guru->id.'"><i class="bi bi-pencil-square"></i></button>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<button type="button" class="delete btn icon icon-left btn-danger" id="'.$guru->id.'"><i class="bi bi-trash"></i></button>';     
                    return $button;
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
        $user = User::where('nama', Session::get('nama'))->get();
        return view('admin.guru.index', compact('user'));
    }

    public function create()
    {
        return view('guru.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nip' => 'required',
            'nama' => 'required',
            'email' => 'required|email|unique:guru,email',
        ], [
            'email.unique' => 'Gagal menambahkan guru karena kesalahan. Periksa kembali data',
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
            'email' => $request->input('email'),
            'role' => 'guru',
            'password' => Hash::make($request->input('nip')), // Use 'nisn' as the password
        ]);
    
        // Create a new guru record and associate it with the User
        $guru = Guru::create([
            'nip' => $request->input('nip'),
            'nama' => $request->input('nama'),
            'pangkat' => $request->input('pangkat'),
            'email' => $request->input('email'),
            'alamat' => $request->input('alamat'),
            'notelp' => $request->input('notelp'),
            'id_user' => $user->id,
        ]);
    
        // Upload the default profile image for each user
        $defaultImagePath = public_path('img/teacher.png');
        if (file_exists($defaultImagePath)) {
            $defaultImageName = 'teacher.png';
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
            return response()->json(['success' => 'Guru berhasil ditambahkan.']);
        } else {
            return redirect()->back()->with('success', 'Guru berhasil ditambahkan.');
        }
    }    
    
    public function edit($id)
    {
        $guru = Guru::findOrFail($id);
        return response()->json($guru);
    }

    public function update(Request $request, $id)
    {
        $guru = Guru::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nip' => 'required',
            'nama' => 'required',
            'email' => 'required|email|unique:guru,email,' . $guru->id,
        ], [
            'email.unique' => 'Gagal menambahkan guru karena kesalahan. Periksa kembali data.',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => $validator->errors()], 400);
            } else {
                return redirect()->back()->withErrors($validator)->withInput();
            }
        }

        // Update User record
        $guru->user->update([
            'password' => Hash::make($request->input('nip')),
            'nama' => $request->input('nama'),
            'email' => $request->input('email'),
        ]);

        // Update guru record
        $guru->update([
            'nip' => $request->input('nip'),
            'nama' => $request->input('nama'),
            'pangkat' => $request->input('pangkat'),
            'email' => $request->input('email'),
            'alamat' => $request->input('alamat'),
            'notelp' => $request->input('notelp'),
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => 'Guru berhasil diperbarui.', 'guru' => $guru]);
        } else {
            return redirect()->back()->with('success', 'Guru berhasil diperbarui.');
        }
    }
    public function import(Request $request)
    {
        // Validate the file
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv'
        ]);

        // Retrieve all Guru records
        $dataGuru = Guru::all();

        foreach ($dataGuru as $guru) {
            // Find and delete the associated User record
            $user = User::find($guru->id_user);
            if ($user) {
                $user->delete();
            }

            // Delete the Guru record
            $guru->delete();
        }

        // Import new data from the uploaded file
        Excel::import(new GuruImport, $request->file('file'));

        return response()->json(['success' => "Data guru berhasil diimport."]);
    }


    public function destroy($id)
    {
        $guru = Guru::findOrFail($id);
        $user = User::find($guru->id_user);
        
        if ($user) {
            $user->delete();
        }
        $guru->delete();
    

        return response()->json(['success' => 'Guru berhasil dihapus.']);
    }
}
