<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Guru;
use App\Models\Murid;
use App\Models\kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
            $user = Auth::user();
        
            if (in_array($user->role, ['guru', 'murid'])) {
                $profile = null;
        
                if ($user->role === 'guru') {
                    $profile = Guru::where('id_user', $user->id)->first();
                return view('user.profile.guru_profile', compact('user', 'profile'));
                } elseif ($user->role === 'murid') {
                    $profile = Murid::where('id_user', $user->id)->first();
                    $dataKelas = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
                return view('user.profile.murid_profile', compact('user', 'profile','dataKelas'));
                }
            }
        
            return view('admin.profile.index', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'email' => 'required',
        ]);
    
        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => $validator->errors()], 400);
            } else {
                return redirect()->back()->withErrors($validator)->withInput();
            }
        }
    
        $user = User::findOrFail($id);
        $user->update($request->all()); 
        session(['nama' => $user->nama]);
        session(['email' => $user->email]);
    
        if ($request->expectsJson()) {
            return response()->json(['success' => 'Profile berhasil diperbarui.', 'user' => $user]);
        } else {
            return redirect()->back()->with('success', 'User berhasil diperbarui.');
        }
    }
    public function updatemurid(Request $request, $id)
    {
        $murid = Murid::findOrFail($id);
    
        $validator = Validator::make($request->all(), [
            'nisn' => 'required',
            'nama' => 'required',
            'jk' => 'required',
            'email' => 'required',
            'kelas' => 'required',
        ]);
    
        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => $validator->errors()], 400);
            } else {
                return redirect()->back()->withErrors($validator)->withInput();
            }
        }
        $murid->update([
            'nisn' => $request->input('nisn'),
            'nama' => $request->input('nama'),
            'jk' => $request->input('jk'),
            'email' => $request->input('email'),
            'alamat' => $request->input('alamat'),
            'notelp' => $request->input('notelp'),
            'id_kelas' => $request->input('kelas'),
        ]);
            // Ambil id_user dari data Murid
    $user = User::findOrFail($murid->id_user);
        $user->update($request->all()); 
        session(['nama' => $user->nama]);
        session(['email' => $user->email]);
    
        if ($request->expectsJson()) {
            return response()->json(['success' => 'Profile berhasil diperbarui.', 'user' => $user]);
        } else {
            return redirect()->back()->with('success', 'User berhasil diperbarui.');
        }
    }
    public function updateguru(Request $request, $id)
    {
        $guru = Guru::findOrFail($id);
    
        $validator = Validator::make($request->all(), [
            'nip' => 'required',
            'nama' => 'required',
            'email' => 'required',
        ]);
    
        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => $validator->errors()], 400);
            } else {
                return redirect()->back()->withErrors($validator)->withInput();
            }
        }
        $guru->update([
            'nip' => $request->input('nip'),
            'nama' => $request->input('nama'),
            'pangkat' => $request->input('pangkat'),
            'email' => $request->input('email'),
            'alamat' => $request->input('alamat'),
            'notelp' => $request->input('notelp'),
        ]);
            // Ambil id_user dari data Murid
    $user = User::findOrFail($guru->id_user);
        $user->update($request->all()); 
        session(['nama' => $user->nama]);
        session(['email' => $user->email]);
    
        if ($request->expectsJson()) {
            return response()->json(['success' => 'Profile berhasil diperbarui.', 'user' => $user]);
        } else {
            return redirect()->back()->with('success', 'User berhasil diperbarui.');
        }
    }
    
    public function updatePassword(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|current_password',
            'password_baru' => 'required',
            're_password_baru' => 'required|same:password_baru',
        ]);
    
        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => $validator->errors()], 400);
            } else {
                return redirect()->back()->withErrors($validator)->withInput();
            }
        }
    
        $user = User::findOrFail($id);
        $user->password = Hash::make($request->re_password_baru);
        $user->save();
    
        if ($request->expectsJson()) {
            return response()->json(['success' => 'Password berhasil diperbarui.', 'user' => $user]);
        } else {
            return redirect()->back()->with('success', 'Password berhasil diperbarui.');
        }
    }
    public function updateProfileImage(Request $request, $id)
    {
        $user = User::find($id);
    
        $validator = Validator::make($request->all(), [
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => $validator->errors()], 400);
            } else {
                return redirect()->back()->withErrors($validator)->withInput();
            }
        }
    
        if ($user->profile_image) {
            $previousImage = public_path($user->profile_image);
            
            if (file_exists($previousImage)) {
                unlink($previousImage);
            }
        }
    
        $image = $request->file('profile_image');
        $imageName = uniqid() . '_' . $image->getClientOriginalName(); // Get a unique name for the image
    
        // Move the image to the public/storage directory
        $image->move(public_path('storage'), $imageName);
    
        $user->update([
            'profile_image' => 'storage/' . $imageName, // Store the relative path
        ]);
        
        if ($request->expectsJson()) {
            return response()->json(['success' => 'Profile berhasil diperbarui.']);
        } else {
            return redirect()->back()->with('success', 'Profile berhasil diperbarui.');
        }
    }
    
}

