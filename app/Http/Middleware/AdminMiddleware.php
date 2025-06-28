<?php

// app/Http/Middleware/AdminMiddleware.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Pastikan ini ada
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah login DAN memiliki role 'dosen'
        if (Auth::check() && Auth::user()->role == 'dosen') {
            return $next($request);
        }

        // Jika tidak, redirect ke halaman login user biasa dengan pesan error
        return redirect('/login')->with('error', 'Anda tidak memiliki hak akses Admin.');
    }
}