<x-layout bodyClass="g-sidenav-show bg-gray-200">
    <x-navbars.sidebar activePage="fields"></x-navbars.sidebar>

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-navbars.navs.auth titlePage="Daftar Lapangan"></x-navbars.navs.auth>

        <div class="container-fluid py-4">
            <!-- Button to open Create Modal -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5>Daftar Lapangan</h5>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createFieldModal">
                    Tambah Lapangan
                </button>
            </div>

            <!-- Fields Table inside a card -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Daftar Lapangan</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder">Nama</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder">Tipe</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder">Lokasi</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder">Kualitas Rumput
                                    </th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($fields as $field)
                                    <tr>
                                        <td>
                                            <a href="{{ route('vendor.fields.show', $field->id) }}"
                                                class="text-primary font-weight-bold">
                                                {{ $field->name }}
                                            </a>
                                        </td>
                                        <td>{{ $field->fieldType->type_name }}</td>
                                        <td>{{ $field->location }}</td>
                                        <td>{{ ucfirst($field->grass_quality) }}</td>
                                        <td>
                                            <form action="{{ route('vendor.fields.destroy', $field->id) }}"
                                                method="POST" onsubmit="return confirm('Are you sure?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Create Field Modal -->
            <div class="modal fade" id="createFieldModal" tabindex="-1" aria-labelledby="createFieldModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="createFieldModalLabel">Tambah Lapangan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <form action="{{ route('vendor.fields.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="field_type_id" class="form-label">Tipe Lapangan</label>
                                    <select name="field_type_id" id="field_type_id" class="form-control" required>
                                        @foreach ($fieldTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->type_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="location_id" class="form-label">Lokasi</label>
                                    <select name="location_id" id="location_id" class="form-control" required>
                                        @foreach ($locations as $location)
                                            <option value="{{ $location->id }}">{{ $location->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Lapangan</label>
                                    <input type="text" name="name" id="name" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="owner" class="form-label">Pemilik</label>
                                    <input type="text" name="owner" id="owner" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="location" class="form-label">Lokasi Lapangan</label>
                                    <input type="text" name="location" id="location" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="slug" class="form-label">Slug</label>
                                    <input type="text" name="slug" id="slug" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="deskripsi" class="form-label">Deskripsi</label>
                                    <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="grass_quality" class="form-label">Kualitas Rumput</label>
                                    <select name="grass_quality" id="grass_quality" class="form-control" required>
                                        <option value="synthetic">Synthetic</option>
                                        <option value="natural">Natural</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="foto" class="form-label">Foto</label>
                                    <input type="file" name="foto" id="foto" class="form-control"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="banner" class="form-label">Banner</label>
                                    <input type="file" name="banner" id="banner" class="form-control"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="slot_tipe" class="form-label">Slot Tipe</label>
                                    <select name="slot_tipe" id="slot_tipe" class="form-control" required>
                                        <option value="per 1 jam">Per 1 Jam</option>
                                        <option value="per 2 jam">Per 2 Jam</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="lat" class="form-label">Latitude</label>
                                    <input type="text" name="lat" id="lat" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="lon" class="form-label">Longitude</label>
                                    <input type="text" name="lon" id="lon" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="no_whatsapp" class="form-label">Nomor WhatsApp</label>
                                    <input type="text" name="no_whatsapp" id="no_whatsapp" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="custom_domain" class="form-label">Custom Domain</label>
                                    <input type="text" name="custom_domain" id="custom_domain"
                                        class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="instagram" class="form-label">Instagram</label>
                                    <input type="text" name="instagram" id="instagram" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="facebook" class="form-label">Facebook</label>
                                    <input type="text" name="facebook" id="facebook" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="video" class="form-label">Video</label>
                                    <input type="text" name="video" id="video" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="batas_pembayaran" class="form-label">Batas Pembayaran</label>
                                    <select name="batas_pembayaran" id="batas_pembayaran" class="form-control"
                                        required>
                                        <option value="30 menit">30 Menit</option>
                                        <option value="1 jam">1 Jam</option>
                                        <option value="2 jam">2 Jam</option>
                                        <option value="10 jam">10 Jam</option>
                                        <option value="24 jam">24 Jam</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="syarat_ketentuan" class="form-label">Syarat dan Ketentuan</label>
                                    <textarea name="syarat_ketentuan" id="syarat_ketentuan" class="form-control" rows="3"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select name="status" id="status" class="form-control" required>
                                        <option value="1">Aktif</option>
                                        <option value="0">Nonaktif</option>
                                    </select>
                                </div>
                                <!-- Tambahkan Fasilitas -->
                                <div class="mb-3">
                                    <label for="facilities" class="form-label">Pilih Fasilitas</label>
                                    <div>
                                        @foreach ($facilities as $facility)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="facilities[]"
                                                    value="{{ $facility->id }}" id="facility-{{ $facility->id }}">
                                                <label class="form-check-label" for="facility-{{ $facility->id }}">
                                                    {{ $facility->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </main>
</x-layout>
