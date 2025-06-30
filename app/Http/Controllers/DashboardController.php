<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Penting untuk mendapatkan data user

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard utama.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Mengambil data user yang sedang login
        $user = Auth::user();

        // Mengirim data user ke view 'dashboard'
        return view('dashboard', ['user' => $user]);
    }
}