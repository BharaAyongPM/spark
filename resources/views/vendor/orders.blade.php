@extends('vendor.layout.master')

@push('plugin-styles')
    <link rel="stylesheet" href="{{ asset('admin/assets/plugins/plugin.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container .select2-selection--single {
            height: 38px !important;
            /* sama tinggi seperti input */
            padding: 8px 12px;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 20px;
            /* teks di tengah */
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 38px !important;
        }
    </style>
@endpush

@section('content')
    <div class="container py-4">
        <div class="row mb-3">
            <div class="col-md-3">
                <label for="start_date" class="form-label">Tanggal Mulai</label>
                <input type="date" id="start_date" class="form-control">
            </div>
            <div class="col-md-3">
                <label for="end_date" class="form-label">Tanggal Selesai</label>
                <input type="date" id="end_date" class="form-control">
            </div>
            <div class="col-md-3">
                <label for="status_filter" class="form-label">Status Pesanan</label>
                <select id="status_filter" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="pending">Pending</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button class="btn btn-primary w-100" onclick="applyFilters()">Filter</button>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button class="btn btn-success w-100" onclick="exportExcel()">Export Excel</button>
            </div>

        </div>

        <div class="card">
            <div class="card-header">
                <h5>Pesanan Masuk</h5>
            </div>
            <div class="card-body">
                <div id="orders-container">
                    @forelse ($orders as $order)
                        <div class="mb-4 border rounded p-3 order-block" data-order-id="{{ $order->id }}"
                            data-order-date="{{ $order->created_at->format('Y-m-d') }}"
                            data-order-status="{{ $order->status }}">
                            <h6>Pesanan dari: {{ optional($order->user)->name ?? 'User tidak ditemukan' }}</h6>
                            <p><strong>Tanggal:</strong> {{ $order->created_at->format('d-m-Y H:i') }}</p>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nama Lapangan</th>
                                        <th>Harga</th>
                                        <th>Jam Mulai</th>
                                        <th>Jam Selesai</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->orderItems as $item)
                                        <tr>
                                            <td>{{ $item->field_name }}</td>
                                            <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                            <td>{{ $item->slot_start }}</td>
                                            <td>{{ $item->slot_end }}</td>
                                            <td>
                                                <span
                                                    class="badge
                                                        {{ $order->status === 'completed'
                                                            ? 'bg-success'
                                                            : ($order->status === 'pending'
                                                                ? 'bg-warning'
                                                                : ($order->status === 'cancelled'
                                                                    ? 'bg-danger'
                                                                    : 'bg-secondary')) }}">
                                                    {{ ucfirst($order->status) }}
                                                </span>

                                                @if ($order->status === 'completed')
                                                    <button class="btn btn-sm btn-info ms-2"
                                                        onclick="cetakInvoice('{{ $order->id }}')">
                                                        Cetak
                                                    </button>
                                                @endif
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @empty
                        <p class="text-center">Belum ada pesanan.</p>
                    @endforelse
                </div>

                <!-- PAGINATION MANUAL DENGAN JAVASCRIPT -->
                <nav class="mt-4">
                    <ul class="pagination justify-content-center" id="pagination"></ul>
                </nav>

            </div>
        </div>
    </div>
@endsection
@push('plugin-scripts')
    <script src="{{ asset('admin/assets/plugins/chartjs/chart.min.js') }}"></script>
    <script src="{{ asset('admin/assets/plugins/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
@endpush
@push('custom-scripts')
    <script src="{{ asset('admin/assets/js/dashboard.js') }}"></script>
    <script>
        const itemsPerPage = 10;
        let currentPage = 1;

        function applyFilters() {
            const start = document.getElementById('start_date').value;
            const end = document.getElementById('end_date').value;
            const status = document.getElementById('status_filter').value;

            const orders = document.querySelectorAll('.order-block');

            orders.forEach(order => {
                const orderDate = order.getAttribute('data-order-date');
                const orderStatus = order.getAttribute('data-order-status');

                const matchDate = (!start || orderDate >= start) && (!end || orderDate <= end);
                const matchStatus = (!status || orderStatus === status);

                if (matchDate && matchStatus) {
                    order.style.display = 'block';
                } else {
                    order.style.display = 'none';
                }
            });

            // Setelah filter, reset pagination ke page 1
            currentPage = 1;
            paginateOrders();
        }

        function paginateOrders() {
            const allOrders = Array.from(document.querySelectorAll('.order-block')).filter(order => order.style.display ===
                'block');
            const totalPages = Math.ceil(allOrders.length / itemsPerPage);

            // Sembunyikan semua dulu
            allOrders.forEach(order => order.style.display = 'none');

            // Tampilkan hanya sesuai halaman
            const start = (currentPage - 1) * itemsPerPage;
            const end = start + itemsPerPage;

            allOrders.slice(start, end).forEach(order => {
                order.style.display = 'block';
            });

            renderPagination(totalPages);
        }

        function renderPagination(totalPages) {
            const pagination = document.getElementById('pagination');
            pagination.innerHTML = '';

            for (let i = 1; i <= totalPages; i++) {
                pagination.innerHTML += `
            <li class="page-item ${i === currentPage ? 'active' : ''}">
                <button class="page-link" onclick="gotoPage(${i})">${i}</button>
            </li>
        `;
            }
        }

        function gotoPage(page) {
            currentPage = page;
            paginateOrders();
        }

        // Jalankan pertama kali
        document.addEventListener('DOMContentLoaded', function() {
            paginateOrders();
        });

        function initSelect2() {
            $('.select2').select2({
                placeholder: "Silahkan Pilih",
                allowClear: true,
                width: '100%' // sangat penting supaya select mengikuti parent container
            });
        }
    </script>
    <script>
        function cetakInvoice(orderId) {
            const orderElement = document.querySelector(`.order-block[data-order-id='${orderId}']`);
            if (!orderElement) {
                alert('Data pesanan tidak ditemukan.');
                return;
            }

            let html = `
            <html>
            <head>
                <title>Invoice #${orderId}</title>
                <style>
                    body { font-family: Arial, sans-serif; padding: 20px; }
                    h2 { text-align: center; }
                    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                    th, td { border: 1px solid #333; padding: 8px; text-align: left; }
                    th { background-color: #eee; }
                </style>
            </head>
            <body>
                <h2>Invoice #${orderId}</h2>
                <p>Tanggal: ${orderElement.getAttribute('data-order-date')}</p>
                <table>
                    <thead>
                        <tr>
                            <th>Nama Lapangan</th>
                            <th>Harga</th>
                            <th>Jam Mulai</th>
                            <th>Jam Selesai</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            const rows = orderElement.querySelectorAll('tbody tr');
            rows.forEach(row => {
                const columns = row.querySelectorAll('td');
                html += `
                    <tr>
                        <td>${columns[0]?.innerText || ''}</td>
                        <td>${columns[1]?.innerText || ''}</td>
                        <td>${columns[2]?.innerText || ''}</td>
                        <td>${columns[3]?.innerText || ''}</td>
                    </tr>
                `;
            });

            html += `
                    </tbody>
                </table>
            </body>
            </html>
            `;

            // Buka window baru dan print
            const invoiceWindow = window.open('', '_blank');
            invoiceWindow.document.write(html);
            invoiceWindow.document.close();
            invoiceWindow.focus();
            invoiceWindow.print();
        }

        function exportExcel() {
            const start = document.getElementById('start_date').value;
            const end = document.getElementById('end_date').value;
            const status = document.getElementById('status_filter') ? document.getElementById('status_filter').value : '';

            let url = '{{ route('vendor.orders.export') }}?';

            if (start) url += `start_date=${start}&`;
            if (end) url += `end_date=${end}&`;
            if (status) url += `status=${status}&`;

            window.location.href = url;
        }
    </script>
@endpush
