<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    // $role adalah peran yang kita inginkan (misal: 'dosen')
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Jika user tidak login ATAU perannya tidak sesuai dengan yang diizinkan
        if (!Auth::check() || $request->user()->role !== $role) {
            // Tampilkan halaman error 403 (Forbidden)
            abort(403, 'AKSES DITOLAK: ANDA TIDAK MEMILIKI HAK AKSES.');
        }

        // Jika peran sesuai, lanjutkan ke halaman berikutnya
        return $next($request);
    }
}