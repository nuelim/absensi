<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        // Mengambil data dengan paginasi, 10 data per halaman, diurutkan dari yang terbaru
        $mahasiswas = Mahasiswa::latest()->paginate(10);

        // Mengirim data ke view dalam bentuk Paginator
        return view('mahasiswa.index', compact('mahasiswas'));
    }

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
    public function edit(string $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);

        // Tampilkan view edit dan kirim data mahasiswa yang ditemukan
        return view('mahasiswa.edit', compact('mahasiswa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            // Aturan 'unique' di sini sedikit berbeda
            // Kita memberitahu Laravel untuk mengabaikan NIM mahasiswa saat ini (dengan ID $id)
            // saat memeriksa keunikan data.
            'nim' => 'required|max:10|unique:mahasiswas,nim,' . $id,
            'nama' => 'required|max:255',
            'jurusan' => 'required',
        ]);

        // Cari mahasiswa yang akan diupdate
        $mahasiswa = Mahasiswa::findOrFail($id);

        // Update data di database
        $mahasiswa->update($request->all());

        // Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);

        // Hapus data
        $mahasiswa->delete();

        // Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil dihapus.');
    }

    // Tambahkan 2 method ini di dalam class MahasiswaController

    public function showDaftarkanWajah(Mahasiswa $mahasiswa)
    {
        // Cukup tampilkan view dengan data mahasiswa yang bersangkutan
        return view('mahasiswa.daftarkan-wajah', compact('mahasiswa'));
    }

    public function simpanWajah(Request $request, Mahasiswa $mahasiswa)
    {
        // Validasi untuk memastikan descriptor tidak kosong
        $request->validate([
            'face_descriptor' => 'required'
        ]);

        // Update kolom face_descriptor pada mahasiswa yang bersangkutan
        $mahasiswa->update([
            'face_descriptor' => $request->face_descriptor
        ]);

        // Jangan lupa tambahkan 'face_descriptor' ke $fillable di Model Mahasiswa
        // protected $fillable = ['nim', 'nama', 'jurusan', 'face_descriptor'];

        // Kirim respons JSON kembali ke JavaScript
        return response()->json(['success' => true, 'message' => 'Wajah berhasil didaftarkan!']);
    }
}
