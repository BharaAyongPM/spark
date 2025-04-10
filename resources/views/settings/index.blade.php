<x-layout bodyClass="g-sidenav-show bg-gray-200">
    <x-navbars.sidebar activePage="settings"></x-navbars.sidebar>

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-navbars.navs.auth titlePage="Daftar Lapangan"></x-navbars.navs.auth>

        <div class="container-fluid py-4">
            <div class="container mt-4">
                <h2 class="mb-4">Pengaturan Website</h2>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5><i class="fas fa-cogs"></i> Informasi Umum</h5>
                                <ul class="list-group">
                                    <li class="list-group-item"><strong>Nama Website:</strong> {{ $setting->nama_web }}
                                    </li>
                                    <li class="list-group-item"><strong>Email Support:</strong>
                                        {{ $setting->email_suport }}
                                    </li>
                                    <li class="list-group-item"><strong>WhatsApp Support:</strong>
                                        {{ $setting->wa_suport }}
                                    </li>
                                </ul>
                            </div>

                            <div class="col-md-6">
                                <h5><i class="fas fa-money-bill"></i> Biaya & Fee</h5>
                                <ul class="list-group">
                                    <li class="list-group-item"><strong>Fee Service:</strong>
                                        Rp{{ number_format($setting->fee_service, 0, ',', '.') }}</li>
                                    <li class="list-group-item"><strong>Fee Xendit:</strong>
                                        Rp{{ number_format($setting->fee_xendit, 0, ',', '.') }}</li>
                                    <li class="list-group-item"><strong>Fee Penarikan:</strong>
                                        Rp{{ number_format($setting->fee_penarikan, 0, ',', '.') }}</li>
                                </ul>
                            </div>
                        </div>

                        <div class="mt-4">
                            <h5><i class="fas fa-image"></i> Banner Website</h5>
                            <div class="d-flex">
                                @if ($setting->baner1)
                                    <img src="{{ asset('storage/' . $setting->baner1) }}" class="img-thumbnail me-2"
                                        width="150">
                                @endif
                                @if ($setting->baner2)
                                    <img src="{{ asset('storage/' . $setting->baner2) }}" class="img-thumbnail me-2"
                                        width="150">
                                @endif
                                @if ($setting->baner3)
                                    <img src="{{ asset('storage/' . $setting->baner3) }}" class="img-thumbnail me-2"
                                        width="150">
                                @endif
                            </div>
                        </div>

                        <div class="mt-4">
                            <h5><i class="fas fa-info-circle"></i> Deskripsi Website</h5>
                            <p>{{ $setting->deskripsi }}</p>
                        </div>

                        <button class="btn btn-warning mt-3" data-bs-toggle="modal" data-bs-target="#editSettingModal">
                            <i class="fas fa-edit"></i> Edit Pengaturan
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modal Edit Pengaturan -->
            <div class="modal fade" id="editSettingModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{ route('settings.update', $setting->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title"><i class="fas fa-edit"></i> Edit Pengaturan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Nama Website</label>
                                    <input type="text" class="form-control" name="nama_web"
                                        value="{{ $setting->nama_web }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email Support</label>
                                    <input type="email" class="form-control" name="email_suport"
                                        value="{{ $setting->email_suport }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">WhatsApp Support</label>
                                    <input type="text" class="form-control" name="wa_suport"
                                        value="{{ $setting->wa_suport }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Fee Service</label>
                                    <input type="number" class="form-control" name="fee_service"
                                        value="{{ $setting->fee_service }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Fee Xendit</label>
                                    <input type="number" class="form-control" name="fee_xendit"
                                        value="{{ $setting->fee_xendit }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Fee Penarikan</label>
                                    <input type="number" class="form-control" name="fee_penarikan"
                                        value="{{ $setting->fee_penarikan }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Deskripsi Website</label>
                                    <textarea class="form-control" name="deskripsi" required>{{ $setting->deskripsi }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Banner 1</label>
                                    <input type="file" class="form-control" name="baner1">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Banner 2</label>
                                    <input type="file" class="form-control" name="baner2">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Banner 3</label>
                                    <input type="file" class="form-control" name="baner3">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
</x-layout>
