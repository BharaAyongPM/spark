<x-layout bodyClass="g-sidenav-show bg-gray-200">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid py-4">
            <!-- Tombol Keranjang -->
            <div class="d-flex justify-content-end mb-4">
                <button class="btn btn-primary position-relative" id="cart-button">
                    Keranjang <span id="cart-count"
                        class="badge bg-danger position-absolute top-0 start-100 translate-middle">0</span>
                </button>
            </div>

            <!-- Informasi Lapangan -->
            <div class="card mb-4">
                <img src="{{ asset('storage/' . $field->banner) }}" class="card-img-top" alt="Banner">
                <div class="card-body">
                    <h3 class="card-title">{{ $field->name }}</h3>
                    <p>{{ $field->deskripsi }}</p>
                </div>
            </div>

            <!-- Jadwal Booking -->
            <div class="card">
                <div class="card-header">Jadwal Booking (5 Hari ke Depan)</div>
                <div class="card-body">
                    @if (!empty($schedules))
                        <div class="row">
                            @foreach ($schedules as $date => $slots)
                                <div class="col-md-2 mb-4">
                                    <div class="card">
                                        <div class="card-header text-center bg-light">
                                            <strong>{{ \Carbon\Carbon::parse($date)->format('d M') }}</strong>
                                            <p>{{ \Carbon\Carbon::parse($date)->format('l') }}</p>
                                        </div>
                                        <div class="card-body">
                                            @foreach ($slots as $slot)
                                                <div class="mb-2 p-2 text-center border rounded slot-item
                                            {{ $slot['status'] === 'available' ? 'bg-light' : '' }}
                                            {{ $slot['status'] === 'unavailable' ? 'bg-secondary text-white' : '' }}
                                            {{ $slot['status'] === 'booked' ? 'bg-danger text-white fw-bold' : '' }}
                                            {{ $slot['status'] === 'pending' ? 'bg-warning text-dark fw-bold' : '' }}"
                                                    style="cursor: {{ $slot['status'] === 'available' ? 'pointer' : 'not-allowed' }}"
                                                    data-field-id="{{ $field->id }}"
                                                    data-date="{{ $date }}"
                                                    data-time-slot="{{ $slot['time'] }}"
                                                    data-price="{{ $slot['price'] }}"
                                                    data-due-date="{{ isset($slot['due_date']) ? \Carbon\Carbon::parse($slot['due_date'])->toIso8601String() : '' }}">


                                                    <span>{{ $slot['time'] }}</span><br>
                                                    <span>Rp
                                                        {{ number_format($slot['price'], 0, ',', '.') }}</span><br>

                                                    @if ($slot['status'] === 'booked')
                                                        <span class="text-uppercase">BOOKED</span>
                                                    @elseif ($slot['status'] === 'pending')
                                                        <span class="text-uppercase">
                                                            WAITING
                                                        </span>


                                                        <br>
                                                        <span class="countdown-timer"
                                                            data-duedate="{{ $slot['due_date'] ?? '' }}"></span>
                                                    @else
                                                        <span>{{ ucfirst($slot['status']) }}</span>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center text-muted">Jadwal belum tersedia.</p>
                    @endif
                </div>
            </div>


            <!-- Modal Keranjang -->
            <div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="cartModalLabel">Keranjang</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <ul id="cart-items" class="list-group">
                                <!-- Item akan diisi oleh JavaScript -->
                            </ul>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <a href="{{ route('cart.checkout') }}" class="btn btn-primary">Checkout</a>
                        </div>
                    </div>
                </div>
            </div>

            @push('js')
                <script>
                    function checkExpiredOrders() {
                        fetch('/check-expired-orders')
                            .then(response => response.json())
                            .then(data => console.log(data.message))
                            .catch(error => console.error("Error:", error));
                    }

                    // Jalankan setiap 1 menit (60000ms)
                    setInterval(checkExpiredOrders, 10000);
                </script>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const cartCountElement = document.getElementById('cart-count');
                        const cartItemsElement = document.getElementById('cart-items');
                        const cartModal = new bootstrap.Modal(document.getElementById('cartModal'));

                        // Keranjang di session
                        let cart = {};

                        // Event listener untuk setiap slot item
                        document.querySelectorAll('.slot-item').forEach(function(slotElement) {
                            slotElement.addEventListener('click', function() {
                                const slot = this;
                                const fieldId = slot.dataset.fieldId;
                                const date = slot.dataset.date;
                                const timeSlot = slot.dataset.timeSlot;
                                const price = slot.dataset.price;

                                const slotKey = `${fieldId}-${date}-${timeSlot}`;

                                // Jika sudah di keranjang, hapus
                                if (cart[slotKey]) {
                                    fetch('/cart/remove', {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'X-CSRF-TOKEN': document.querySelector(
                                                    'meta[name="csrf-token"]').content,
                                            },
                                            body: JSON.stringify({
                                                field_id: fieldId,
                                                date: date,
                                                time_slot: timeSlot,
                                            }),
                                        })
                                        .then(response => response.json())
                                        .then(data => {
                                            if (data.success) {
                                                // Ubah warna slot kembali ke default
                                                slot.classList.remove('bg-success');
                                                slot.classList.add('bg-light');

                                                // Hapus dari objek keranjang
                                                delete cart[slotKey];

                                                // Hapus item dari modal keranjang
                                                const item = document.querySelector(
                                                    `[data-cart-item="${slotKey}"]`);
                                                if (item) {
                                                    item.remove();
                                                }

                                                // Update jumlah keranjang
                                                cartCountElement.textContent = Object.keys(cart).length;
                                            }
                                        })
                                        .catch(error => console.error(
                                            'Error saat menghapus item dari keranjang:', error));
                                } else {
                                    // Jika belum di keranjang, tambahkan
                                    fetch('/cart/add', {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'X-CSRF-TOKEN': document.querySelector(
                                                    'meta[name="csrf-token"]').content,
                                            },
                                            body: JSON.stringify({
                                                field_id: fieldId,
                                                date: date,
                                                time_slot: timeSlot,
                                                price: price,
                                            }),
                                        })
                                        .then(response => response.json())
                                        .then(data => {
                                            if (data.success) {
                                                // Ubah warna slot menjadi hijau
                                                slot.classList.add('bg-success');
                                                slot.classList.remove('bg-light');

                                                // Tambahkan ke objek keranjang
                                                cart[slotKey] = {
                                                    fieldId,
                                                    date,
                                                    timeSlot,
                                                    price
                                                };

                                                // Tambahkan item ke modal keranjang
                                                const newItem = document.createElement('li');
                                                newItem.classList.add('list-group-item');
                                                newItem.setAttribute('data-cart-item', slotKey);
                                                newItem.textContent =
                                                    `Lapangan: ${fieldId}, Tanggal: ${date}, Waktu: ${timeSlot}, Harga: Rp ${new Intl.NumberFormat('id-ID').format(price)}`;
                                                cartItemsElement.appendChild(newItem);

                                                // Update jumlah keranjang
                                                cartCountElement.textContent = Object.keys(cart).length;
                                            }
                                        })
                                        .catch(error => console.error(
                                            'Error saat menambahkan item ke keranjang:', error));
                                }
                            });
                        });

                        // Tampilkan modal keranjang
                        document.getElementById('cart-button').addEventListener('click', function() {
                            cartModal.show();
                        });
                    });
                    document.addEventListener("DOMContentLoaded", function() {
                        const countdownElements = document.querySelectorAll('.countdown-timer');

                        countdownElements.forEach(timer => {
                            const dueDateStr = timer.dataset.duedate;
                            if (!dueDateStr) return;

                            const dueDate = new Date(dueDateStr).getTime();

                            function updateCountdown() {
                                const now = new Date().getTime();
                                const timeLeft = dueDate - now;

                                if (timeLeft <= 0) {
                                    timer.innerHTML = "EXPIRED";
                                    timer.closest('.slot-item').classList.remove('bg-warning', 'text-dark');
                                    timer.closest('.slot-item').classList.add('bg-light');
                                    return;
                                }

                                const hours = Math.floor(timeLeft / (1000 * 60 * 60));
                                const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
                                const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

                                if (hours > 0) {
                                    timer.innerHTML = `${hours}h ${minutes}m ${seconds}s`;
                                } else {
                                    timer.innerHTML = `${minutes}m ${seconds}s`;
                                }
                                setTimeout(updateCountdown, 1000);
                            }

                            updateCountdown();
                        });
                    });
                </script>
            @endpush
</x-layout>
