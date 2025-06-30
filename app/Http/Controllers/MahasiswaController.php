<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Mahasiswa;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // KEMUNGKINAN KODE LAMA ANDA
    // KODE BARU YANG BENAR
    public function index()
    {
        // Tetap seperti sebelumnya, mengambil user dengan role mahasiswa
        $mahasiswas = User::where('role', 'mahasiswa')->latest()->get();
        return view('mahasiswa.index', compact('mahasiswas'));
    }
    /**
     * Show the form for editing the specified resource.
     * Parameter $mahasiswa sekarang adalah instance dari User karena Route-Model Binding.
     */

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('mahasiswa.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nim' => 'required|unique:mahasiswas|max:10',
            'nama' => 'required|max:255',
            'jurusan' => 'required',
        ]);

        Mahasiswa::create($request->all()); // Simpan ke database

        return redirect()->route('mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $mahasiswa)
    {
        // Kirim data user ke view edit
        return view('mahasiswa.edit', ['user' => $mahasiswa]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $mahasiswa)
    {
        $request->validate([
            // Pastikan NIM unik kecuali untuk user ini sendiri
            'nim' => 'required|unique:users,nim,' . $mahasiswa->id,
            'name' => 'required',
        ]);

        $mahasiswa->update([
            'nim' => $request->nim,
            'name' => $request->name,
        ]);

        return redirect()->route('mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $mahasiswa)
    {
        $mahasiswa->delete();

        return redirect()->route('mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil dihapus.');
    }

    // Tambahkan 2 method ini di dalam class MahasiswaController

    public function showDaftarkanWajah($id)
    {
        $user = User::findOrFail($id);
        return view('mahasiswa.daftarkan-wajah', compact('user'));
    }
    /**
     * Menyimpan face descriptor ke user yang sesuai.
     */

    public function simpanWajah(Request $request, $id)
    {
        $request->validate([
            'face_descriptor' => 'required',
        ]);

        $user = User::findOrFail($id);
        $user->face_descriptor = json_encode($request->input('face_descriptor'));
        $user->save();

        return response()->json([
        'success' => true,
        'message' => 'Wajah berhasil didaftarkan.'
    ]);
    }
    
    // Metode 'create' dan 'store' tidak lagi relevan karena mahasiswa
    // dibuat melalui halaman registrasi. Anda bisa menghapusnya jika mau.
    // Metode 'show' juga bisa dihapus jika tidak digunakan.
}
