@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container">
    {{-- Baris untuk Judul dan Sambutan --}}
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="h4">Dashboard</h2>
            <div class="alert alert-success" role="alert">
                <h4 class="alert-heading">Selamat Datang, {{ Auth::user()->name }}!</h4>
                <p> Anda telah berhasil login ke dalam sistem absensi mahasiswa. Silakan gunakan menu navigasi atau pintasan di bawah ini untuk mengelola data.</p>
            </div>
        </div>
    </div>

    {{-- Baris untuk Kartu Menu Pintasan --}}
    <div class="row">
        {{-- Kartu Data Mahasiswa --}}
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fa-solid fa-users fa-3x text-primary mb-3"></i>
                    <h5 class="card-title">Data Mahasiswa</h5>
                    <p class="card-text">Kelola data mahasiswa, tambah, edit, atau hapus.</p>
                    <a href="{{ route('mahasiswa.index') }}" class="btn btn-primary">Buka</a>
                </div>
            </div>
        </div>

        {{-- Kartu Data Mata Kuliah --}}
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fa-solid fa-book fa-3x text-success mb-3"></i>
                    <h5 class="card-title">Data Mata Kuliah</h5>
                    <p class="card-text">Kelola data mata kuliah yang tersedia.</p>
                    <a href="{{ route('matakuliah.index') }}" class="btn btn-success">Buka</a>
                </div>
            </div>
        </div>
        
        {{-- Kartu Ambil Absen --}}
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fa-solid fa-camera fa-3x text-warning mb-3"></i>
                    <h5 class="card-title">Ambil Absensi</h5>
                    <p class="card-text">Mulai sesi absensi baru dengan pengenalan wajah.</p>
                    <a href="{{ route('absensi.create') }}" class="btn btn-warning">Buka</a>
                </div>
            </div>
        </div>

        {{-- Kartu Laporan Absensi --}}
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fa-solid fa-chart-line fa-3x text-info mb-3"></i>
                    <h5 class="card-title">Laporan Absensi</h5>
                    <p class="card-text">Lihat rekapitulasi dan riwayat kehadiran mahasiswa.</p>
                    <a href="{{ route('absensi.index') }}" class="btn btn-info">Buka</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection