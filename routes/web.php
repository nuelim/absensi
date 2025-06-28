<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AuthController;

// Halaman utama
Route::get('/', function () {
    return view('welcome');
});

// Rute untuk Mahasiswa (otomatis membuat URL untuk index, create, store, dll.)
Route::resource('mahasiswa', MahasiswaController::class);

// Rute untuk Mata Kuliah
Route::resource('matakuliah', MataKuliahController::class);

// Rute untuk Absensi
Route::get('/absensi/create', [AbsensiController::class, 'create'])->name('absensi.create');
Route::post('/absensi', [AbsensiController::class, 'store'])->name('absensi.store');
Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
Route::get('/mahasiswa/{mahasiswa}/daftarkan-wajah', [MahasiswaController::class, 'showDaftarkanWajah'])->name('mahasiswa.daftarkan-wajah');

// Route untuk menyimpan data wajah (menerima request dari JavaScript)
Route::post('/mahasiswa/{mahasiswa}/simpan-wajah', [MahasiswaController::class, 'simpanWajah'])->name('mahasiswa.simpan-wajah');
Route::post('/absensi/absen-otomatis', [AbsensiController::class, 'absenOtomatis'])->name('absensi.otomatis');

// Route untuk Tamu (yang belum login)
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Route untuk Pengguna yang Sudah Login
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::get('/dashboard', function() {
        return view('dashboard');
    })->name('dashboard');
    
    // Pindahkan semua route aplikasi Anda yang lain ke dalam grup ini
    // Contoh:
    // Route::resource('mahasiswa', MahasiswaController::class);
    // ... dan seterusnya ...
});