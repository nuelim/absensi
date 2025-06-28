@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<style>
    body {
        background-image: url('{{ asset('images/logo.jpg') }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-attachment: fixed;
        height: 100vh;
        overflow: hidden;
    }
    .login-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .login-card {
        width: 100%;
        max-width: 450px;
        background-color: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(5px);
    }
</style>

<div class="login-overlay">
    <div class="card shadow-lg login-card">
        <div class="card-header text-center h4">Login Sistem Absensi</div>
        <div class="card-body p-4">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Alamat Email</label>
                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus>
                    
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                </div>

                <div class="text-center mt-3">
                    <small>Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a></small>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection