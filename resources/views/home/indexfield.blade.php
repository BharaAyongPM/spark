@extends('layouts.app')

@section('title', 'Home - MyApp')
@section('content')
    <section class="hero-section shadow-md">
        <div class="col-md-6 hero-text">
            <h2><span class="terra">TERRA SPAR</span> <span class="x">X</span> Hadir untuk membantu anda mencari tempat
                olahraga dan nongkrong, nih</h2>
        </div>
        <div class="col-md-6 hero-img">
            <img src="{{ asset('assets/img/devices.png') }}" alt="Devices" width="350">
        </div>
    </section>
    <section class="search-venue-section">
        <div class="search-form">
            <input type="text" class="form-control vanue" placeholder="Cari nama venue">
            <select class="form-control">
                <option>Pilih kota</option>
            </select>
            <select class="form-control">
                <option>Pilih Cabang Olahraga</option>
            </select>
            <button class="btn btn-primary">Cek Venue</button>
        </div>

        <hr>
        <div class="venue-list row mt-4">
            @foreach ($fields as $field)
                <div class="col-md-3">
                    <div class="venue-item">
                        <img src="{{ asset('storage/' . $field->foto) }}" class="img-fluid vanue-img" alt="Lapangan">
                        <div class="details">
                            <span class="jenis">Vanue</span>
                            <h5>{{ $field->name }}</h5>
                            <p>⭐⭐⭐⭐⭐| {{ $field->location }}</p>
                            <span class="jenis"><i class="fa fa-futbol"></i> {{ $field->deskripsi }}</span>
                            <p>Mulai dari <strong style="color: #003f88;">Rp. 1.500.000,- / Sesi</strong></p>
                            <a href="{{ route('home.field', $field->id) }}" class="btn btn-primary">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pagination-container mt-4">
            <nav>
                <ul class="pagination">
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">4</a></li>
                    <li class="page-item"><a class="page-link" href="#">5</a></li>
                </ul>
            </nav>
        </div>
    </section>

@endsection
