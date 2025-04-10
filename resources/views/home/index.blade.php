@extends('layouts.app')

@section('title', 'Home - MyApp')
@section('content')
    <section class="banner-section"></section>

    <section class="search-section">
        <div class="search-item">
            <div class="icon-container">
                <i class="fa fa-futbol"></i>
            </div>
            <div class="tanggal-select">
                <h6>Aktivitas</h6>
                <select>
                    <option>Pilih Aktivitas</option>
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
                <select>
                    <option>Pilih kota</option>
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
                <select>
                    <option>Pilih waktu</option>
                </select>
            </div>
        </div>
        <div class="garis-vertikal2"></div>
        <a href="/lapangan" class="btn btn-primary search-button">
            Temukan <i class="fas fa-arrow-right"></i>
        </a>

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
                <div class="col-md-6 text-start">
                    <img src="{{ asset('assets/img/cta-person.png') }}" alt="Person">
                </div>
                <div class="col-md-6 text-start">
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
                    <p class="text-white btn btn-primary rounded-5 mb-5">Minggu, 20 September 2024</p> <br>
                    <a href="#" class="btn btn-warning mb-3 rounded-5">Baca Selengkapnya</a>
                </div>
            </div>

            <!-- Slider for secondary news -->
            <div id="newsCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <!-- Slide 1 -->
                    <div class="carousel-item active">
                        <div class="row">
                            <div class="col-md-4 col-12 mb-4">
                                <div class="news-card">
                                    <img src="{{ asset('assets/img/news2.png') }}" alt="Latihan Angkat Beban"
                                        class="img-fluid mb-3">
                                    <h4>Latihan Angkat Beban Dapat Memperpanjang Usia?</h4>
                                    <p class="text-muted">Sabtu, 21 September 2024</p>
                                </div>
                            </div>
                            <div class="col-md-4 col-12 mb-4">
                                <div class="news-card">
                                    <img src="{{ asset('assets/img/news3.png') }}" alt="Buah untuk Sepakbola"
                                        class="img-fluid mb-3">
                                    <h4>4 Buah ini Dapat Membantu Olahraga Sepakbola!</h4>
                                    <p class="text-muted">Minggu, 22 September 2024</p>
                                </div>
                            </div>
                            <div class="col-md-4 col-12 mb-4">
                                <div class="news-card">
                                    <img src="{{ asset('assets/img/news4.png') }}" alt="Olahraga di Tempat Kerja"
                                        class="img-fluid mb-3">
                                    <h4>Haruskah olahraga di tempat kerja menjadi keharusan?</h4>
                                    <p class="text-muted">Senin, 23 September 2024</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Carousel controls -->
                <button class="carousel-control-prev" type="button" data-bs-target="#newsCarousel"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#newsCarousel"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </section>
@endsection
