<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Absensi')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }

        .wrapper {
            display: flex;
            flex-grow: 1;
        }

        .sidebar {
            width: 260px;
            background-color: #0d6efd;
            color: white;
            min-height: 100vh;
        }

        .sidebar .nav-link {
            color: white;
            padding: 12px 20px;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: #0b5ed7;
        }

        .sidebar .brand {
            font-size: 1.25rem;
            font-weight: bold;
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
        }

        .sidebar .brand img {
            height: 35px;
            margin-right: 10px;
        }

        .main-content {
            flex-grow: 1;
            padding: 30px;
            background-color: #f8f9fa;
        }

        .profile-img {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 8px;
        }
    </style>
</head>
<body>
    {{-- Header --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="/dashboard">
                <img src="{{ asset('images/logo.jpg') }}" alt="Logo" style="height: 35px; margin-right: 10px;">
                Sistem Absensi
            </a>
            <div class="d-flex ms-auto">
                @auth
                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle text-white d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ Auth::user()->foto ? asset('storage/' . Auth::user()->foto) : asset('images/default-profile.png') }}" class="profile-img" alt="Foto Profil">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fa fa-sign-out-alt me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <div class="wrapper">
        {{-- Sidebar --}}
        <div class="sidebar d-flex flex-column">
            
            @auth
            <nav class="nav flex-column">
                <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="/dashboard">
                    <i class="fa fa-home me-2"></i>Dashboard
                </a>
                <a class="nav-link {{ request()->is('mahasiswa*') ? 'active' : '' }}" href="{{ route('mahasiswa.index') }}">
                    <i class="fa fa-users me-2"></i>Data Mahasiswa
                </a>
                <a class="nav-link {{ request()->is('matakuliah*') ? 'active' : '' }}" href="{{ route('matakuliah.index') }}">
                    <i class="fa fa-book me-2"></i>Data Mata Kuliah
                </a>
                <a class="nav-link {{ request()->is('absensi/create') ? 'active' : '' }}" href="{{ route('absensi.create') }}">
                    <i class="fa fa-calendar-check me-2"></i>Ambil Absen
                </a>
                <a class="nav-link {{ request()->is('absensi') ? 'active' : '' }}" href="{{ route('absensi.index') }}">
                    <i class="fa fa-file-alt me-2"></i>Laporan Absensi
                </a>
            </nav>
            @endauth
        </div>

        {{-- Main Content --}}
        <div class="main-content">
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
