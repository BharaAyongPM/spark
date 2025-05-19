@extends('vendor.layout.master')

@push('plugin-styles')
    {{-- DataTables CSS --}}

    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap5.min.css">
@endpush

@section('content')
    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-header pb-0 bg-success text-white">
                    <h6 class="mb-0">Orders Overview</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="ordersTable" class="table table-striped table-bordered dt-responsive nowrap"
                            style="width:100%">
                            <thead class="bg-success text-white">
                                <tr>
                                    <th style="width: 5%">No</th>
                                    <th>Nama</th>
                                    <th>Field</th>
                                    <th>Slot Start</th>
                                    <th>Slot End</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @foreach ($orders as $order)
                                    @foreach ($order->orderItems as $item)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ optional($order->user)->name ?? '-' }}</td>

                                            <td>{{ $item->field_name }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->slot_start)->format('d M Y, H:i') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->slot_end)->format('d M Y, H:i') }}</td>
                                            <td class="text-center">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                            <td class="text-center">
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
    </div>
@endsection

@push('plugin-scripts')
    {{-- DataTables JS --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap5.min.js"></script>
@endpush

@push('custom-scripts')
    <script>
        $(document).ready(function() {
            $('#ordersTable').DataTable({
                responsive: true,
                autoWidth: false,
                paging: true, // <-- Pagination aktif
                searching: true, // <-- Fitur pencarian aktif
                ordering: true, // <-- Sorting aktif
                lengthMenu: [10, 25, 50, 100],
                pageLength: 10,
                language: {
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    zeroRecords: "Tidak ada data yang ditemukan",
                    info: "Menampilkan halaman _PAGE_ dari _PAGES_",
                    infoEmpty: "Tidak ada data tersedia",
                    infoFiltered: "(difilter dari _MAX_ total data)",
                    search: "Cari:",
                    paginate: {
                        next: "Berikutnya",
                        previous: "Sebelumnya"
                    }
                }
            });
        });
    </script>
@endpush
