@extends('layouts.app')

@section('title', 'Data Mahasiswa')

@section('content')
    <div class="container">
        <h2 class="mb-4">Data Mahasiswa</h2>

        <a href="{{ route('mahasiswa.create') }}" class="btn btn-primary mb-3">
            <i class="fas fa-plus"></i> Tambah Mahasiswa Baru
        </a>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">NIM</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Jurusan</th>
                                <th scope="col" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($mahasiswas as $mhs)
                                <tr>
                                    {{-- Nomor urut yang benar untuk paginasi --}}
                                    <th scope="row">{{ $loop->iteration + $mahasiswas->firstItem() - 1 }}</th>
                                    <td>{{ $mhs->nim }}</td>
                                    <td>{{ $mhs->nama }}</td>
                                    <td>{{ $mhs->jurusan }}</td>
                                    <td class="text-center" style="min-width: 260px;">
                                        {{-- Tombol baru untuk mendaftarkan wajah --}}
                                        <a href="{{ route('mahasiswa.daftarkan-wajah', $mhs->id) }}" class="btn btn-info btn-sm">
                                            <i class="fa-solid fa-camera"></i> Wajah
                                        </a>
                                        
                                        {{-- Tombol Edit --}}
                                        <a href="{{ route('mahasiswa.edit', $mhs->id) }}" class="btn btn-warning btn-sm">
                                            <i class="fa-solid fa-pen-to-square"></i> Edit
                                        </a>

                                        {{-- Form untuk Tombol Hapus --}}
                                        <form action="{{ route('mahasiswa.destroy', $mhs->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                <i class="fa-solid fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Data mahasiswa masih kosong.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Link Paginasi --}}
                <div class="d-flex justify-content-center mt-3">
                    {{ $mahasiswas->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection