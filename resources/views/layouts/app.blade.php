<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SparX Landing Page</title>

    {{-- Bootstrap & Fonts --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pontano+Sans&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    {{-- Custom --}}
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/">
                <img src="{{ asset('assets/img/logo.png') }}" alt="SparX">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="/">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="/lapangan">Venue</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Berita</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Jelajah</a></li>
                    <li class="nav-item d-flex align-items-center">
                        <div class="collapse navbar-collapse" id="navbarNav">
                            <ul class="navbar-nav ms-auto">
                                <li class="nav-item cart-icon" onclick="toggleCart()">
                                    <i class="fas fa-shopping-cart fa-lg"></i>
                                    <span class="cart-badge" id="cart-count">0</span>
                                </li>
                            </ul>
                        </div>
                    </li>
                    @guest
                        <li class="nav-item ms-3">
                            <a class="btn btn-daftar" data-bs-toggle="modal" data-bs-target="#DaftarModal">Daftar</a>
                        </li>
                        <li class="nav-item ms-2">
                            <a class="btn btn-masuk" data-bs-toggle="modal" data-bs-target="#loginModal">Masuk</a>
                        </li>
                    @else
                        <li class="nav-item dropdown ms-3">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown"
                                role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle fa-lg me-1"></i>
                                <span>{{ Auth::user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li class="dropdown-item-text">
                                    <strong>{{ Auth::user()->name }}</strong><br>
                                    <small>{{ Auth::user()->email }}</small>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>

                                <li>
                                    <a href="/orders" class="dropdown-item">pesanan</a>
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    {{-- Konten halaman --}}
    @yield('content')

    {{-- Modal Login --}}
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" action="{{ route('login') }}" class="modal-content">
                @csrf
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="loginModalLabel">Masuk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Belum punya akun? <a href="#" class="text-primary" data-bs-toggle="modal"
                            data-bs-target="#DaftarModal" data-bs-dismiss="modal">Daftar</a></p>

                    <div class="mb-3">
                        <input type="text" name="email_or_phone" class="form-control"
                            placeholder="Email atau Nomor HP" required>
                    </div>
                    <div class="mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Kata Sandi"
                            required>
                    </div>
                    <button class="btn btn-primary w-100">Masuk</button>

                    <div class="text-center mt-3 mb-2 text-muted">atau</div>
                    <a href="{{ route('login.google', 'PENYEWA') }}" class="btn btn-outline-danger w-100 mb-2">
                        <i class="fab fa-google me-2"></i> Masuk dengan Google
                    </a>
                    <a href="{{ route('login.facebook') }}" class="btn btn-outline-primary w-100">
                        <i class="fab fa-facebook me-2"></i> Masuk dengan Facebook
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Daftar --}}
    <div class="modal fade" id="DaftarModal" tabindex="-1" aria-labelledby="DaftarModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content px-3 pt-3 pb-4">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="DaftarModalLabel">Daftar sebagai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p>Pilih jenis akun yang ingin kamu buat</p>
                    <a href="{{ route('register.penyewa') }}" class="btn btn-primary w-100 mb-3">Daftar Sebagai
                        Penyewa</a>
                    <a href="{{ route('register.pemilik') }}" class="btn btn-outline-primary w-100">Daftar Sebagai
                        Pemilik Lapangan</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Sidebar Keranjang --}}
    <div class="cart-sidebar" id="cartSidebar">
        <div class="cart-header">
            <h5>Jadwal Dipilih</h5>
            <button onclick="toggleCart()" class="btn-close"></button>
        </div>
        <div class="cart-body" id="cart-items">
            <!-- JS akan isi di sini -->
        </div>
        <div class="cart-footer mt-3">
            <a href="{{ route('cart.checkout') }}" class="btn btn-primary w-100">Selanjutnya</a>
        </div>
    </div>

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
                        <div class="cart-item" data-cart-item="${key}">
                            <img src="/assets/img/fasilitas 1.png" alt="Lapangan">
                            <div class="item-info">
                                <strong>Lapangan ${item.fieldId}</strong>
                                <p>${item.date} | ${item.timeSlot}</p>
                                <span class="badge bg-primary">Rp. ${new Intl.NumberFormat('id-ID').format(item.price)}</span>
                            </div>
                            <i class="fas fa-trash-alt" style="cursor:pointer" onclick="removeCartItem('${key}', ${item.fieldId}, '${item.date}', '${item.timeSlot}')"></i>
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
                        }
                    });
            }

            document.querySelectorAll('.slot-item').forEach(slot => {
                slot.addEventListener('click', function() {
                    const fieldId = this.dataset.fieldId;
                    const date = this.dataset.date;
                    const timeSlot = this.dataset.timeSlot;
                    const price = this.dataset.price;

                    const slotKey = `${fieldId}-${date}-${timeSlot}`;

                    if (cart[slotKey]) {
                        removeCartItem(slotKey, fieldId, date, timeSlot);
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

            document.getElementById('cart-button').addEventListener('click', function() {
                toggleCart();
            });

            function toggleCart() {
                cartSidebar.classList.toggle('active');
            }
            window.toggleCart = toggleCart;

        });
    </script>

    {{-- JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleCart() {
            document.getElementById('cartSidebar').classList.toggle('active');
        }
    </script>

    @stack('js')
</body>

</html>
