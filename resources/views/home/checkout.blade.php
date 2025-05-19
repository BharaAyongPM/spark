@extends('layouts.app')

@section('title', 'Home - MyApp')
@section('content')
    <style>
        body {
            font-family: 'Rubik';
            background: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        /* Header */
        .header {
            background: #0047AB;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .header img {
            width: 100px;
        }

        .container-order {
            width: 80%;
            margin: auto;
        }

        /* Kartu Pesanan */
        .order-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 500px;
            border: none;
        }

        /* Header */
        .order-header {
            align-items: center;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .order-header h5 {
            font-weight: bold;
            margin: 0;
        }

        /* Rating */
        .rating {
            font-size: 12px;
            color: #777;
        }

        .rating i {
            color: #ffcc00;
            margin-right: 5px;
        }

        /* Lapangan & Waktu */
        .order-details {
            margin-top: 10px;
        }

        .order-details h6 {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .order-details p {
            color: #777;
            font-size: 14px;
            margin: 0;
        }

        /* Slot Waktu */
        .time-slot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f5f5f5;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: bold;
            margin-top: 10px;
            position: relative;
        }

        .time-slot::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            width: 8px;
            height: 100%;
            background: #001F8B;
            border-radius: 8px 0 0 8px;
        }

        /* Garis pemisah */
        .divider {
            border-top: 1px solid #ddd;
            margin: 10px 0;
        }

        /* Tombol Tambah Jadwal */
        .add-schedule {
            color: black;
            font-weight: bold;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
        }

        .add-schedule i {
            margin-right: 8px;
            font-size: 16px;
        }

        /* Tombol Pilih Voucher */
        .voucher-btn {
            display: flex;
            align-items: center;
            background: #e8f0ff;
            color: #0047AB;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #0047AB;
            width: 100%;
            justify-content: center;
            cursor: pointer;
            font-weight: bold;
        }

        .voucher-btn i {
            margin-right: 10px;
            color: #ffcc00;
            font-size: 18px;
        }

        /* Kartu Rincian Biaya */
        .summary-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            border: none;
            width: 100%;
        }

        .summary-card h5 {
            font-weight: bold;
        }

        .cost-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            font-size: 14px;
            color: #333;
        }

        .cost-row.border-top {
            border-top: 1px solid #ddd;
            margin-top: 10px;
            padding-top: 10px;
        }

        /* Tombol Bayar */
        .pay-button {
            width: 100%;
            background: #0047AB;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            font-weight: bold;
        }

        .pay-button:hover {
            background: #003080;
        }
    </style>
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

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops!',
                text: '{{ session('error') }}',
                confirmButtonColor: '#d33'
            });
        </script>
    @endif

    <div class="row">
        <!-- Kartu Pesanan -->
        <div class="col-md-6">
            <div class="container-order">
                <h3 style="font-weight: bold;">Review Pesanan dan Pembayaran</h3>
                <div class="order-card">
                    <div class="order-header">
                        <h5>Detail Pesanan</h5>
                        <p class="rating">
                            <i class="fas fa-star"></i> Pesanan Anda
                        </p>
                    </div>

                    <div class="divider"></div>

                    {{-- Pesanan dari Keranjang --}}
                    @forelse ($cart as $item)
                        <div class="order-details">
                            <h6>Lapangan #{{ $item['field_id'] }}</h6>
                            <p>{{ \Carbon\Carbon::parse($item['date'])->translatedFormat('l, d F Y') }}</p>
                        </div>

                        <div class="time-slot">
                            <span>{{ $item['time_slot'] }}</span>
                            <span>Rp. {{ number_format($item['price'], 0, ',', '.') }}</span>
                        </div>
                        <div class="divider"></div>
                        @if (isset($wasitPhotoData[$item['field_id']]))
                            @php
                                $selectedFieldAddons = collect($selectedAddons)->where('field_id', $item['field_id']);
                                $alreadySelected = $selectedFieldAddons->isNotEmpty();
                            @endphp

                            @if ($alreadySelected)
                                <div class="text-success small mb-2">✅ Add-on sudah ditambahkan</div>
                                <button class="btn btn-sm btn-outline-warning mb-3" data-bs-toggle="modal"
                                    data-bs-target="#addonModal-{{ $item['field_id'] }}">✏ Edit Add-on</button>
                            @else
                                <div class="text-muted small mb-2">Ingin menggunakan wasit/fotografer dari penyedia
                                    lapangan?</div>
                                <button class="btn btn-sm btn-outline-info mb-3" data-bs-toggle="modal"
                                    data-bs-target="#addonModal-{{ $item['field_id'] }}">+ Tambahkan Add-on</button>
                            @endif
                        @endif
                    @empty
                        <p class="text-muted px-3">Tidak ada pesanan di keranjang.</p>
                    @endforelse

                    {{-- Pesanan dari Histori --}}
                    @forelse ($orderHistory as $item)
                        <div class="order-details">
                            <h6>Lapangan #{{ $item['field_id'] }}</h6>
                            <p>{{ \Carbon\Carbon::parse($item['date'])->translatedFormat('l, d F Y') }}</p>
                        </div>

                        <div class="time-slot">
                            <span>{{ $item['time_slot'] }}</span>
                            <span>Rp. {{ number_format($item['price'], 0, ',', '.') }}</span>
                        </div>
                        <div class="divider"></div>
                        @if (isset($wasitPhotoData[$item['field_id']]))
                            @php
                                $selectedFieldAddons = collect($selectedAddons)->where('field_id', $item['field_id']);
                                $alreadySelected = $selectedFieldAddons->isNotEmpty();
                            @endphp

                            @if ($alreadySelected)
                                <div class="text-success small mb-2">✅ Add-on sudah ditambahkan</div>
                                <button class="btn btn-sm btn-outline-warning mb-3" data-bs-toggle="modal"
                                    data-bs-target="#addonModal-{{ $item['field_id'] }}">✏ Edit Add-on</button>
                            @else
                                <div class="text-muted small mb-2">Ingin menggunakan wasit/fotografer dari penyedia
                                    lapangan?</div>
                                <button class="btn btn-sm btn-outline-info mb-3" data-bs-toggle="modal"
                                    data-bs-target="#addonModal-{{ $item['field_id'] }}">+ Tambahkan Add-on</button>
                            @endif
                        @endif
                    @empty
                        <p class="text-muted px-3">Tidak ada pesanan tertunda dari histori.</p>
                    @endforelse

                    <div class="add-schedule">
                        <i class="fas fa-arrow-left"></i> <a href="{{ url()->previous() }}"
                            class="text-dark text-decoration-none">Tambah Jadwal</a>
                    </div>
                </div>
            </div>
        </div>
        @foreach ($wasitPhotoData as $fieldId => $addons)
            <div class="modal fade" id="addonModal-{{ $fieldId }}" tabindex="-1">
                <div class="modal-dialog">
                    <form action="{{ route('checkout.addons') }}" method="POST" class="modal-content">
                        @csrf
                        <input type="hidden" name="field_id" value="{{ $fieldId }}">
                        <div class="modal-header">
                            <h5 class="modal-title">Pilih Add-on</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            @foreach ($addons as $addon)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="addons[]"
                                        value="{{ $addon->id }}"
                                        {{ collect($selectedAddons)->pluck('id')->contains($addon->id) ? 'checked' : '' }}>
                                    <label class="form-check-label">
                                        {{ ucfirst($addon->jenis) }} - {{ $addon->nama }} (Rp
                                        {{ number_format($addon->harga, 0, ',', '.') }})
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
        <div class="col-md-6">
            <div class="container-order">
                <!-- Tombol Pilih Voucher -->
                <button id="voucherBtn" class="voucher-btn mb-3">
                    <i class="fas fa-ticket"></i> Pilih Voucher
                </button>

                <!-- Kartu Rincian Biaya -->
                <div class="summary-card">
                    <h5 class="mb-3">Rincian Biaya</h5>

                    <div class="cost-row">
                        <span>Pesanan Chart</span>
                        <strong>Rp {{ number_format($totalSewaCart, 0, ',', '.') }}</strong>

                    </div>
                    <div class="cost-row">
                        <span>Pesanan History </span>
                        <strong>Rp {{ number_format($totalSewaHistory, 0, ',', '.') }}</strong>
                    </div>
                    <div class="cost-row">
                        <span>Platform </span>
                        <strong>Rp {{ number_format($feeService, 0, ',', '.') }}</strong>
                    </div>
                    <div class="cost-row">
                        <span>Biaya Xendit </span>
                        <strong>Rp {{ number_format($feeXendit, 0, ',', '.') }}</strong>
                    </div>
                    @if (!empty($selectedAddons))
                        @php
                            $wasitTotal = collect($selectedAddons)->where('jenis', 'wasit')->sum('harga');
                            $photoTotal = collect($selectedAddons)->where('jenis', 'photo')->sum('harga');
                        @endphp

                        @if ($wasitTotal > 0)
                            <div class="cost-row">
                                <span>Biaya Wasit</span>
                                <strong>Rp {{ number_format($wasitTotal, 0, ',', '.') }}</strong>
                            </div>
                        @endif

                        @if ($photoTotal > 0)
                            <div class="cost-row">
                                <span>Biaya Fotografer</span>
                                <strong>Rp {{ number_format($photoTotal, 0, ',', '.') }}</strong>
                            </div>
                        @endif
                    @endif
                    <div class="cost-row border-top">
                        <span>Jumlah</span>
                        <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong>
                    </div>
                    @if ($appliedDiscount)
                        <div class="cost-row">
                            <span>Diskon ({{ $appliedDiscount->code }})</span>
                            <strong>- Rp {{ number_format($discountAmount, 0, ',', '.') }}</strong>
                        </div>
                    @endif
                    <div class="cost-row border-top">
                        <strong>Total</strong>
                        <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong>
                    </div>
                    <form action="{{ route('checkout') }}" method="POST">
                        @csrf
                        <button type="submit" class="pay-button mt-3">Bayar Sekarang</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


    <script>
        document.getElementById('voucherBtn').addEventListener('click', function() {
            Swal.fire({
                title: 'Masukkan Kode Voucher',
                input: 'text',
                inputPlaceholder: 'Contoh: DISKON50',
                showCancelButton: true,
                confirmButtonText: 'Gunakan',
                cancelButtonText: 'Batal',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Kode tidak boleh kosong!';
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    let kode = result.value.trim();
                    if (kode) {
                        // Redirect ke halaman checkout dengan kode_diskon
                        const url = new URL(window.location.href);
                        url.searchParams.set('kode_diskon', kode);
                        window.location.href = url.toString();
                    }
                }
            });
        });
    </script>

@endsection
