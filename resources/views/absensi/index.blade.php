@extends('layouts.app')

@section('title', 'Laporan Absensi')

@section('content')
    <div class="container">
        <h2 class="mb-4">Laporan Absensi</h2>

        {{-- Form untuk Pencarian Berdasarkan Tanggal --}}
        <div class="card mb-4">
            <div class="card-header">
                <strong>Pencarian Berdasarkan Tanggal</strong>
            </div>
            <div class="card-body">
                <form action="{{ route('absensi.index') }}" method="GET">
                    <div class="row align-items-end">
                        <div class="col-md-4">
                            <label for="tanggal" class="form-label">Pilih Tanggal</label>
                            <input type="date" class="form-control" name="tanggal" id="tanggal" value="{{ request('tanggal') }}">
                        </div>
                        <div class="col-md-auto">
                            <button type="submit" class="btn btn-primary">Cari</button>
                            <a href="{{ route('absensi.index') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Notifikasi Sukses --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Tabel Utama untuk Menampilkan Data Absensi --}}
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Nama Mahasiswa</th>
                                <th scope="col">NIM</th>
                                <th scope="col">Mata Kuliah</th>
                                <th scope="col" class="text-center">Status Kehadiran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($absensi as $item)
                            <tr>
                                <th scope="row">{{ $loop->iteration + $absensi->firstItem() - 1 }}</th>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal_absensi)->translatedFormat('d F Y') }}</td>
                                <td>{{ $item->mahasiswa->nama }}</td>
                                <td>{{ $item->mahasiswa->nim }}</td>
                                <td>{{ $item->mataKuliah->nama_mk }}</td>
                                <td class="text-center">
                                    @if($item->status == 'Hadir')
                                        <span class="badge bg-success">{{ $item->status }}</span>
                                    @elseif($item->status == 'Sakit')
                                        <span class="badge bg-warning text-dark">{{ $item->status }}</span>
                                    @elseif($item->status == 'Izin')
                                        <span class="badge bg-info text-dark">{{ $item->status }}</span>
                                    @else
                                        <span class="badge bg-danger">{{ $item->status }}</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">
                                    @if(request('tanggal'))
                                        Data absensi untuk tanggal yang dipilih tidak ditemukan.
                                    @else
                                        Data absensi masih kosong.
                                    @endif
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Link Paginasi --}}
                <div class="d-flex justify-content-center mt-3">
                    {{ $absensi->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection