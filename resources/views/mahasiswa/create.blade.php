@extends('layouts.app')

@section('title', 'Tambah Mahasiswa')

@section('content')
    <h2>Tambah Data Mahasiswa</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('mahasiswa.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="nim" class="form-label">NIMM</label>
            <input type="text" class="form-control" id="nim" name="nim">
        </div>
        <div class="mb-3">
            <label for="nama" class="form-label">Nama</label>
            <input type="text" class="form-control" id="nama" name="nama">
        </div>
        <div class="mb-3">
            <label for="jurusan" class="form-label">Jurusan</label>
            <input type="text" class="form-control" id="jurusan" name="jurusan">
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
@endsection