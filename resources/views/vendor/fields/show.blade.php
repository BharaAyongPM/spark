<x-layout bodyClass="g-sidenav-show bg-gray-200">
    <x-navbars.sidebar activePage="fields"></x-navbars.sidebar>

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-navbars.navs.auth titlePage="Detail Lapangan"></x-navbars.navs.auth>

        <div class="container-fluid py-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ $field->name }}</h5>
                    <button class="btn btn-primary btn-sm float-end" data-bs-toggle="modal"
                        data-bs-target="#editFieldModal">Edit Lapangan</button>
                </div>
                <div class="card-body">
                    <p><strong>Tipe Lapangan:</strong> {{ $field->fieldType->type_name }}</p>
                    <p><strong>Lokasi:</strong> {{ $field->location }}</p>
                    <p><strong>Kualitas Rumput:</strong> {{ $field->grass_quality }}</p>
                    <p><strong>Slug:</strong> {{ $field->slug }}</p>
                    <p><strong>Latitude:</strong> {{ $field->lat }}</p>
                    <p><strong>Longitude:</strong> {{ $field->lon }}</p>
                    <img src="{{ asset('storage/' . $field->foto) }}" alt="Foto Lapangan" class="img-fluid mt-3"
                        style="max-height: 200px;">
                    <img src="{{ asset('storage/' . $field->banner) }}" alt="Banner Lapangan" class="img-fluid mt-3"
                        style="max-height: 200px;">
                </div>
            </div>
        </div>
    </main>

    <!-- Modal Edit -->
    <div class="modal fade" id="editFieldModal" tabindex="-1" aria-labelledby="editFieldModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('vendor.fields.update', $field->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editFieldModalLabel">Edit Lapangan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lapangan</label>
                            <input type="text" name="name" id="name" class="form-control"
                                value="{{ $field->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="field_type_id" class="form-label">Tipe Lapangan</label>
                            <select name="field_type_id" id="field_type_id" class="form-control" required>
                                @foreach ($fieldTypes as $type)
                                    <option value="{{ $type->id }}"
                                        {{ $field->field_type_id == $type->id ? 'selected' : '' }}>{{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="location_id" class="form-label">Lokasi</label>
                            <select name="location_id" id="location_id" class="form-control" required>
                                @foreach ($locations as $location)
                                    <option value="{{ $location->id }}"
                                        {{ $field->location_id == $location->id ? 'selected' : '' }}>
                                        {{ $location->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="grass_quality" class="form-label">Kualitas Rumput</label>
                            <input type="text" name="grass_quality" id="grass_quality" class="form-control"
                                value="{{ $field->grass_quality }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="foto" class="form-label">Foto</label>
                            <input type="file" name="foto" id="foto" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="banner" class="form-label">Banner</label>
                            <input type="file" name="banner" id="banner" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout>
