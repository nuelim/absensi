@extends('layouts.app')

@section('title', 'Tambah Mata Kuliah')

@section('content')
    <h2>Tambah Data Mata Kuliah</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('matakuliah.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="kode_mk" class="form-label">Kode Mata Kuliah</label>
            <input type="text" class="form-control" id="kode_mk" name="kode_mk">
        </div>
        <div class="mb-3">
            <label for="nama_mk" class="form-label">Nama Mata Kuliah</label>
            <input type="text" class="form-control" id="nama_mk" name="nama_mk">
        </div>
        <div class="mb-3">
            <label for="sks" class="form-label">SKS</label>
            <input type="number" class="form-control" id="sks" name="sks">
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
@endsection