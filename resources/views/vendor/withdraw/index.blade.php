@extends('vendor.layout.master')

@section('content')
    <div class="container py-4">
        <h5>Withdraw Dana</h5>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $err)
                    <div>{{ $err }}</div>
                @endforeach
            </div>
        @endif

        <div class="card mb-4">
            <div class="card-body">
                <p><strong>Saldo Siap Tarik:</strong> Rp {{ number_format($vendor->saldo_siap_tarik, 0, ',', '.') }}</p>
                @if ($rekening)
                    <p><strong>Rekening:</strong> {{ $rekening->nama_bank }} - {{ $rekening->rekening }}
                        ({{ $rekening->nama }})</p>
                    <form action="{{ route('vendor.withdraw.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="amount" class="form-label">Jumlah Withdraw</label>
                            <input type="number" name="amount" id="amount" class="form-control"
                                placeholder="Minimal Rp10.000" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Ajukan Withdraw</button>
                    </form>
                @else
                    <div class="alert alert-warning">
                        Anda belum menambahkan data rekening. Silakan isi di menu Profil Vendor terlebih dahulu.
                    </div>
                @endif
            </div>
        </div>

        <h5>Riwayat Withdraw</h5>
        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($withdraws as $wd)
                            <tr>
                                <td>{{ $wd->created_at->format('d-m-Y H:i') }}</td>
                                <td>Rp {{ number_format($wd->amount, 0, ',', '.') }}</td>
                                <td>
                                    <span
                                        class="badge
                                        {{ $wd->status == 'pending'
                                            ? 'bg-warning'
                                            : ($wd->status == 'approved'
                                                ? 'bg-info'
                                                : ($wd->status == 'completed'
                                                    ? 'bg-success'
                                                    : 'bg-danger')) }}">
                                        {{ ucfirst($wd->status) }}
                                    </span>
                                </td>
                                <td>{{ $wd->note ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Belum ada riwayat withdraw.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-3">
                    {{ $withdraws->links() }}
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Ketentuan Withdraw -->
    <div class="modal fade" id="withdrawRulesModal" tabindex="-1" aria-labelledby="withdrawRulesLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="withdrawRulesLabel">Ketentuan Withdraw</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <ul>
                        <li>Dana yang ditarik <strong>tidak boleh melebihi saldo yang bisa ditarik</strong>.</li>
                        <li><strong>Biaya admin sebesar Rp10.000</strong> akan dipotong dari jumlah withdraw.</li>
                        <li>Proses withdraw memakan waktu <strong>3-5 hari kerja</strong> tergantung antrian admin dan
                            payment gateway (Xendit).</li>
                        <li>Pastikan <strong>nomor rekening sudah diisi</strong> di menu Profil dan telah
                            <strong>terverifikasi oleh admin</strong>.
                        </li>
                    </ul>
                    <p class="mt-3">Terima kasih telah menggunakan layanan kami 🙌.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Saya Mengerti</button>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('custom-scripts')
    <script>
        $(document).ready(function() {
            $('#withdrawRulesModal').modal('show');
        });
    </script>
@endpush
@push('custom-styles')
    <style>
        .modal-backdrop {
            display: none;
        }
    </style>
@endpush
