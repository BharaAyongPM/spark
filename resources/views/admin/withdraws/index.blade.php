@extends('vendor.layout.master')

@push('plugin-styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
@endpush

@section('content')
    <div class="container mt-4">
        <h4 class="mb-3">Data Withdraw Vendor</h4>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="withdrawsTable" class="table table-striped">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>#</th>
                                <th>Vendor</th>
                                <th>Jumlah Withdraw</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($withdraws as $index => $withdraw)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $withdraw->user->name }}</td>
                                    <td>Rp {{ number_format($withdraw->amount, 0, ',', '.') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($withdraw->created_at)->format('d M Y, H:i') }}</td>
                                    <td>
                                        <span
                                            class="badge
                                            @if ($withdraw->status == 'pending') bg-warning
                                            @elseif($withdraw->status == 'processing') bg-primary
                                            @elseif($withdraw->status == 'completed') bg-success
                                            @elseif($withdraw->status == 'cancelled') bg-danger @endif">
                                            {{ ucfirst($withdraw->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <form action="{{ route('admin.withdraws.updateStatus', $withdraw->id) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="status" value="processing">
                                            <button type="submit" class="btn btn-sm btn-primary mb-1">Set Proses</button>
                                        </form>
                                        <form action="{{ route('admin.withdraws.updateStatus', $withdraw->id) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="status" value="completed">
                                            <button type="submit" class="btn btn-sm btn-success mb-1">Set Selesai</button>
                                        </form>
                                        <form action="{{ route('admin.withdraws.updateStatus', $withdraw->id) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="status" value="cancelled">
                                            <button type="submit" class="btn btn-sm btn-danger mb-1">Cancel</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('plugin-scripts')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
@endpush

@push('custom-scripts')
    <script>
        $(document).ready(function() {
            $('#withdrawsTable').DataTable({
                "language": {
                    "lengthMenu": "Tampilkan _MENU_ data per halaman",
                    "zeroRecords": "Tidak ada data ditemukan",
                    "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                    "infoEmpty": "Tidak ada data tersedia",
                    "infoFiltered": "(disaring dari _MAX_ total data)",
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
