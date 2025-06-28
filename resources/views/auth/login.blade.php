@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<style>
    body, html {
        margin: 0;
        padding: 0;
        height: 100%;
    }

    .login-container {
        display: flex;
        height: 100vh;
    }

    .login-left {
        flex: 1;
        background: url('{{ asset('images/background-login.jpg') }}') no-repeat center center;
        background-size: cover;
    }

    .login-right {
        flex: 1;
        background: linear-gradient(to bottom, #f0f4f8, #dbe5f1);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .login-card {
        width: 100%;
        max-width: 400px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        padding: 40px;
    }

    .login-logo {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 30px;
    }

    .login-logo img {
        height: 40px;
        margin-right: 10px;
    }

    .login-logo h1 {
        font-size: 24px;
        font-weight: bold;
        color: #1976d2;
        margin: 0;
    }

    .login-form .form-control {
        border-radius: 6px;
    }

    .btn-login {
        background-color: #1976d2;
        border: none;
        font-weight: bold;
    }

    .btn-login:hover {
        background-color: #125ba1;
    }
</style>

<div class="login-container">
    <div class="login-left"></div>

    <div class="login-right">
        <div class="login-card">
            <div class="login-logo">
                <img src="{{ asset('images/logo.jpg') }}" alt="Logo">
                <h1>Havetra</h1>
            </div>

            <form method="POST" action="{{ route('login') }}" class="login-form">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
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
                    <button type="submit" class="btn btn-login btn-block text-white">Login</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
