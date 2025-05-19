@extends('vendor.layout.master')

@push('plugin-styles')
    <link rel="stylesheet" href="{{ asset('admin/assets/plugins/plugin.css') }}">
@endpush

@section('content')
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Profil Vendor</h5>
                <div>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                        Edit Profil
                    </button>
                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#changeAvatarModal">
                        Ganti Foto
                    </button>
                </div>
            </div>
            <div class="card-body text-center">
                <div class="profile-image mb-3">
                    <img src="{{ $vendor->avatar ? asset('storage/avatar/' . $vendor->avatar) : asset('admin/assets/images/faces/face8.jpg') }}"
                        alt="Foto Profil" class="rounded-circle" width="120" height="120">
                </div>
                <p><strong>Nama:</strong> {{ $vendor->name }}</p>
                <p><strong>Email:</strong> {{ $vendor->email }}</p>
                <p><strong>Telepon:</strong> {{ $vendor->phone ?? '-' }}</p>
                <p><strong>Status:</strong>
                    @if ($vendor->is_active)
                        <span class="badge bg-success">Aktif</span>
                    @else
                        <span class="badge bg-danger">Tidak Aktif</span>
                    @endif
                </p>
            </div>
        </div>

        {{-- Data Rekening (Biarkan) --}}
        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Data Rekening</h5>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editRekeningModal">
                    {{ $rekening ? 'Edit Rekening' : 'Tambah Rekening' }}
                </button>
            </div>
            <div class="card-body">
                @if ($rekening)
                    <p><strong>Nama Bank:</strong> {{ $rekening->nama_bank }}</p>
                    <p><strong>Nomor Rekening:</strong> {{ $rekening->rekening }}</p>
                    <p><strong>Nama Pemilik:</strong> {{ $rekening->nama }}</p>
                    <p><strong>Email:</strong> {{ $rekening->email }}</p>
                @else
                    <p>Belum ada data rekening.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Edit Profil -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('vendor.updateProfile') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editProfileModalLabel">Edit Profil</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" name="name" class="form-control" value="{{ $vendor->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ $vendor->email }}"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Telepon</label>
                            <input type="text" name="phone" class="form-control" value="{{ $vendor->phone }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Ganti Foto -->
    <div class="modal fade" id="changeAvatarModal" tabindex="-1" aria-labelledby="changeAvatarModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('vendor.updateAvatar') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="changeAvatarModalLabel">Ganti Foto Profil</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Pilih Foto</label>
                            <input type="file" name="avatar" class="form-control" accept="image/*" required>
                            <small class="text-muted">Max: 2MB, Format: jpg/jpeg/png</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Upload Foto</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Rekening (Biarkan) -->
    <!-- Modal Edit Rekening -->
    <div class="modal fade" id="editRekeningModal" tabindex="-1" aria-labelledby="editRekeningModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('vendor.updateRekening') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editRekeningModalLabel">
                            {{ $rekening ? 'Edit Rekening' : 'Tambah Rekening' }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama_bank" class="form-label">Nama Bank</label>
                            <select name="nama_bank" id="nama_bank" class="form-control" required>
                                <option value="" disabled
                                    {{ empty($rekening) || empty($rekening->nama_bank) ? 'selected' : '' }}>Pilih Bank
                                </option>
                                <option value="BRI" {{ $rekening?->nama_bank == 'BRI' ? 'selected' : '' }}>BRI
                                </option>
                                <option value="BNI" {{ $rekening?->nama_bank == 'BNI' ? 'selected' : '' }}>BNI
                                </option>
                                <option value="BCA" {{ $rekening?->nama_bank == 'BCA' ? 'selected' : '' }}>BCA
                                </option>
                                <option value="JAGO" {{ $rekening?->nama_bank == 'JAGO' ? 'selected' : '' }}>JAGO
                                </option>
                                <option value="MANDIRI" {{ $rekening?->nama_bank == 'MANDIRI' ? 'selected' : '' }}>
                                    MANDIRI</option>
                                <option value="BTN" {{ $rekening?->nama_bank == 'BTN' ? 'selected' : '' }}>BTN
                                </option>
                                <option value="DKI" {{ $rekening?->nama_bank == 'DKI' ? 'selected' : '' }}>DKI
                                </option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="rekening" class="form-label">Nomor Rekening</label>
                            <input type="text" name="rekening" id="rekening" class="form-control"
                                value="{{ $rekening->rekening ?? '' }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Pemilik</label>
                            <input type="text" name="nama" id="nama" class="form-control"
                                value="{{ $rekening->nama ?? '' }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control"
                                value="{{ $rekening->email ?? '' }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('plugin-scripts')
    <script src="{{ asset('admin/assets/plugins/chartjs/chart.min.js') }}"></script>
    <script src="{{ asset('admin/assets/plugins/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
@endpush

@push('custom-scripts')
    <script src="{{ asset('admin/assets/js/dashboard.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#28a745'
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal menyimpan!',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                confirmButtonColor: '#d33'
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan!',
                text: '{{ session('error') }}',
                confirmButtonColor: '#d33'
            });
        </script>
    @endif
@endpush
