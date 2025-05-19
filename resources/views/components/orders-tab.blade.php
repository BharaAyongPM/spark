@if ($orders->isEmpty())
    <div class="text-center py-5">
        <img src="https://cdn-icons-png.flaticon.com/512/4076/4076549.png" width="80" class="mb-3 opacity-50"
            alt="Empty Icon">
        <h6 class="fw-semibold text-muted">Belum ada Pemesanan</h6>
        <p class="text-muted small">Venue yang anda pesan akan muncul disini ya!</p>
    </div>
@else
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tanggal</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                        <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        <td>
                            <span
                                class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : ($order->status === 'ongoing' ? 'info' : 'danger')) }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-info btn-sm show-order-details" data-order-id="{{ $order->id }}">
                                Lihat Detail
                            </button>
                            @if ($order->status === 'pending')
                                <button class="btn btn-success btn-sm pay-order" data-order-id="{{ $order->id }}">
                                    Bayar
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
0
