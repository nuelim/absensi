@extends('layouts.app')

@section('title', 'Ambil Absen')

@section('content')
    <h2>Form Absensi</h2>

    <form action="{{ route('absensi.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="mata_kuliah_id" class="form-label">Mata Kuliah</label>
            <select name="mata_kuliah_id" id="mata_kuliah_id" class="form-control">
                @foreach($mataKuliahs as $mk)
                    <option value="{{ $mk->id }}">{{ $mk->nama_mk }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="tanggal_absensi" class="form-label">Tanggal</label>
            <input type="date" name="tanggal_absensi" id="tanggal_absensi" class="form-control" value="{{ date('Y-m-d') }}">
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Mahasiswa</th>
                    <th>Status Kehadiran</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mahasiswas as $mhs)
                <tr>
                    <td>{{ $mhs->nama }}</td>
                    <td>
                        <input type="radio" name="absensi[{{ $mhs->id }}]" value="Hadir" checked> Hadir
                        <input type="radio" name="absensi[{{ $mhs->id }}]" value="Sakit"> Sakit
                        <input type="radio" name="absensi[{{ $mhs->id }}]" value="Izin"> Izin
                        <input type="radio" name="absensi[{{ $mhs->id }}]" value="Alpa"> Alpa
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <button type="submit" class="btn btn-primary">Simpan Absensi</button>
    </form>
@endsection