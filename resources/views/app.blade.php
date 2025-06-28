<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Absensi')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="/dashboard">
            <img src="{{ asset('images/logo.jpg') }}" alt="Logo Aplikasi" style="height: 35px; margin-right: 10px;">
            Sistem Absensi Mahasiswa
            </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="{{ route('mahasiswa.index') }}">Data Mahasiswa</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('matakuliah.index') }}">Data Mata Kuliah</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('absensi.create') }}">Ambil Absen</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('absensi.index') }}">Laporan Absensi</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>