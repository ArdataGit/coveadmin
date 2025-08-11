<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminAuth
{
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah session admin ada
        if (! session()->has('admin_id')) {
            return redirect('/'); // kembali ke halaman login
        }

        return $next($request);
    }
}
