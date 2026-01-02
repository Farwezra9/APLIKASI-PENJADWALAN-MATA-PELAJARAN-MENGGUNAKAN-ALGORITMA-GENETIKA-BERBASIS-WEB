<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class AuthController extends Controller
{
    public function index()
    {
        return view('auth.index'); // Correct view path
    }

    public function login_action(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $role = $user->role;

                $request->session()->put('user_id', $user->id);
                $request->session()->put('nama', $user->nama);
                $request->session()->regenerate();

            return response()->json(['success' => true, 'role' => $role]);
        }

        return response()->json(['success' => false]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Hapus cookie "remember" secara manual
        $response = redirect()->route('login.index')->with('logout', 'Anda berhasil logout!');

        return $response;
    }
}

