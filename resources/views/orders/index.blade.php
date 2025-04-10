@extends('layouts.app')

@section('title', 'Home - MyApp')
@section('content')

    <div class="container mt-5">
        <h2>Histori Pesanan</h2>

        @if ($orders->isEmpty())
            <p class="text-muted">Belum ada pesanan.</p>
        @else
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
                                    class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : 'danger') }}">
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
        @endif
    </div>

    <!-- Modal untuk Detail Pesanan -->
    <div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Pesanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>ID Pesanan:</strong> <span id="order-id"></span></p>
                    <p><strong>Tanggal:</strong> <span id="order-date"></span></p>
                    <p><strong>Total Harga:</strong> Rp <span id="order-total"></span></p>
                    <p><strong>Status:</strong> <span id="order-status" class="badge"></span></p>
                    <h5>Detail Lapangan</h5>
                    <ul id="order-items"></ul>
                </div>
            </div>
        </div>
    </div>

    @push('js')
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Tampilkan detail pesanan dalam modal
                document.querySelectorAll(".show-order-details").forEach(button => {
                    button.addEventListener("click", function() {
                        let orderId = this.dataset.orderId;

                        fetch(`/orders/${orderId}`)
                            .then(response => response.json())
                            .then(order => {
                                document.getElementById("order-id").textContent = order.id;
                                document.getElementById("order-date").textContent = new Date(order
                                    .created_at).toLocaleString();
                                document.getElementById("order-total").textContent = new Intl
                                    .NumberFormat('id-ID').format(order.total_price);

                                let statusElement = document.getElementById("order-status");
                                statusElement.textContent = order.status.charAt(0).toUpperCase() +
                                    order.status.slice(1);
                                statusElement.className = "badge bg-" + (order.status ===
                                    "completed" ? "success" : (order.status === "pending" ?
                                        "warning" : "danger"));

                                let itemsList = document.getElementById("order-items");
                                itemsList.innerHTML = "";
                                order.items.forEach(item => {
                                    let listItem = document.createElement("li");
                                    listItem.textContent =
                                        `Lapangan ${item.field_name} - ${item.date} (${item.time})`;
                                    itemsList.appendChild(listItem);
                                });

                                let modal = new bootstrap.Modal(document.getElementById(
                                    "orderDetailsModal"));
                                modal.show();
                            })
                            .catch(error => console.error("Error fetching order details:", error));
                    });
                });

                // Tombol bayar pesanan
                document.querySelectorAll(".pay-order").forEach(button => {
                    button.addEventListener("click", function() {
                        let orderId = this.dataset.orderId;

                        Swal.fire({
                            title: "Konfirmasi Pembayaran",
                            text: "Apakah Anda ingin melanjutkan pembayaran?",
                            icon: "question",
                            showCancelButton: true,
                            confirmButtonText: "Ya, Bayar",
                            cancelButtonText: "Batal"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Redirect langsung ke halaman checkout
                                window.location.href = `/checkout?order_id=${orderId}`;
                            }
                        });
                    });
                });
            });
        </script>
    @endpush


@endsection
