@extends('vendor.layout.master')

@push('plugin-styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
    <div class="container-fluid py-4">
        <h4 class="mb-4">Manajemen Diskon</h4>

        @if (session('success'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: '{{ session('success') }}'
                });
            </script>
        @endif

        @if (session('error'))
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: '{{ session('error') }}'
                });
            </script>
        @endif

        @if ($errors->any())
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal',
                    html: `{!! implode('<br>', $errors->all()) !!}`
                });
            </script>
        @endif

        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addDiskonModal">Tambah Diskon</button>

        <div class="card">
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Persentase</th>
                            <th>Periode</th>
                            <th>Field</th>
                            <th>Scope</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($discounts as $diskon)
                            <tr>
                                <td>{{ $diskon->code }}</td>
                                <td>{{ $diskon->percentage }}%</td>
                                <td>{{ $diskon->start_date }} - {{ $diskon->end_date }}</td>
                                <td>{{ $diskon->field ? $diskon->field->name : ($diskon->scope == 'all' ? 'Semua Field' : '-') }}
                                </td>
                                <td>{{ ucfirst($diskon->scope) }}</td>
                                <td>{{ $diskon->automatic ? 'Otomatis' : 'Manual' }}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#editModal-{{ $diskon->id }}">Edit</button>
                                    <form method="POST" action="{{ route('discounts.destroy', $diskon->id) }}"
                                        style="display:inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            <!-- Modal Edit -->
                            <div class="modal fade" id="editModal-{{ $diskon->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <form method="POST" action="{{ route('discounts.update', $diskon->id) }}"
                                        class="modal-content">
                                        @csrf @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Diskon</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Kode</label>
                                                <input type="text" name="code" class="form-control"
                                                    value="{{ $diskon->code }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Persentase</label>
                                                <input type="number" name="percentage" class="form-control"
                                                    value="{{ $diskon->percentage }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Tanggal Mulai</label>
                                                <input type="date" name="start_date" class="form-control"
                                                    value="{{ $diskon->start_date }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Tanggal Selesai</label>
                                                <input type="date" name="end_date" class="form-control"
                                                    value="{{ $diskon->end_date }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Scope</label>
                                                <select name="scope" class="form-control select2">
                                                    <option value="all" {{ $diskon->scope == 'all' ? 'selected' : '' }}>
                                                        Semua Field</option>
                                                    <option value="specific"
                                                        {{ $diskon->scope == 'specific' ? 'selected' : '' }}>Field Tertentu
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Field</label>
                                                <select name="field_id" class="form-control select2">
                                                    <option value="">Pilih Field</option>
                                                    @foreach ($fields as $field)
                                                        <option value="{{ $field->id }}"
                                                            {{ $diskon->field_id == $field->id ? 'selected' : '' }}>
                                                            {{ $field->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" name="automatic"
                                                    value="1" {{ $diskon->automatic ? 'checked' : '' }}>
                                                <label class="form-check-label">Diskon Otomatis</label>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal Tambah Diskon -->
        <div class="modal fade" id="addDiskonModal" tabindex="-1">
            <div class="modal-dialog">
                <form method="POST" action="{{ route('discounts.store') }}" class="modal-content">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Diskon</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Kode</label>
                            <input type="text" name="code" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Persentase</label>
                            <input type="number" name="percentage" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" name="start_date" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Selesai</label>
                            <input type="date" name="end_date" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Scope</label>
                            <select name="scope" class="form-control select2">
                                <option value="all">Semua Field</option>
                                <option value="specific">Field Tertentu</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Field</label>
                            <select name="field_id" class="form-control select2">
                                <option value="">Pilih Field</option>
                                @foreach ($fields as $field)
                                    <option value="{{ $field->id }}">{{ $field->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="automatic" value="1">
                            <label class="form-check-label">Diskon Otomatis</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('custom-scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                width: '100%'
            });
        });
    </script>
@endpush
