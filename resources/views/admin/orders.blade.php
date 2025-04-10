{{-- resources/views/admin/orders.blade.php --}}
<x-layout bodyClass="g-sidenav-show bg-gray-200">
    <x-navbars.sidebar activePage="orders"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-navbars.navs.auth titlePage="Order Management"></x-navbars.navs.auth>
        <div class="container-fluid py-4">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Orders Overview</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="ordersTable" class="table align-items-center table-striped">
                            <thead class="bg-success text-white">
                                <tr>
                                    <th>User</th>
                                    <th>Field</th>
                                    <th>Slot Start</th>
                                    <th>Slot End</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    @foreach ($order->orderItems as $item)
                                        <tr>
                                            <td>{{ $order->owner_name }}</td>
                                            <td>{{ $item->field_name }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->slot_start)->format('d M Y, H:i') }}
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($item->slot_end)->format('d M Y, H:i') }}</td>
                                            <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                            <td>
                                                <span
                                                    class="badge {{ $order->payment_status === 'paid' ? 'bg-success' : 'bg-danger' }}">
                                                    {{ strtoupper($order->payment_status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @push('css')
        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    @endpush

    @push('js')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#ordersTable').DataTable({
                    "order": [
                        [0, "asc"]
                    ],
                    "lengthMenu": [10, 25, 50, 100],
                    "pageLength": 10,
                    "language": {
                        "lengthMenu": "Tampilkan _MENU_ data per halaman",
                        "zeroRecords": "Tidak ada data yang ditemukan",
                        "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                        "infoEmpty": "Tidak ada data tersedia",
                        "infoFiltered": "(difilter dari _MAX_ total data)",
                        "search": "Cari:",
                        "paginate": {
                            "next": "Berikutnya",
                            "previous": "Sebelumnya"
                        }
                    }
                });
            });
        </script>
    @endpush
</x-layout>
