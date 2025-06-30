@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<style>
    /* Menghilangkan margin dan padding default dari body */
    body {
        margin: 0;
        padding: 0;
        height: 100vh;
        overflow: hidden; /* Mencegah scroll */
    }

    /* Container utama yang mengatur layout dua kolom */
    .login-container {
        display: flex;
        height: 100vh;
        width: 100%;
    }

    /* Kolom kiri untuk gambar latar belakang */
    .image-side {
        flex: 1;
        background: url('images/background-login.jpg') no-repeat center center;
        background-size: cover;
        /* Sembunyikan di layar kecil agar form fokus */
        display: none;
    }
    @media (min-width: 768px) {
        .image-side {
            display: block;
            flex-basis: 55%; /* Lebar kolom gambar di layar besar */
        }
    }

    /* Kolom kanan untuk form login */
    .form-side {
        flex: 1;
        /* --- BARIS INI TELAH DIUBAH --- */
        /* Menggunakan gradasi dari warna kulit (#F5E5D5) ke biru muda (#B8D0E8) */
        background: linear-gradient(to bottom, #F5E5D5, #B8D0E8);
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 2rem;
        flex-basis: 45%; /* Lebar kolom form */
    }
    
    .login-form-container {
        width: 100%;
        max-width: 400px;
    }

    /* Styling untuk logo */
    .login-logo {
        text-align: left;
        margin-bottom: 2.5rem;
    }
    .login-logo img {
        height: 40px; /* Sesuaikan tinggi logo */
    }
    /* Jika Anda ingin menggunakan teks seperti di contoh */
    .havetra-logo {
        font-size: 28px;
        font-weight: bold;
        color: #333;
        display: flex;
        align-items: center;
    }
    .havetra-logo .icon {
        display: inline-block;
        width: 32px;
        height: 32px;
        background-color: #4A90E2; /* Warna biru dari logo */
        margin-right: 10px;
        /* Anda bisa menggunakan background-image untuk ikon asli */
        mask: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm-1 3h2v2h-2V5zm0 3h2v2h-2V8zm0 3h2v2h-2v-2zm3-5h2v2h-2V6zM8 9h2v2H8V9zm5 0h2v2h-2V9z"/></svg>') no-repeat center center;
    }

    /* Styling untuk input fields */
    .form-control {
        border-radius: 8px; /* Sudut lebih tumpul */
        border: 1px solid #DDE2E8;
        padding: 0.75rem 1rem;
        height: 50px; /* Membuat input lebih tinggi */
    }
    .form-control:focus {
        border-color: #4A90E2;
        box-shadow: 0 0 0 0.25rem rgba(74, 144, 226, 0.25);
    }
    .form-label {
        font-weight: 500;
        color: #555;
    }

    /* Styling untuk tombol login */
    .btn-login {
        background-color: #4A90E2; /* Warna biru tombol */
        border-color: #4A90E2;
        border-radius: 8px;
        padding: 0.75rem;
        font-weight: bold;
        width: 100%;
    }
    .btn-login:hover {
        background-color: #357ABD;
        border-color: #357ABD;
    }

    /* Styling untuk link di bawah */
    .login-footer-link {
        text-align: right;
        margin-top: 1rem;
    }
    .login-footer-link a {
        font-size: 0.9rem;
        color: #4A90E2;
        text-decoration: none;
    }
    .login-footer-link a:hover {
        text-decoration: underline;
    }

</style>

<div class="login-container">
    {{-- Kolom Kiri - Gambar --}}
    <div class="image-side"></div>

    {{-- Kolom Kanan - Form --}}
    <div class="form-side">
        <div class="login-form-container">
            
            {{-- Logo --}}
            <div class="login-logo">
                {{-- Ganti dengan tag <img> untuk logo Anda jika ada --}}
                {{-- <img src="{{ asset('path/to/your/havetra-logo.png') }}" alt="Havetra Logo"> --}}
                <div class="havetra-logo">
                    <img src="{{ asset('images/logo.jpg') }}" alt="Havetra Logo"> </img>
                    Havetra
                </div>
            </div>

            {{-- Form Login (Konten Asli Anda) --}}
            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                {{-- Email --}}
                <div class="mb-3">
                    <label for="email" class="form-label">Alamat Email</label>
                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus placeholder="nama@email.com">
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control" required placeholder="Masukkan password">
                </div>

                {{-- Tombol Submit --}}
                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-primary btn-login">Login</button>
                </div>

                
                {{-- Link untuk registrasi --}}
                <div class="text-center mt-4">
                    <small>Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a></small>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection