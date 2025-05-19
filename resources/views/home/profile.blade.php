@extends('layouts.app')

@section('title', 'Home - MyApp')
@section('content')
    <div class="container mt-4">
        <h4 class="mb-3">Profil Saya</h4>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        @if ($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" class="img-thumbnail" width="200">
                        @else
                            <img src="https://via.placeholder.com/200" class="img-thumbnail" width="200">
                        @endif
                        <p class="mt-3">
                            <span class="badge {{ $user->status == 1 ? 'bg-success' : 'bg-danger' }}">
                                {{ $user->status == 1 ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-8">
                        <table class="table table-borderless">
                            <tr>
                                <th>Nama</th>
                                <td>{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th>Telepon</th>
                                <td>{{ $user->phone ?? '-' }}</td>
                            </tr>
                        </table>

                        <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                            <i class="mdi mdi-account-edit"></i> Edit Profil
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Profil Modal -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('penyewa.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="mdi mdi-account-edit"></i> Edit Profil</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" class="form-control" name="name" value="{{ $user->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Telepon</label>
                            <input type="text" class="form-control" name="phone" value="{{ $user->phone }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Foto Profil (Avatar)</label>
                            <input type="file" class="form-control" name="avatar">
                            <small class="text-muted">Biarkan kosong jika tidak ingin mengubah.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
