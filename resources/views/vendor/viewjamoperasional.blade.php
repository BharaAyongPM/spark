@extends('vendor.layout.master')

@push('plugin-styles')
    <link rel="stylesheet" href="{{ asset('admin/assets/plugins/plugin.css') }}">
@endpush

@section('content')
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Jam Operasional</h5>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addJamOperasionalModal">Tambah
                    Jam Operasional</button>
            </div>
            <div class="card-body">
                <table class="table align-items-center">
                    <thead>
                        <tr>
                            <th>Lapangan</th>
                            <th>Senin</th>
                            <th>Selasa</th>
                            <th>Rabu</th>
                            <th>Kamis</th>
                            <th>Jumat</th>
                            <th>Sabtu</th>
                            <th>Minggu</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jamOperasionals as $jam)
                            <tr>
                                <td>{{ $jam->field->name }}</td>
                                <td>{{ $jam->senin ?? '-' }}</td>
                                <td>{{ $jam->selasa ?? '-' }}</td>
                                <td>{{ $jam->rabu ?? '-' }}</td>
                                <td>{{ $jam->kamis ?? '-' }}</td>
                                <td>{{ $jam->jumat ?? '-' }}</td>
                                <td>{{ $jam->sabtu ?? '-' }}</td>
                                <td>{{ $jam->minggu ?? '-' }}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#editJamModal-{{ $jam->id }}">Edit</button>
                                    <form action="{{ route('vendor.jamoperasional.destroy', $jam->id) }}" method="POST"
                                        style="display:inline;" onsubmit="return confirm('Hapus data ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Modal Edit Jam Operasional -->
                            <div class="modal fade" id="editJamModal-{{ $jam->id }}" tabindex="-1"
                                aria-labelledby="editJamModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form action="{{ route('vendor.jamoperasional.update', $jam->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editJamModalLabel">Edit Jam Operasional
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                @foreach (['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'] as $day)
                                                    <div class="mb-3">
                                                        <label for="{{ $day }}"
                                                            class="form-label">{{ ucfirst($day) }}</label>
                                                        <input type="text" name="{{ $day }}"
                                                            id="{{ $day }}" class="form-control"
                                                            value="{{ $jam->$day }}">
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Jam Operasional -->
    <div class="modal fade" id="addJamOperasionalModal" tabindex="-1" aria-labelledby="addJamOperasionalModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('vendor.jamoperasional.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addJamOperasionalModalLabel">Tambah Jam Operasional</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="field_id" class="form-label">Lapangan</label>
                            <select name="field_id" id="field_id" class="form-control" required>
                                <option value="" disabled selected>Pilih Lapangan</option>
                                @foreach ($fields as $field)
                                    <option value="{{ $field->id }}">{{ $field->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @foreach (['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'] as $day)
                            <div class="mb-3">
                                <label for="{{ $day }}_buka" class="form-label">{{ ucfirst($day) }}</label>
                                <div class="d-flex align-items-center gap-2">
                                    <div>
                                        <label for="{{ $day }}_buka" class="form-label">Jam Buka</label>
                                        <input type="time" name="{{ $day }}_buka"
                                            id="{{ $day }}_buka" class="form-control">
                                    </div>
                                    <div>
                                        <label for="{{ $day }}_tutup" class="form-label">Jam Tutup</label>
                                        <input type="time" name="{{ $day }}_tutup"
                                            id="{{ $day }}_tutup" class="form-control">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
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
@endpush
