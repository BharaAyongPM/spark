@extends('vendor.layout.master')

@push('plugin-styles')
    <link rel="stylesheet" href="{{ asset('admin/assets/plugins/plugin.css') }}">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container .select2-selection--single {
            height: 38px !important;
            /* sama tinggi seperti input */
            padding: 8px 12px;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 20px;
            /* teks di tengah */
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 38px !important;
        }
    </style>
@endpush



@section('content')
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
                                <th class="text-uppercase text-primary text-xs font-weight-bolder">Nama</th>
                                <th class="text-uppercase text-primary text-xs font-weight-bolder">Tipe</th>
                                <th class="text-uppercase text-primary text-xs font-weight-bolder">Lokasi</th>
                                <th class="text-uppercase text-primary text-xs font-weight-bolder">Kualitas Rumput
                                </th>
                                <th class="text-uppercase text-primary text-xs font-weight-bolder">Aksi</th>
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
                                        <button type="button" class="btn btn-warning btn-sm me-1" data-bs-toggle="modal"
                                            data-bs-target="#editFieldModal-{{ $field->id }}">
                                            Edit
                                        </button>
                                        <form action="{{ route('vendor.fields.destroy', $field->id) }}" method="POST"
                                            onsubmit="return confirm('Are you sure?');">
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
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('vendor.fields.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="field_type_id" class="form-label">Tipe Lapangan</label>
                                <select name="field_type_id" id="field_type_id" class="form-control select2" required>
                                    <option value="">Pilih Tipe Lapangan</option>
                                    @foreach ($fieldTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->type_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="location_id" class="form-label">Lokasi</label>
                                <select name="location_id" id="location_id" class="form-control select2" required>
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
                                <select name="grass_quality" id="grass_quality" class="form-control select2" required>
                                    <option value="synthetic">Synthetic</option>
                                    <option value="natural">Natural</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="foto" class="form-label">Foto</label>
                                <input type="file" name="foto" id="foto" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="banner" class="form-label">Banner</label>
                                <input type="file" name="banner" id="banner" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="slot_tipe" class="form-label">Slot Tipe</label>
                                <select name="slot_tipe" id="slot_tipe" class="form-control select2" required>
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
                                <input type="text" name="custom_domain" id="custom_domain" class="form-control">
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
                                <select name="batas_pembayaran" id="batas_pembayaran" class="form-control select2"
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
                                <select name="status" id="status" class="form-control select2" required>
                                    <option value="1">Aktif</option>
                                    <option value="0">Nonaktif</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="gallery" class="form-label">Galeri (maks. 3 gambar)</label>
                                <input type="file" name="gallery[]" class="form-control" multiple accept="image/*"
                                    required>
                                <small class="text-muted">Maksimum 3 gambar</small>
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
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @foreach ($fields as $field)
            <div class="modal fade" id="editFieldModal-{{ $field->id }}" tabindex="-1"
                aria-labelledby="editFieldModalLabel-{{ $field->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{ route('vendor.fields.update', $field->id) }}" method="POST"
                        enctype="multipart/form-data" class="modal-content">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title" id="editFieldModalLabel-{{ $field->id }}">Edit Lapangan
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Nama Lapangan</label>
                                <input type="text" name="name" class="form-control" value="{{ $field->name }}"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tipe Lapangan</label>
                                <select name="field_type_id" class="form-control select2" required>
                                    @foreach ($fieldTypes as $type)
                                        <option value="{{ $type->id }}"
                                            {{ $field->field_type_id == $type->id ? 'selected' : '' }}>
                                            {{ $type->type_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Lokasi</label>
                                <select name="location_id" class="form-control" required>
                                    @foreach ($locations as $location)
                                        <option value="{{ $location->id }}"
                                            {{ $field->location_id == $location->id ? 'selected' : '' }}>
                                            {{ $location->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Pemilik</label>
                                <input type="text" name="owner" class="form-control" value="{{ $field->owner }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Lokasi Detail</label>
                                <input type="text" name="location" class="form-control"
                                    value="{{ $field->location }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Slug</label>
                                <input type="text" name="slug" class="form-control" value="{{ $field->slug }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control" rows="3">{{ $field->deskripsi }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Kualitas Rumput</label>
                                <select name="grass_quality" class="form-control select2">
                                    <option value="synthetic"
                                        {{ $field->grass_quality == 'synthetic' ? 'selected' : '' }}>Synthetic
                                    </option>
                                    <option value="natural" {{ $field->grass_quality == 'natural' ? 'selected' : '' }}>
                                        Natural</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Foto</label>
                                <input type="file" name="foto" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Banner</label>
                                <input type="file" name="banner" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Slot Tipe</label>
                                <select name="slot_tipe" class="form-control select2">
                                    <option value="per 1 jam" {{ $field->slot_tipe == 'per 1 jam' ? 'selected' : '' }}>Per
                                        1 Jam</option>
                                    <option value="per 2 jam" {{ $field->slot_tipe == 'per 2 jam' ? 'selected' : '' }}>Per
                                        2 Jam</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Latitude</label>
                                <input type="text" name="lat" class="form-control" value="{{ $field->lat }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Longitude</label>
                                <input type="text" name="lon" class="form-control" value="{{ $field->lon }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nomor WhatsApp</label>
                                <input type="text" name="no_whatsapp" class="form-control"
                                    value="{{ $field->no_whatsapp }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Custom Domain</label>
                                <input type="text" name="custom_domain" class="form-control"
                                    value="{{ $field->custom_domain }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Instagram</label>
                                <input type="text" name="instagram" class="form-control"
                                    value="{{ $field->instagram }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Facebook</label>
                                <input type="text" name="facebook" class="form-control"
                                    value="{{ $field->facebook }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Video</label>
                                <input type="text" name="video" class="form-control" value="{{ $field->video }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Batas Pembayaran</label>
                                <select name="batas_pembayaran" class="form-control select2">
                                    <option value="30 menit"
                                        {{ $field->batas_pembayaran == '30 menit' ? 'selected' : '' }}>30 Menit
                                    </option>
                                    <option value="1 jam" {{ $field->batas_pembayaran == '1 jam' ? 'selected' : '' }}>1
                                        Jam</option>
                                    <option value="2 jam" {{ $field->batas_pembayaran == '2 jam' ? 'selected' : '' }}>2
                                        Jam</option>
                                    <option value="10 jam" {{ $field->batas_pembayaran == '10 jam' ? 'selected' : '' }}>10
                                        Jam
                                    </option>
                                    <option value="24 jam" {{ $field->batas_pembayaran == '24 jam' ? 'selected' : '' }}>24
                                        Jam
                                    </option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Syarat & Ketentuan</label>
                                <textarea name="syarat_ketentuan" class="form-control" rows="3">{{ $field->syarat_ketentuan }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control select2">
                                    <option value="1" {{ $field->status == 1 ? 'selected' : '' }}>Aktif
                                    </option>
                                    <option value="0" {{ $field->status == 0 ? 'selected' : '' }}>Nonaktif
                                    </option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Galeri Lapangan (maks. 3 gambar)</label>
                                <div class="d-flex flex-wrap gap-2">
                                    @php
                                        $existingGallery = json_decode($field->gallery, true) ?? [];
                                        $totalGallery = count($existingGallery);
                                    @endphp

                                    @foreach ($existingGallery as $index => $galleryPath)
                                        <div class="d-flex flex-column align-items-center me-3">
                                            <img src="{{ asset('storage/' . $galleryPath) }}" width="80"
                                                height="80"
                                                style="object-fit: cover; border-radius: 8px; border: 1px solid #ddd;">
                                            <input type="file" name="gallery_update[{{ $index }}]"
                                                class="form-control mt-1" accept="image/*" style="width: 100px;">
                                            <input type="hidden" name="gallery_existing[{{ $index }}]"
                                                value="{{ $galleryPath }}">
                                        </div>
                                    @endforeach

                                    @for ($i = $totalGallery; $i < 3; $i++)
                                        <div class="d-flex flex-column align-items-center me-3">
                                            <div
                                                style="width: 80px; height: 80px; background: #f0f0f0; border: 1px dashed #aaa; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                                <i class="fa fa-plus text-muted"></i>
                                            </div>
                                            <input type="file" name="gallery_update[{{ $i }}]"
                                                class="form-control mt-1" accept="image/*" style="width: 100px;">
                                        </div>
                                    @endfor
                                </div>
                            </div>





                            <div class="mb-3">
                                <label class="form-label">Fasilitas</label>
                                @foreach ($facilities as $facility)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="facilities[]"
                                            value="{{ $facility->id }}"
                                            id="edit-facility-{{ $field->id }}-{{ $facility->id }}"
                                            {{ $field->facilities->contains($facility->id) ? 'checked' : '' }}>
                                        <label class="form-check-label"
                                            for="edit-facility-{{ $field->id }}-{{ $facility->id }}">
                                            {{ $facility->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach

    </div>
    </main>
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
    <script>
        function initSelect2() {
            $('.select2').select2({
                placeholder: "Silahkan Pilih",
                allowClear: true,
                width: '100%' // sangat penting supaya select mengikuti parent container
            });
        }

        $(document).ready(function() {
            initSelect2();

            // Setiap kali modal Create dibuka, inisialisasi select2 ulang
            $('#createFieldModal').on('shown.bs.modal', function() {
                initSelect2();
            });

            // Setiap kali modal Edit dibuka, inisialisasi select2 ulang
            @foreach ($fields as $field)
                $('#editFieldModal-{{ $field->id }}').on('shown.bs.modal', function() {
                    initSelect2();
                });
            @endforeach
        });
    </script>
@endpush
