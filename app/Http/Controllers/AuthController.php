<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    // --- REGISTRASI ---

    /**
     * Menampilkan form registrasi.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Memproses data dari form registrasi.
     */
    public function register(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'nim' => 'required|string|max:255|unique:users,nim', // Tambahkan validasi untuk nim
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            //'role' => ['required', Rule::in(['mahasiswa', 'dosen'])], // <-- Tambahkan validasi untuk role
        ]);

        // 2. Buat user baru dan hash passwordnya
        $user = User::create([
            'name' => $request->name,
            'nim' => $request->nim, // Tambahkan nim saat membuat user
            'email' => $request->email,
            'role' => 'mahasiswa', // Atur role menjadi 'mahasiswa' secara default
            'password' => Hash::make($request->password), // WAJIB: Selalu hash password!

        ]);

        // 3. Login user yang baru dibuat
        Auth::login($user);

        // 4. Redirect ke halaman dashboard
        return redirect('/dashboard');
        return redirect('/login')->with('success', 'Registration successful! Please login.');
    }


    // --- LOGIN ---

    /**
     * Menampilkan form login.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Memproses data dari form login.
     */
    public function login(Request $request)
    {
        // 1. Validasi input
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Coba lakukan autentikasi
        if (Auth::attempt($credentials)) {
            // Jika berhasil, regenerate session untuk keamanan
            $request->session()->regenerate();
            
            // Redirect ke halaman yang dituju sebelumnya atau ke dashboard
            return redirect()->intended('/dashboard');
        }

        // 3. Jika gagal, kembali ke halaman login dengan pesan error
        return back()->withErrors([
            'email' => 'Email atau Password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }


    // --- LOGOUT ---
    
    /**
     * Memproses logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // Invalidate session dan regenerate token untuk keamanan
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login'); // Redirect ke halaman utama
    }
}