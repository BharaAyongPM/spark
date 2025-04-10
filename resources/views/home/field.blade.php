@extends('layouts.app')

@section('title', 'Home - MyApp')
@section('content')
    @php
        $shareUrl = url()->current(); // atau url('route('nama.route'))
    @endphp
    <section class="title-page">
        <p><b>Beranda > Sewa Lapangan ></b> {{ $field->name }}</p>
    </section>

    <section class="banner-field">
        <div class="container">
            <div class="row">
                <div class="col-md-8 left-1">
                    <img src="{{ asset('assets/img/Rectangle 217.png') }}" alt="" id="banner-img">
                </div>
                <div class="col-md-4 right-2">
                    <div>
                        <img src="{{ asset('assets/img/stadion-2.png') }}" alt="">
                    </div>
                    <div>
                        <img src="{{ asset('assets/img/stadion-3.png') }}" alt="">
                    </div>

                </div>
            </div>
        </div>
    </section>

    <!-- Modal QR (gunakan Bootstrap modal agar konsisten) -->
    <div class="modal fade" id="qr-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center">
                <div class="modal-header">
                    <h5 class="modal-title">QR Code</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <canvas id="qrcode"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Popup Share -->
    <div id="popup-share" class="share-popup">
        <div class="share-popup-header">Bagikan ke:</div>
        <div class="share-options">
            <a href="#" class="social-btn fb" target="_blank"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="social-btn wa" target="_blank"><i class="fab fa-whatsapp"></i></a>
            <a href="#" class="social-btn tg" target="_blank"><i class="fab fa-telegram-plane"></i></a>
            <a href="#" class="social-btn x" target="_blank"><i class="fab fa-x-twitter"></i></a>
            <a href="#" class="social-btn link" title="Salin Link"><i class="fas fa-link"></i></a>
        </div>
    </div>

    <section class="detail-field">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <h3>{{ $field->name }}</h3>
                    <p>{{ $field->location }}</p>
                    <!-- Ikon Media Sosial -->
                    <!-- Ikon Share -->
                    <div class="icon-container">
                        <div class="icon-box" id="btn-main-share"><i class="fas fa-share-alt"></i></div>
                        <div class="icon-box" id="btn-qrcode"><i class="fas fa-qrcode"></i></div>
                        <div class="icon-box" onclick="window.open('https://www.instagram.com/sparx_id/', '_blank')">
                            <i class="fab fa-instagram"></i>
                        </div>
                    </div>

                    <!-- Label Sepak Bola -->
                    <div class="label-container">
                        <i class="fas fa-socks"></i> Sepak Bola
                    </div>
                    <hr>
                    <div class="deskripsi">
                        <p>
                            <b style="font-size: 14px;">Deskripsi</b>
                            <br>
                            {{ $field->deskripsi }}
                        </p>
                        <br>
                        <p>
                            <b style="font-size: 14px;">Aturan Vanue</b>
                        <ol>
                            {{ $field->syarat_ketentuan }}
                        </ol>
                        </p>
                        <div class="map-container position-relative">
                            <div id="map" style="width: 100%; height: 3000px; border-radius: 10px;"></div>
                            <div class="map-overlay"></div>
                            <a href="https://www.google.com/maps?q={{ $field->lat }},{{ $field->lon }}" target="_blank"
                                class="map-button">Lihat Map →</a>
                        </div>
                        <hr>
                        <!-- Bagian Fasilitas -->
                        <div class="fasilitas-container mb-4">
                            <b style="font-size: 14px;">Fasilitas</b>
                            <div class="fasilitas-list">
                                @forelse ($faciliti->facilities as $facility)
                                    <div class="fasilitas-item">
                                        <i class="{{ $facility->icon }}"></i>
                                        <span>{{ $facility->name }}</span>
                                    </div>
                                @empty
                                    <p class="text-muted">Tidak ada fasilitas tersedia</p>
                                @endforelse
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-md-4">
                    <!-- Kartu Harga -->
                    <div class="price-card">
                        <p>Mulai Dari</p>
                        <h2>Rp. {{ number_format($minPrice ?? 0, 0, ',', '.') }} / Sesi</h2>
                        <a href="#" class="btn btn-primary mt-2" data-bs-toggle="modal"
                            data-bs-target="#dateFilterModal">Cek Sekarang</a>

                    </div>
                    {{-- //modal  --}}
                    <!-- Modal Filter Tanggal -->
                    <div class="modal fade" id="dateFilterModal" tabindex="-1" aria-labelledby="dateFilterModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <form method="GET" action="{{ route('home.field', $field->id) }}" class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="dateFilterModalLabel">Pilih Rentang Tanggal</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Tutup"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="start_date" class="form-label">Tanggal Awal</label>
                                        <input type="date" class="form-control" id="start_date" name="start_date"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="end_date" class="form-label">Tanggal Akhir</label>
                                        <input type="date" class="form-control" id="end_date" name="end_date"
                                            required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Tampilkan Jadwal</button>
                                </div>
                            </form>
                        </div>
                    </div>


                    <!-- Pemilihan Tanggal -->
                    <form method="GET" action="{{ route('home.field', $field->id) }}">
                        <div class="d-flex gap-2 mb-3">
                            <input type="date" name="start_date" class="form-control"
                                value="{{ request('start_date') }}">
                            <input type="date" name="end_date" class="form-control"
                                value="{{ request('end_date') }}">
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                    </form>


                </div>
                <hr>
                <div class="booking-container mb-4">
                    <!-- Gambar Lapangan -->
                    <div class="image-container">
                        <img src="{{ asset('storage/' . $field->foto) }}" alt="Lapangan">
                    </div>

                    <!-- Informasi Lapangan -->
                    <div class="info-container">
                        <h2>{{ $field->name }}</h2>
                        <p>{{ $field->deskripsi }}</p>

                        <div class="icon">
                            <i class="fas fa-futbol px-2"> </i>
                            Sepak Bola
                        </div>
                        <!-- Slot Booking -->
                        <div class="slots-container">
                            @if (!empty($schedules))
                                @foreach ($schedules as $date => $slots)
                                    <div class="mb-3">
                                        <div class="date text-center">
                                            <strong>{{ \Carbon\Carbon::parse($date)->format('d M') }}</strong> <br>
                                            {{ \Carbon\Carbon::parse($date)->format('l') }}
                                        </div>

                                        @foreach ($slots as $slot)
                                            <div class="slot slot-item
                                                {{ $slot['status'] === 'available' ? 'bg-light' : '' }}
                                                {{ $slot['status'] === 'unavailable' ? 'bg-secondary text-white' : '' }}
                                                {{ $slot['status'] === 'booked' ? 'bg-danger text-white fw-bold' : '' }}
                                                {{ $slot['status'] === 'pending' ? 'bg-warning text-dark fw-bold' : '' }}"
                                                style="cursor: {{ $slot['status'] === 'available' ? 'pointer' : 'not-allowed' }};"
                                                data-field-id="{{ $field->id }}" data-date="{{ $date }}"
                                                data-time-slot="{{ $slot['time'] }}" data-price="{{ $slot['price'] }}"
                                                data-due-date="{{ isset($slot['due_date']) ? \Carbon\Carbon::parse($slot['due_date'])->toIso8601String() : '' }}">

                                                @if ($slot['status'] === 'booked')
                                                    <span style="font-size: 10px; color: grey;">BOOKED</span>
                                                @elseif ($slot['status'] === 'pending')
                                                    <span style="font-size: 10px; color: grey;">WAITING</span><br>
                                                    <span class="countdown-timer"
                                                        data-duedate="{{ $slot['due_date'] ?? '' }}"></span>
                                                @else
                                                    <span
                                                        style="font-size: 10px; color: grey;">{{ ucfirst($slot['status']) }}</span>
                                                @endif

                                                <div>{{ $slot['time'] }}</div>
                                                <div>Rp {{ number_format($slot['price'], 0, ',', '.') }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            @else
                                <p class="text-center text-muted">Jadwal belum tersedia.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <hr>
                <div class="venue-container">
                    <h2>Rekomendasi Venue</h2>

                    <div class="venue-grid">
                        <!-- Kartu Venue -->
                        <div class="venue-card">
                            <img src="{{ asset('assets/img/Rectangle 217.png') }}" alt="Venue">
                            <div class="venue-content">
                                <p>Venue</p>
                                <h3>Gelora Bung Karno, Jakarta</h3>
                                <div class="venue-rating">⭐⭐⭐⭐⭐ | Kota Jakarta Pusat</div>
                                <div class="venue-icon">
                                    <i class="fas fa-futbol px-2"> </i>
                                    Sepak Bola
                                </div>
                                <div class="venue-price">Mulai dari <b>Rp. 1.500.000,-</b> / Sesi</div>
                            </div>
                        </div>

                        <div class="venue-card">
                            <img src="{{ asset('assets/img/Rectangle 217.png') }}" alt="Venue">
                            <div class="venue-content">
                                <p>Venue</p>
                                <h3>Gelora Bung Karno, Jakarta</h3>
                                <div class="venue-rating">⭐⭐⭐⭐⭐ | Kota Jakarta Pusat</div>
                                <div class="venue-icon">
                                    <i class="fas fa-futbol px-2"> </i>
                                    Sepak Bola
                                </div>
                                <div class="venue-price">Mulai dari <b>Rp. 1.500.000,-</b> / Sesi</div>
                            </div>
                        </div>

                        <div class="venue-card">
                            <img src="{{ asset('assets/img/Rectangle 217.png') }}" alt="Venue">
                            <div class="venue-content">
                                <p>Venue</p>
                                <h3>Gelora Bung Karno, Jakarta</h3>
                                <div class="venue-rating">⭐⭐⭐⭐⭐ | Kota Jakarta Pusat</div>
                                <div class="venue-icon">
                                    <i class="fas fa-futbol px-2"> </i>
                                    Sepak Bola
                                </div>
                                <div class="venue-price">Mulai dari <b>Rp. 1.500.000,-</b> / Sesi</div>
                            </div>
                        </div>

                        <div class="venue-card">
                            <img src="{{ asset('assets/img/Rectangle 217.png') }}" alt="Venue">
                            <div class="venue-content">
                                <p>Venue</p>
                                <h3>Gelora Bung Karno, Jakarta</h3>
                                <div class="venue-rating">⭐⭐⭐⭐⭐ | Kota Jakarta Pusat</div>
                                <div class="venue-icon">
                                    <i class="fas fa-futbol px-2"> </i>
                                    Sepak Bola
                                </div>
                                <div class="venue-price">Mulai dari <b>Rp. 1.500.000,-</b> / Sesi</div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>



        @push('js')
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
            <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
            <script async defer src="https://buttons.github.io/buttons.js"></script>
            <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
            <script src="{{ asset('assets') }}/js/material-dashboard.min.js?v=3.0.0"></script>
            <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCPDhpdfT5jGYRK0u-AGGo17AyA7lV-I78"></script>
            <script>
                function initMap() {
                    const position = {
                        lat: {{ $field->lat }},
                        lng: {{ $field->lon }}
                    };
                    const map = new google.maps.Map(document.getElementById("map"), {
                        zoom: 15,
                        center: position,
                    });

                    const marker = new google.maps.Marker({
                        position: position,
                        map: map,
                        title: "{{ $field->name }}",
                    });
                }

                document.addEventListener('DOMContentLoaded', function() {
                    initMap();
                });
            </script>
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
                    const cartSidebar = document.getElementById('cartSidebar');

                    let cart = {};

                    function renderCartItems() {
                        cartItemsElement.innerHTML = '';
                        Object.entries(cart).forEach(([key, item]) => {
                            const itemHTML = `
                            <div class="cart-item d-flex justify-content-between align-items-center mb-2" data-cart-item="${key}">
                                <div>
                                    <strong>Lapangan ${item.fieldId}</strong><br>
                                    <small>${item.date} | ${item.timeSlot}</small><br>
                                    <span class="badge bg-primary">Rp. ${new Intl.NumberFormat('id-ID').format(item.price)}</span>
                                </div>
                                <i class="fas fa-trash-alt text-danger" style="cursor:pointer" onclick="removeCartItem('${key}', ${item.fieldId}, '${item.date}', '${item.timeSlot}')"></i>
                            </div>
                        `;
                            cartItemsElement.insertAdjacentHTML('beforeend', itemHTML);
                        });
                        cartCountElement.textContent = Object.keys(cart).length;
                    }

                    window.removeCartItem = function(key, fieldId, date, timeSlot) {
                        fetch('/cart/remove', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                },
                                body: JSON.stringify({
                                    field_id: fieldId,
                                    date,
                                    time_slot: timeSlot
                                }),
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    delete cart[key];
                                    renderCartItems();

                                    // ✅ Cari elemen slot dan ubah kelas warna
                                    const slotElement = document.querySelector(
                                        `.slot-item[data-field-id="${fieldId}"][data-date="${date}"][data-time-slot="${timeSlot}"]`
                                    );

                                    if (slotElement) {
                                        slotElement.classList.remove('bg-success');
                                        slotElement.classList.add('bg-light');
                                    }
                                }
                            });
                    }


                    // Saat klik jadwal (slot)
                    document.querySelectorAll('.slot-item').forEach(slot => {
                        console.log("🔍 Mengecek slot-item:", slot);

                        slot.addEventListener('click', function() {
                            console.log("🟢 Slot diklik:", slot);
                            if (!slot.classList.contains('bg-light'))
                                return; // hanya bisa klik yg available

                            const fieldId = this.dataset.fieldId;
                            const date = this.dataset.date;
                            const timeSlot = this.dataset.timeSlot;
                            const price = this.dataset.price;

                            const slotKey = `${fieldId}-${date}-${timeSlot}`;

                            // Jika sudah ada, hapus
                            if (cart[slotKey]) {
                                removeCartItem(slotKey, fieldId, date, timeSlot);
                                // slot.classList.remove('bg-success');
                                // slot.classList.add('bg-light');
                            } else {
                                fetch('/cart/add', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': document.querySelector(
                                                'meta[name="csrf-token"]').content,
                                        },
                                        body: JSON.stringify({
                                            field_id: fieldId,
                                            date,
                                            time_slot: timeSlot,
                                            price: price
                                        }),
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            slot.classList.add('bg-success');
                                            slot.classList.remove('bg-light');

                                            cart[slotKey] = {
                                                fieldId,
                                                date,
                                                timeSlot,
                                                price
                                            };
                                            renderCartItems();
                                        }
                                    });
                            }
                        });
                    });

                    // Tampilkan keranjang
                    document.getElementById('cart-button').addEventListener('click', function() {
                        cartSidebar.classList.toggle('active');
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

            <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const shareUrl = window.location.href;
                    const shareImage = document.getElementById("banner-img").src;
                    const qrBtn = document.getElementById("btn-qrcode");

                    // QR BUTTON
                    qrBtn.addEventListener("click", () => {
                        const qr = new QRious({
                            element: document.getElementById("qrcode"),
                            value: shareUrl,
                            size: 250
                        });
                        const modal = new bootstrap.Modal(document.getElementById('qr-modal'));
                        modal.show();
                    });
                });
            </script>

            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const shareUrl = encodeURIComponent(window.location.href);
                    const shareText = encodeURIComponent("Cek venue ini!");

                    const btnShare = document.getElementById('btn-main-share');
                    const popup = document.getElementById('popup-share');

                    btnShare.addEventListener('click', function(e) {
                        e.stopPropagation(); // Supaya tidak langsung ketutup oleh window click
                        if (popup.style.display === 'block') {
                            popup.style.display = 'none';
                        } else {
                            popup.style.display = 'block';
                            const rect = btnShare.getBoundingClientRect();
                            popup.style.left = rect.left + 'px';
                            popup.style.top = (rect.bottom + window.scrollY + 10) + 'px'; // Tambahkan scroll offset
                        }
                    });

                    // Close popup when clicking outside
                    window.addEventListener('click', function(e) {
                        if (!popup.contains(e.target) && e.target !== btnShare) {
                            popup.style.display = 'none';
                        }
                    });

                    // Set social URLs
                    document.querySelector('.social-btn.fb').href =
                        `https://www.facebook.com/sharer/sharer.php?u=${shareUrl}`;
                    document.querySelector('.social-btn.wa').href = `https://wa.me/?text=${shareText}%0A${shareUrl}`;
                    document.querySelector('.social-btn.tg').href =
                        `https://t.me/share/url?url=${shareUrl}&text=${shareText}`;
                    document.querySelector('.social-btn.x').href =
                        `https://twitter.com/intent/tweet?text=${shareText}&url=${shareUrl}`;

                    // Copy link
                    document.querySelector('.social-btn.link').addEventListener('click', function(e) {
                        e.preventDefault();
                        navigator.clipboard.writeText(window.location.href).then(() => {
                            alert('📋 Link berhasil disalin!');
                            popup.style.display = 'none';
                        });
                    });
                });
            </script>


        </section>
    @endpush


@endsection
