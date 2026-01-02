<?php

// Buat atau perbarui middleware Authenticate
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    public function handle($request, Closure $next, $guard = null)
    {
        if (!Auth::guard($guard)->check()) {
            return redirect()->route('login.index')->with('not_login', 'Anda belum login!');
        }

        return $next($request);
    }
}

