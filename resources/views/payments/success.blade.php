<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-body text-center">
            <h3 class="text-success"><i class="fas fa-check-circle"></i> Pembayaran Berhasil!</h3>
            <p>Terima kasih telah melakukan pembayaran. Order Anda sedang diproses.</p>

            <!-- Rincian Order -->
            <div class="card mt-4 text-start">
                <div class="card-header bg-light">
                    <h5>Detail Invoice</h5>
                </div>
                <div class="card-body">
                    <p><strong>Invoice:</strong> {{ $order->invoice_number }}</p>
                    <p><strong>Tanggal:</strong>
                        {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y, H:i') }}</p>
                    <p><strong>Total Pembayaran:</strong> Rp
                        {{ number_format($order->total_price, 0, ',', '.') }}
                    </p>
                    <p><strong>Status:</strong> <span
                            class="badge bg-success">{{ strtoupper($order->payment_status) }}</span></p>
                </div>
            </div>

            <!-- Tombol Cetak Invoice -->
            <button class="btn btn-primary mt-3" onclick="printInvoice()"><i class="fas fa-print"></i> Cetak
                Invoice</button>
            <a href="{{ route('home.index') }}" class="btn btn-secondary mt-3">Kembali ke Beranda</a>
        </div>
    </div>
</div>

<!-- Script untuk Cetak Invoice -->
<script>
    function printInvoice() {
        var printContent = document.querySelector('.card.mt-4').innerHTML;
        var originalContent = document.body.innerHTML;
        document.body.innerHTML = printContent;
        window.print();
        document.body.innerHTML = originalContent;
        location.reload();
    }
</script>
