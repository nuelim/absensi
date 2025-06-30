<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- PASTIKAN USE INI ADA
use Illuminate\Support\Facades\DB;
use App\Models\User;

class AbsensiController extends Controller
{
    public function create()
    {
        $mataKuliahs = MataKuliah::all();
        
        // Ambil semua user dengan role mahasiswa yang sudah mendaftarkan wajah
        $mahasiswas = User::where('role', 'mahasiswa')->whereNotNull('face_descriptor')->get();

        return view('absensi.create', compact('mataKuliahs', 'mahasiswas'));
    }


    public function store(Request $request)
    {
        $request->validate([
            // Ubah validasi ke tabel users
            'mahasiswa_id' => 'required|exists:users,id', 
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
        ]);

        // Cek apakah mahasiswa sudah absen hari ini untuk matkul yang sama
        $alreadyExists = Absensi::where('user_id', $request->mahasiswa_id)
            ->where('mata_kuliah_id', $request->mata_kuliah_id)
            ->whereDate('created_at', today())
            ->exists();

        if ($alreadyExists) {
            return response()->json(['status' => 'already_exists']);
        }

        // Simpan absensi dengan user_id
        Absensi::create([
            'user_id' => $request->mahasiswa_id,
            'mata_kuliah_id' => $request->mata_kuliah_id,
        ]);

        return response()->json(['status' => 'success']);
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Absensi::with(['user', 'mataKuliah']);

        // Filter berdasarkan tanggal jika ada
        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        // Jika user adalah mahasiswa, hanya tampilkan absensinya sendiri
        if ($user->role == 'mahasiswa') {
            $query->where('user_id', $user->id);
        }

        $absensis = $query->latest()->get();

        return view('absensi.index', compact('absensis'));
    }


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
