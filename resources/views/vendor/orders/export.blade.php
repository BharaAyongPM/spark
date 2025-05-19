<table>
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Nama Pemesan</th>
            <th>Nama Lapangan</th>
            <th>Harga</th>
            <th>Jam Mulai</th>
            <th>Jam Selesai</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($orders as $order)
            @foreach ($order->orderItems as $item)
                <tr>
                    <td>{{ $order->created_at->format('d-m-Y H:i') }}</td>
                    <td>{{ optional($order->user)->name ?? '-' }}</td>
                    <td>{{ $item->field->name ?? '-' }}</td>
                    <td>{{ $item->price ?? 0 }}</td>
                    <td>{{ $item->slot_start ?? '-' }}</td>
                    <td>{{ $item->slot_end ?? '-' }}</td>
                    <td>{{ ucfirst($order->status) }}</td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>
