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
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    {{-- Custom --}}
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top fixed-top">
        <div class="container">
            <a class="navbar-brand" href="/">
                <img src="{{ asset('assets/img/logo.png') }}" alt="SparX">
            </a>
            <div class="d-flex align-items-center">
                <!-- ICON KERANJANG UNTUK MOBILE -->
                <div class="cart-icon me-2 d-lg-none" onclick="toggleCart()" style="cursor:pointer">
                    <i class="fas fa-shopping-cart fa-lg text-white"></i>
                    <span class="cart-badge" id="cart-count">0</span>
                </div>

                <!-- SEPARATOR -->
                <div class="vr mx-2 d-lg-none" style="height: 40px; background-color: rgba(255,255,255,0.5);"></div>


                <!-- HAMBURGER MENU -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="/">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="/lapangan">Venue</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Berita</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Jelajah</a></li>
                    <li class="nav-item d-md-flex align-items-center d-none d-md-block ">
                        <div class="collapse navbar-collapse" id="navbarNav">
                            <ul class="navbar-nav ms-auto">
                                <li class="nav-item cart-icon" onclick="toggleCart()">
                                    <i class="fas fa-shopping-cart fa-lg"></i>
                                    <span class="cart-badge" id="desktop-cart-count">0</span>
                                </li>
                            </ul>
                        </div>
                    </li>
                    @guest
                        <li class="nav-item ms-3 d-none d-md-block">
                            <a class="btn btn-daftar" data-bs-toggle="modal" data-bs-target="#DaftarModal">Daftar</a>
                        </li>
                        <li class="nav-item ms-2 d-none d-md-block">
                            <a class="btn btn-masuk" data-bs-toggle="modal" data-bs-target="#loginModal">Masuk</a>
                        </li>

                        <li class="nav-item d-lg-flex d-block d-lg-none w-100 justify-content-end gap-2 mt-2 mt-lg-0">
                            <a class="btn btn-daftar w-auto" data-bs-toggle="modal" data-bs-target="#DaftarModal">Daftar</a>
                            <a class="btn btn-masuk w-auto" data-bs-toggle="modal" data-bs-target="#loginModal">Masuk</a>
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
                                    <a href="/profile" class="dropdown-item">Profil</a>
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


    <footer class="footer-sparx text-white py-5">
        <div class="container-footer">
            <div class="row">
                <!-- Kolom 1 -->
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold">Perusahaan :</h5>
                    <ul class="list-unstyled mt-3">
                        <li><a href="/" class="footer-link">Beranda</a></li>
                        <li><a href="/lapangan" class="footer-link">Vanue</a></li>
                        <li><a href="/berita" class="footer-link">Berita</a></li>
                    </ul>
                </div>

                <!-- Kolom 2 -->
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold">Produk :</h5>
                    <ul class="list-unstyled mt-3">
                        <li><a href="/lapangan" class="footer-link">Vanue</a></li>
                        <li><a href="#" class="footer-link">Nobar</a></li>
                        <li><a href="#" class="footer-link">Shoping</a></li>
                    </ul>
                </div>

                <!-- Kolom 3 -->
                <div class="col-md-4 text-md-start text-center">
                    <img src="{{ asset('assets/img/logo.png') }}" alt="SparX" class="mb-3"
                        style="max-height: 45px;">
                    <div class="d-flex social-media">
                        <a href="https://www.instagram.com/sparx_id?igsh=MThyZGp6OGY2NTF1eg=="><img
                                src="{{ asset('assets/img/instagram (1).png') }}" alt=""></a>
                        <a href=""><img src="{{ asset('assets/img/facebook.png') }}" alt=""></a>
                        <a href="https://www.tiktok.com/@sparx_id?_t=ZS-8vmwDCq5DX7&_r=1"><img
                                src="{{ asset('assets/img/tik-tok.png') }}" alt=""></a>
                    </div>
                    <div class="d-md-flex">
                        <p class="fw-bold mb-2 mx-2">Powered By : </p>
                        <img src=" {{ asset('assets/img/logo bb1.png') }}" alt="Bisa Bola" class="mb-2"
                            style="max-height: 35px;">
                    </div>
                    <p class="mt-3 small">
                        Alamat : Jl. Panglima Polim No.116i, RT.01/RW06<br>
                        Kel. Melawai, Kec. Kebayoran Baru<br>
                        Jakarta Selatan, Indonesia, 12130
                    </p>
                </div>
            </div>
        </div>
    </footer>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cartCountMobile = document.getElementById('cart-count');
            const cartCountDesktop = document.getElementById('desktop-cart-count');
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

                const count = Object.keys(cart).length;
                if (cartCountMobile) cartCountMobile.textContent = count;
                if (cartCountDesktop) cartCountDesktop.textContent = count;
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
            };

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

            const cartButton = document.getElementById('cart-button');
            if (cartButton) {
                cartButton.addEventListener('click', function() {
                    toggleCart();
                });
            }

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

    <script>
        function goToStep(stepNumber, aktivitas = '') {
            document.querySelectorAll('.search-step').forEach(el => el.classList.add('d-none'));
            document.querySelector('.step-' + stepNumber).classList.remove('d-none');

            if (aktivitas) {
                console.log("Aktivitas dipilih: " + aktivitas);
                // kamu bisa simpan ini di localStorage atau form tersembunyi
            }
        }
    </script>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script>
        const swiper = new Swiper('.news-swiper', {
            loop: true,
            spaceBetween: 24,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            breakpoints: {
                0: {
                    slidesPerView: 1,
                },
                768: {
                    slidesPerView: 2,
                },
                992: {
                    slidesPerView: 3,
                }
            }
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            new Swiper(".bannerSwiper", {
                loop: true,
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true
                },
            });
        });
    </script>


    @stack('js')
</body>

</html>
