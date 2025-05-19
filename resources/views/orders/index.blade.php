@extends('layouts.app')

@section('title', 'Home - MyApp')
@section('content')

    <section class="container my-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold mb-4">Pemesanan</h5>

                {{-- Tabs --}}
                <ul class="nav nav-tabs mb-4" id="orderTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending"
                            type="button" role="tab">Menunggu Pembayaran</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="ongoing-tab" data-bs-toggle="tab" data-bs-target="#ongoing"
                            type="button" role="tab">Sedang Berjalan</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed"
                            type="button" role="tab">Selesai</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="cancelled-tab" data-bs-toggle="tab" data-bs-target="#cancelled"
                            type="button" role="tab">Dibatalkan</button>
                    </li>
                </ul>

                {{-- Tab Content --}}
                <div class="tab-content" id="orderTabsContent">

                    {{-- Menunggu Pembayaran --}}
                    <div class="tab-pane fade show active" id="pending" role="tabpanel">
                        @include('components.orders-tab', [
                            'orders' => $orders->where('status', 'pending'),
                        ])
                    </div>

                    {{-- Sedang Berjalan --}}
                    <div class="tab-pane fade" id="ongoing" role="tabpanel">
                        @include('components.orders-tab', [
                            'orders' => $orders->where('status', 'ongoing'),
                        ])
                    </div>

                    {{-- Selesai --}}
                    <div class="tab-pane fade" id="completed" role="tabpanel">
                        @include('components.orders-tab', [
                            'orders' => $orders->where('status', 'completed'),
                        ])
                    </div>

                    {{-- Dibatalkan --}}
                    <div class="tab-pane fade" id="cancelled" role="tabpanel">
                        @include('components.orders-tab', [
                            'orders' => $orders->where('status', 'cancelled'),
                        ])
                    </div>

                </div>

            </div>
        </div>

        {{-- Modal Detail Pesanan --}}
        @include('components.order-details-modal')

        @push('js')
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                            document.addEventListener("click", function(e) {
                                if (e.target.classList.contains("show-order-details")) {
                                    let orderId = e.target.dataset.orderId;

                                    fetch(`/orders/${orderId}`)
                                        .then(response => response.json())
                                        .then(order => {
                                            document.getElementById("order-id").textContent = order.id;
                                            document.getElementById("order-date").textContent = new Date(order
                                                .created_at).toLocaleString();
                                            document.getElementById("order-total").textContent = new Intl.NumberFormat(
                                                'id-ID').format(order.total_price);

                                            let statusElement = document.getElementById("order-status");
                                            statusElement.textContent = order.status.charAt(0).toUpperCase() + order
                                                .status.slice(1);
                                            statusElement.className = "badge bg-" + (order.status === "completed" ?
                                                "success" : (order.status === "pending" ? "warning" : (order
                                                    .status === "ongoing" ? "info" : "danger")));

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
                                }
                            });
            </script>
        @endpush
    </section>
@endsection
