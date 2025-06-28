<?php

namespace App\Http\Controllers;

use App\Models\MataKuliah; // <-- IMPORT MODELNYA
use Illuminate\Http\Request;

class MataKuliahController extends Controller
{
    /**
     * Menampilkan daftar semua mata kuliah.
     */
    public function index()
    {
        $mataKuliahs = MataKuliah::all(); // <-- Ambil semua data
        return view('matakuliah.index', ['mataKuliahs' => $mataKuliahs]); // <-- Kirim data ke view
    }

    /**
     * Menampilkan form untuk menambah mata kuliah baru.
     */
    public function create()
    {
        return view('matakuliah.create'); // <-- Cukup tampilkan view form
    }

    /**
     * Menyimpan mata kuliah baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi input dari user
        $request->validate([
            'kode_mk' => 'required|unique:mata_kuliahs|max:10',
            'nama_mk' => 'required|max:255',
            'sks' => 'required|integer',
        ]);

        // Jika validasi berhasil, simpan data menggunakan metode create()
        MataKuliah::create($request->all());

        // Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('matakuliah.index')
                         ->with('success', 'Data mata kuliah berhasil ditambahkan.');
    }

    // ... method show, edit, update, destroy bisa diisi nanti ...
}