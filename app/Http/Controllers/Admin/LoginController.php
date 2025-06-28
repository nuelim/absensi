<?php

// app/Http/Controllers/Admin/LoginController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Menampilkan halaman form login admin
    public function showLoginForm()
    {
        return view('admin.login');
    }

    // Memproses usaha login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            // Setelah berhasil login, cek rolenya
            if (Auth::user()->role == 'dosen') {
                $request->session()->regenerate();
                return redirect()->intended('/admin/users'); // Redirect ke dashboard admin
            } else {
                // Jika role bukan dosen, logout lagi dan beri pesan error
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun ini tidak memiliki hak akses Admin.',
                ])->onlyInput('email');
            }
        }

        return back()->withErrors([
            'email' => 'Email atau Password salah.',
        ])->onlyInput('email');
    }
}