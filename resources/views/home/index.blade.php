@extends('layouts.app')

@section('title', 'Home - MyApp')
@section('content')
    <section class="banner-section">

    </section>

    <form action="{{ route('home.indexfield') }}" method="GET">
        <section class="search-section">
            <div class="search-item">
                <div class="icon-container">
                    <i class="fa fa-futbol"></i>
                </div>
                <div class="tanggal-select">
                    <h6>Aktivitas</h6>
                    <select name="field_type_id" class="form-select">
                        <option value="">Pilih Aktivitas</option>
                        @foreach ($fieldTypes as $type)
                            <option value="{{ $type->id }}"
                                {{ request('field_type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->type_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="garis-vertikal"></div>

            <div class="search-item">
                <div class="icon-container">
                    <i class="fa fa-map-location-dot"></i>
                </div>
                <div class="tanggal-select">
                    <h6>Lokasi</h6>
                    <select name="location_id" class="form-select">
                        <option value="">Pilih kota</option>
                        @foreach ($locations as $location)
                            <option value="{{ $location->id }}"
                                {{ request('location_id') == $location->id ? 'selected' : '' }}>
                                {{ $location->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="garis-vertikal"></div>

            <div class="search-item">
                <div class="icon-container">
                    <i class="fa fa-calendar-days"></i>
                </div>
                <div class="tanggal-select">
                    <h6>Tanggal</h6>
                    <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                </div>
            </div>

            <div class="garis-vertikal2"></div>
            <button type="submit" class="btn btn-primary search-button">
                Temukan <i class="fas fa-arrow-right"></i>
            </button>

        </section>
    </form>

    <section class="search-wizard-mobile d-flex d-md-none flex-column p-3">
        <!-- STEP 1: Aktivitas -->
        <div class="search-step step-1">
            <p class="mb-2">Kegiatan apa yang kamu cari?</p>
            <div class="list-group">
                <button class="list-group-item" onclick="goToStep(2, 'Sewa Lapangan')">Sewa Lapangan</button>
                <button class="list-group-item" onclick="goToStep(2, 'Sparring')">Sparring</button>
                <button class="list-group-item" onclick="goToStep(2, 'Main Bareng')">Main Bareng</button>
            </div>
        </div>

        <!-- STEP 2: Lokasi -->
        <div class="search-step step-2 d-none">
            <p class="mb-2">Lokasi</p>
            <input type="text" class="form-control mb-3" placeholder="Contoh: Jakarta Selatan" id="inputLokasi">
            <button class="btn btn-primary" onclick="goToStep(3)">Lanjut</button>
        </div>

        <!-- STEP 3: Tanggal -->
        <div class="search-step step-3 d-none">
            <p class="mb-2">Tanggal</p>
            <input type="date" class="form-control mb-3" id="inputTanggal">
            <a href="/lapangan" class="btn btn-success w-100">Temukan</a>
        </div>
    </section>

    <section class="description d-flex">
        <p>
            <span class="spar">Spar</span><span class="x">X</span> adalah pengalaman olahraga dan gaya hidup
            interaktif pertama di Indonesia, yang menggabungkan Olahraga, Esports, Cafe, dan Retail.
        </p>
    </section>

    <section class="why-sparx">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h2>Kenapa <span class="spar">SPAR</span><span class="highlight">X</span></h2>
                <p>Dengan <span class="spar">Spar<span class="highlight">X</span></span>, merek Anda dapat terhubung
                    dengan ratusan ribu penggemar olahraga di Indonesia.</p>
                <ul>
                    <li>
                        <span class="icon-box">1</span>
                        <div>
                            <p class="why-text">Fasilitas Olahraga Terbaik</p>
                            <p class="why-description">Convenience with the best facilities</p>
                        </div>
                    </li>
                    <li>
                        <span class="icon-box">2</span>
                        <div>
                            <p class="why-text">Tenant Makanan dan Minuman</p>
                            <p class="why-description">Best Combination of sport and F&B</p>
                        </div>
                    </li>
                    <li>
                        <span class="icon-box">3</span>
                        <div>
                            <p class="why-text">Tenant Retail Olahraga dan Kesehatan</p>
                            <p class="why-description">Sport and Health equipment here</p>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="col-md-6 text-end">
                <div class="image-container">
                    <img src="{{ asset('assets/img/why-sparx.png') }}" alt="Kenapa SparX">

                </div>
            </div>
        </div>
    </section>
    <section class="cta-section">
        <div class="container">
            <div class="row">
                <div class="col-md-6 d-flex justify-content-center justify-content-md-start text-md-start">
                    <img src="{{ asset('assets/img/cta-person.png') }}" alt="Person">
                </div>
                <div class="col-md-6 justify-content-center justify-content-md-start text-md-start">
                    <p class="cta-text">Hadirnya <span class="spar">Spar</span><span class="x">X</span> memperkuat
                        hubungan antara Orang dan Tempat lewat <span>Olahraga</span></p>
                    <button class="cta-button">Cari Vanue</button>
                </div>
            </div>
        </div>
    </section>

    <section class="activities-section container">
        <div class="container">
            <h2>Yang bisa kamu lakukan bersama <span>SparX</span></h2>
            <div class="row">
                <div class="col-md-6 text-start">
                    <div class="activity-card">
                        <div>
                            <h5>Olahraga Dengan Banyak Pilihan</h5>
                            <ul>
                                <li>Sepakbola</li>
                                <li>Basketball</li>
                                <li>Padel Ball</li>
                                <li>Badminton</li>
                                <li>Gym</li>
                            </ul>
                        </div>
                        <img src="{{ asset('assets/img/sports.png') }}" alt="Olahraga">
                    </div>
                </div>
                <div class="col-md-6 text-start">
                    <div class="activity-card">
                        <div>
                            <h5>Bersantai, Makan & Minum</h5>
                            <p>Menikmati makanan dan bersantai di berbagai area yang mengagumkan sekitar.</p>
                        </div>
                        <img src="{{ asset('assets/img/food.png') }}" alt="Makanan">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="activity-card text-start">
                        <div>
                            <h5>Belanja Kebutuhan Olahragamu</h5>
                            <p>Adanya tenant olahraga modern yang menyediakan berbagai macam kebutuhan olahraga.</p>
                        </div>
                        <img src="{{ asset('assets/img/shopping.png') }}" alt="Belanja">
                    </div>
                </div>
                <div class="col-md-6 text-start">
                    <div class="activity-card">
                        <div>
                            <h5>Mengikuti Event Seru!</h5>
                            <ul>
                                <li>Turnamen Bola</li>
                                <li>Konten Boxing</li>
                                <li>Live Music</li>
                                <li>Pameran/Expo</li>
                                <li>Dan lainnya</li>
                            </ul>
                        </div>
                        <img src="{{ asset('assets/img/event.png') }}" alt="Event">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Main News Section -->
    <section class="main-news py-5 mx-auto">
        <div class="container">
            <!-- Main news article -->
            <div class="row align-items-center mb-5">
                <div class="col-md-7">
                    <img src="{{ asset('assets/img/news.png') }}" alt="Padelball" class="img-fluid rounded">
                </div>
                <div class="col-md-5">
                    <h2>Padelball Olahraga Baru yang Semakin Populer di Dunia!</h2>
                    <p class="text-white btn btn-primary rounded-5">Minggu, 6 April 2025</p> <br>
                    <a href="#" class="btn btn-warning mb-3 rounded-5">Baca Selengkapnya</a>
                </div>
            </div>
            <div class="swiper news-swiper">
                <div class="swiper-wrapper">
                    <!-- Slide 1 -->
                    <div class="swiper-slide">
                        <div class="news-card">
                            <img src="{{ asset('assets/img/news2.png') }}" alt="Latihan Angkat Beban"
                                class="img-fluid mb-3">
                            <h4>Latihan Angkat Beban Dapat Memperpanjang Usia?</h4>
                            <p class="text-muted">Sabtu, 5 April 2025</p>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="news-card">
                            <img src="{{ asset('assets/img/news3.png') }}" alt="Buah untuk Sepakbola"
                                class="img-fluid mb-3">
                            <h4>4 Buah ini Dapat Membantu Olahraga Sepakbola!</h4>
                            <p class="text-muted">Minggu, 6 April 2025</p>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="news-card">
                            <img src="{{ asset('assets/img/news4.png') }}" alt="Olahraga di Tempat Kerja"
                                class="img-fluid mb-3">
                            <h4>Haruskah olahraga di tempat kerja menjadi keharusan?</h4>
                            <p class="text-muted">Senin, 7 April 2025</p>
                        </div>
                    </div>
                </div>

                <!-- Navigasi -->
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>

                <!-- Pagination (opsional) -->
                <div class="swiper-pagination mt-3"></div>
            </div>

        </div>
    </section>
    <!-- Modal for source of information -->
    <div class="modal fade" id="modalSource" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Darimana Anda tahu tentang Sparx?</h5>
                </div>
                <div class="modal-body">
                    <select id="input-source" class="form-select" required>
                        <option value="">Pilih salah satu</option>
                        <option value="Sosial Media">Sosial Media</option>
                        <option value="Iklan">Iklan</option>
                        <option value="Teman">Teman</option>
                        <option value="Pencarian Web">Pencarian Web</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" onclick="showPreferenceModal()">Lanjut</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal for user preferences -->
    <div class="modal fade" id="modalPreference" tabindex="-1">
        <div class="modal-dialog">
            <form id="form-onboarding">
                @csrf
                <input type="hidden" name="source_info" id="hidden-source">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Pilih Olahraga Favorit Anda</h5>
                    </div>
                    <div class="modal-body">
                        @foreach (['Futsal', 'Sepak Bola', 'Badminton', 'Tenis', 'Basket', 'Padel', 'Volley'] as $sport)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="sports[]"
                                    value="{{ $sport }}" id="sport-{{ $sport }}">
                                <label class="form-check-label"
                                    for="sport-{{ $sport }}">{{ $sport }}</label>
                            </div>
                        @endforeach
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Simpan Preferensi</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            @if (auth()->check())
                @if (!$hasSource)
                    $('#modalSource').modal({
                        backdrop: 'static',
                        keyboard: false
                    }).modal('show');
                @elseif ($hasSource && !$hasPreference)
                    $('#modalPreference').modal({
                        backdrop: 'static',
                        keyboard: false
                    }).modal('show');
                @endif
            @endif
        });

        function showPreferenceModal() {
            let selected = $('#input-source').val();
            if (!selected) {
                alert('Pilih sumber informasi terlebih dahulu.');
                return;
            }
            // Simpan source ke hidden input untuk form onboarding
            $('#hidden-source').val(selected);

            // Tutup modal source ➔ Buka modal preference
            $('#modalSource').modal('hide');
            $('#modalPreference').modal({
                backdrop: 'static',
                keyboard: false
            }).modal('show');
        }

        $('#form-onboarding').submit(function(e) {
            e.preventDefault();

            $.ajax({
                url: '{{ route('user.saveOnboarding') }}',
                method: 'POST',
                data: $(this).serialize(),
                success: function(res) {
                    if (res.success) {
                        $('#modalPreference').modal('hide');
                        location.reload();
                    }
                },
                error: function() {
                    alert('Gagal menyimpan data. Pastikan semua terisi dengan benar.');
                }
            });
        });
    </script>
@endsection
