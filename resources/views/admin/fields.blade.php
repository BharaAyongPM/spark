@extends('vendor.layout.master')

@push('plugin-styles')
    <link rel="stylesheet" href="{{ asset('admin/assets/plugins/plugin.css') }}">
@endpush

@section('content')
    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-header pb-0 bg-primary text-white">
                    <h6 class="mb-0">Fields Table</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-3">
                        <table class="table table-bordered table-hover mb-0">
                            <thead>
                                <tr class="bg-primary text-white">
                                    <th style="width: 5%">No</th>
                                    <th>Name</th>
                                    <th>Location</th>
                                    <th>Owner</th>
                                    <th>Grass Quality</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($fields as $index => $field)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $field->name }}</td>
                                        <td>{{ $field->location->name ?? '-' }}</td>
                                        <td>{{ $field->owner }}</td>
                                        <td>{{ ucfirst($field->grass_quality) }}</td>
                                        <td>
                                            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#viewFieldModal{{ $field->id }}">
                                                <i class="mdi mdi-eye"></i> Lihat
                                            </button>
                                        </td>
                                    </tr>

                                    {{-- Modal --}}
                                    <div class="modal fade" id="viewFieldModal{{ $field->id }}" tabindex="-1"
                                        aria-labelledby="viewFieldModalLabel{{ $field->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary text-white">
                                                    <h5 class="modal-title" id="viewFieldModalLabel{{ $field->id }}">
                                                        Detail Field:
                                                        {{ $field->name }}
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    {{-- Field Details --}}
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Nama Lapangan</label>
                                                            <input type="text" class="form-control" readonly
                                                                value="{{ $field->name }}">
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Tipe Lapangan</label>
                                                            <input type="text" class="form-control" readonly
                                                                value="{{ $field->fieldType->type_name ?? '-' }}">
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Lokasi</label>
                                                            <input type="text" class="form-control" readonly
                                                                value="{{ $field->location->name ?? '-' }}">
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Pemilik</label>
                                                            <input type="text" class="form-control" readonly
                                                                value="{{ $field->owner }}">
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Kualitas Rumput</label>
                                                            <input type="text" class="form-control" readonly
                                                                value="{{ ucfirst($field->grass_quality) }}">
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Slug</label>
                                                            <input type="text" class="form-control" readonly
                                                                value="{{ $field->slug }}">
                                                        </div>
                                                        <div class="col-md-12 mb-3">
                                                            <label class="form-label">Deskripsi</label>
                                                            <textarea class="form-control" rows="3" readonly>{{ $field->deskripsi }}</textarea>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Slot Tipe</label>
                                                            <input type="text" class="form-control" readonly
                                                                value="{{ $field->slot_tipe }}">
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Batas Pembayaran</label>
                                                            <input type="text" class="form-control" readonly
                                                                value="{{ $field->batas_pembayaran }}">
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Latitude</label>
                                                            <input type="text" class="form-control" readonly
                                                                value="{{ $field->lat }}">
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Longitude</label>
                                                            <input type="text" class="form-control" readonly
                                                                value="{{ $field->lon }}">
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">No. WhatsApp</label>
                                                            <input type="text" class="form-control" readonly
                                                                value="{{ $field->no_whatsapp }}">
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Custom Domain</label>
                                                            <input type="text" class="form-control" readonly
                                                                value="{{ $field->custom_domain }}">
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Instagram</label>
                                                            <input type="text" class="form-control" readonly
                                                                value="{{ $field->instagram }}">
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Facebook</label>
                                                            <input type="text" class="form-control" readonly
                                                                value="{{ $field->facebook }}">
                                                        </div>
                                                        <div class="col-md-12 mb-3">
                                                            <label class="form-label">Syarat & Ketentuan</label>
                                                            <textarea class="form-control" rows="3" readonly>{{ $field->syarat_ketentuan }}</textarea>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Status</label>
                                                            <input type="text" class="form-control" readonly
                                                                value="{{ $field->status == 1 ? 'Aktif' : 'Nonaktif' }}">
                                                        </div>
                                                        {{-- Gallery --}}
                                                        <div class="col-md-12 mb-3">
                                                            <label class="form-label">Galeri</label>
                                                            <div class="d-flex flex-wrap gap-2">
                                                                @php
                                                                    $gallery = json_decode($field->gallery, true) ?? [];
                                                                @endphp
                                                                @forelse ($gallery as $img)
                                                                    <img src="{{ asset('storage/' . $img) }}"
                                                                        width="100" height="100"
                                                                        style="object-fit: cover; border-radius: 8px; border: 1px solid #ddd;">
                                                                @empty
                                                                    <p class="text-muted">No gallery images.</p>
                                                                @endforelse
                                                            </div>
                                                        </div>
                                                        {{-- Facilities --}}
                                                        <div class="col-md-12 mb-3">
                                                            <label class="form-label">Fasilitas</label>
                                                            <ul>
                                                                @foreach ($field->facilities as $facility)
                                                                    <li>{{ $facility->name }}</li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- End Modal --}}
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No fields found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('plugin-scripts')
    <script src="{{ asset('admin/assets/plugins/plugin.js') }}"></script>
@endpush

@push('custom-scripts')
    {{-- Custom scripts here --}}
@endpush
