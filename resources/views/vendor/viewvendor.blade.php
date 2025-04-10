<x-layout bodyClass="g-sidenav-show bg-gray-200">
    <x-navbars.sidebar activePage="vendor"></x-navbars.sidebar>

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-navbars.navs.auth titlePage="Vendor Profile"></x-navbars.navs.auth>

        <div class="container-fluid py-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Profil Vendor</h5>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                        Edit Profil
                    </button>
                </div>
                <div class="card-body">
                    <p><strong>Nama:</strong> {{ $vendor->name }}</p>
                    <p><strong>Email:</strong> {{ $vendor->email }}</p>

                    <button class="btn btn-warning btn-sm mt-3" data-bs-toggle="modal"
                        data-bs-target="#changePasswordModal">
                        Ubah Password
                    </button>
                </div>
            </div>

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
    </main>

    <!-- Modal Edit Profil -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('vendor.updateProfile') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editProfileModalLabel">Edit Profil</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input type="text" name="name" id="name" class="form-control"
                                value="{{ $vendor->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control"
                                value="{{ $vendor->email }}" required>
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

    <!-- Modal Ubah Password -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('vendor.updatePassword') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="changePasswordModalLabel">Ubah Password</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Password Saat Ini</label>
                            <input type="password" name="current_password" id="current_password" class="form-control"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">Password Baru</label>
                            <input type="password" name="new_password" id="new_password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                                class="form-control" required>
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
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
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
</x-layout>
