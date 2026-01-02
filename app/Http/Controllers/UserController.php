<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DataTables;

class UserController extends Controller
{public function index(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $users = User::where('id', '!=', Auth::id())->get();
            return DataTables::of($users)
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
        return view('admin.user.index', compact('user'));
    }    

    public function create()
    {
        return view('user.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'email' => 'required',
            'role' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => $validator->errors()], 400);
            } else {
                return redirect()->back()->withErrors($validator)->withInput();
            }
        }
        $data = $request->except(['_token']);
        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);

        if ($request->expectsJson()) {
            return response()->json(['success' => 'User berhasil ditambahkan.', 'user' => $user]);
        } else {
            return redirect()->back()->with('success', 'User berhasil ditambahkan.');
        }
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'email' => 'required',
            'role' => 'required',
            'password' => 'nullable',
        ]);
    
        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => $validator->errors()], 400);
            } else {
                return redirect()->back()->withErrors($validator)->withInput();
            }
        }
    
        $user = User::findOrFail($id);
        $user->nama = $request->input('nama');
        $user->email = $request->input('email');
        $user->role = $request->input('role');
    
        if ($request->filled('password')) {
            $user->password = bcrypt($request->input('password'));
        }
    
        $user->save();
    
        if ($request->expectsJson()) {
            return response()->json(['success' => 'User berhasil diperbarui.', 'user' => $user]);
        } else {
            return redirect()->back()->with('success', 'User berhasil diperbarui.');
        }
    }
    

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['success' => 'Guru berhasil dihapus.']);
    }
}
