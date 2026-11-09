<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Only users with owner or pegawai role can access admin
        if (!in_array(Auth::user()->role, ['owner', 'pegawai'])) {
            Auth::logout();
            return redirect()->route('admin.login')->with('error', 'Akses ditolak.');
        }

        return $next($request);
    }
}
