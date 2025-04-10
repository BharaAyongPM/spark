<x-layout bodyClass="g-sidenav-show bg-gray-200">
    <x-navbars.sidebar activePage="harga"></x-navbars.sidebar>

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-navbars.navs.auth titlePage="Kelola Harga"></x-navbars.navs.auth>

        <div class="container-fluid py-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Daftar Harga</h5>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addPriceModal">Tambah
                        Harga</button>
                </div>
                <div class="card-body">
                    <table class="table align-items-center">
                        <thead>
                            <tr>
                                <th>Lapangan</th>
                                <th>Time Slot</th>
                                <th>Jenis Hari</th>
                                <th>Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pricing as $price)
                                <tr>
                                    <td>{{ $price->field->name }}</td>
                                    <td>
                                        @if ($price->time_slot == 'morning')
                                            Pagi
                                        @elseif ($price->time_slot == 'afternoon')
                                            Siang
                                        @else
                                            Malam
                                        @endif
                                    </td>
                                    <td>{{ $price->day_type == 'weekday' ? 'Hari Biasa' : 'Weekend' }}</td>
                                    <td>Rp {{ number_format($price->price, 0, ',', '.') }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                            data-bs-target="#editPriceModal-{{ $price->id }}">Edit</button>
                                    </td>
                                </tr>

                                <!-- Modal Edit Harga -->
                                <div class="modal fade" id="editPriceModal-{{ $price->id }}" tabindex="-1"
                                    aria-labelledby="editPriceModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form action="{{ route('vendor.harga.update', $price->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editPriceModalLabel">Edit Harga</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="price" class="form-label">Harga</label>
                                                        <input type="text" name="price" id="price"
                                                            class="form-control" value="{{ $price->price }}" required>
                                                    </div>
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

        <!-- Modal Tambah Harga -->
        <div class="modal fade" id="addPriceModal" tabindex="-1" aria-labelledby="addPriceModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('vendor.harga.tambah') }}" method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addPriceModalLabel">Tambah Harga</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
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
                            <div class="mb-3">
                                <label for="time_slot" class="form-label">Waktu</label>
                                <select name="time_slot" id="time_slot" class="form-control" required>
                                    <option value="" disabled selected>Pilih Waktu</option>
                                    <option value="morning">Pagi</option>
                                    <option value="afternoon">Siang</option>
                                    <option value="evening">Malam</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="day_type" class="form-label">Jenis Hari</label>
                                <select name="day_type" id="day_type" class="form-control" required>
                                    <option value="" disabled selected>Pilih Jenis Hari</option>
                                    <option value="weekday">Hari Biasa</option>
                                    <option value="weekend">Weekend</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label">Harga</label>
                                <input type="text" name="price" id="price" class="form-control" required>
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
    </main>
</x-layout>
