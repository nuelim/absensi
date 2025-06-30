@extends('layouts.app')

@section('title', 'Data Mahasiswa')

@section('content')
    <div class="container">
        <h2 class="mb-4">Data Mahasiswa</h2>

        

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
                                <th scope="col">Email</th>
                                <th scope="col" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                                @foreach ($mahasiswas as $index => $mahasiswa)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="px-6 py-4">{{ $index + 1 }}</td>
                                    {{-- Tampilkan nim dari tabel users --}}
                                    <td class="px-6 py-4">{{ $mahasiswa->nim }}</td>
                                    {{-- Tampilkan name dari tabel users --}}
                                    <td class="px-6 py-4">{{ $mahasiswa->name }}</td>
                                    {{-- Tampilkan email dari tabel users --}}
                                    <td class="px-6 py-4">{{ $mahasiswa->email }}</td>
                                    <td class="px-6 py-4">
                                        {{-- PERINGATAN: Tombol-tombol ini perlu perbaikan di langkah selanjutnya --}}
                                        <form action="{{ route('mahasiswa.destroy', $mahasiswa->id) }}" method="POST">
                                            <a href="{{ route('mahasiswa.daftarkan-wajah', $mahasiswa->id) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Daftarkan Wajah</a>
                                            <a href="{{ route('mahasiswa.edit', $mahasiswa->id) }}" class="ml-2 font-medium text-yellow-400 dark:text-yellow-300 hover:underline">Edit</a>
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="ml-2 font-medium text-red-600 dark:text-red-500 hover:underline">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                    </table>
                </div>

                
            </div>
        </div>
    </div>
@endsection