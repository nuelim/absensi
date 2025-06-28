@extends('layouts.app') @section('content')
<div class="container">
    <h1>Manajemen Role User</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role Saat Ini</th>
                        <th width="250px">Ubah Role</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td><span class="badge bg-primary">{{ $user->role }}</span></td>
                        <td>
                            <form action="{{ url('/admin/users/' . $user->id . '/update-role') }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="input-group">
                                    <select name="role" class="form-select">
                                        <option value="mahasiswa" {{ $user->role == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                                        <option value="dosen" {{ $user->role == 'dosen' ? 'selected' : '' }}>Dosen / Admin</option>
                                    </select>
                                    <button class="btn btn-outline-primary" type="submit">Update</button>
                                </div>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection