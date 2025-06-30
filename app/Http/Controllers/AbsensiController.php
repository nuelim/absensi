<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- PASTIKAN USE INI ADA
use Illuminate\Support\Facades\DB;

class AbsensiController extends Controller
{
    public function create()
    {
        // Mengambil SEMUA mahasiswa untuk ditampilkan di tabel form
        $semuaMahasiswa = Mahasiswa::orderBy('nama', 'asc')->get();

        // Mengambil data mahasiswa yang SUDAH mendaftarkan wajah saja
        // Kita hanya butuh id, nama, dan descriptornya untuk JavaScript
        $mahasiswaTerdaftar = Mahasiswa::whereNotNull('face_descriptor')
            ->get(['id', 'nama', 'face_descriptor']);

        $mataKuliahs = MataKuliah::all();

        // Kirim semua data yang dibutuhkan ke view
        return view('absensi.create', compact('semuaMahasiswa', 'mataKuliahs', 'mahasiswaTerdaftar'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mata_kuliah_id' => 'required',
            'tanggal_absensi' => 'required|date',
            'absensi' => 'required|array'
        ]);

        foreach ($request->absensi as $mahasiswa_id => $status) {
            Absensi::create([
                'mahasiswa_id' => $mahasiswa_id,
                'mata_kuliah_id' => $request->mata_kuliah_id,
                'tanggal_absensi' => $request->tanggal_absensi,
                'status' => $status
            ]);
        }

        return redirect()->route('absensi.index')->with('success', 'Absensi berhasil disimpan.');
    }

    public function index(Request $request)
    {
        // Ambil user yang sedang login
        $user = Auth::user(); 

        // Mulai query dasar
        $query = Absensi::with(['mahasiswa', 'mataKuliah']);

        // Periksa apakah ada input tanggal di request
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_absensi', $request->tanggal);
        }
        
        // =============================================
        // TAMBAHKAN BLOK LOGIKA PERAN PENGGUNA DI SINI
        // =============================================
        if ($user->role == 'mahasiswa') {
            // Jika user adalah mahasiswa, filter berdasarkan namanya
            $mahasiswa = Mahasiswa::where('nama', $user->name)->first();

            if ($mahasiswa) {
                // Tambahkan kondisi where untuk memfilter berdasarkan ID mahasiswa yang cocok
                $query->where('mahasiswa_id', $mahasiswa->id);
            } else {
                // Jika tidak ada mahasiswa yang cocok dengan nama user,
                // pastikan query tidak mengembalikan hasil apa pun.
                $query->where('mahasiswa_id', -1); 
            }
        }
        // Jika user adalah 'dosen', tidak ada filter tambahan yang diterapkan,
        // sehingga semua data akan ditampilkan.
        // =============================================
        // AKHIR BLOK LOGIKA
        // =============================================


        // Eksekusi query setelah semua kondisi ditambahkan
        $absensi = $query->latest()->paginate(10)->appends($request->query());

        // Kirim data ke view
        return view('absensi.index', compact('absensi'));
    }
    // ==========================================================
    // AKHIR DARI METHOD INDEX YANG DIUBAH
    // ==========================================================
    

    public function absenOtomatis(Request $request)
    {
        // 1. Validasi data yang dikirim dari JavaScript
        $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswas,id',
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'tanggal_absensi' => 'required|date',
        ]);

        // 2. Cek apakah mahasiswa ini sudah diabsen untuk matkul dan tanggal yang sama
        // Ini PENTING untuk mencegah data ganda jika wajah terdeteksi berkali-kali.
        $absensiAda = Absensi::where('mahasiswa_id', $request->mahasiswa_id)
            ->where('mata_kuliah_id', $request->mata_kuliah_id)
            ->whereDate('tanggal_absensi', $request->tanggal_absensi)
            ->first();

        if ($absensiAda) {
            // Jika sudah ada, kirim respons bahwa sudah diabsen
            return response()->json([
                'success' => true,
                'message' => 'Mahasiswa sudah diabsen sebelumnya.'
            ]);
        }

        // 3. Jika belum ada, buat record absensi baru
        Absensi::create([
            'mahasiswa_id' => $request->mahasiswa_id,
            'mata_kuliah_id' => $request->mata_kuliah_id,
            'tanggal_absensi' => $request->tanggal_absensi,
            'status' => 'Hadir' // Langsung set status Hadir
        ]);

        // 4. Kirim respons sukses
        return response()->json([
            'success' => true,
            'message' => 'Absensi berhasil direkam!'
        ]);
    }
}
