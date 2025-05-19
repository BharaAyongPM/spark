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
        <form action="{{ route('home.indexfield') }}" method="GET" class="search-form">
            <input type="text" name="name" class="form-control vanue" placeholder="Cari nama venue"
                value="{{ request('name') }}">

            <select name="location_id" class="form-control">
                <option value="">Pilih kota</option>
                @foreach ($locations as $location)
                    <option value="{{ $location->id }}" {{ request('location_id') == $location->id ? 'selected' : '' }}>
                        {{ $location->name }}
                    </option>
                @endforeach
            </select>

            <select name="field_type_id" class="form-control">
                <option value="">Pilih Cabang Olahraga</option>
                @foreach ($fieldTypes as $type)
                    <option value="{{ $type->id }}" {{ request('field_type_id') == $type->id ? 'selected' : '' }}>
                        {{ $type->type_name }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-primary">Cek Venue</button>
        </form>

        @if (request()->filled('field_type_id') || request()->filled('location_id') || request()->filled('date'))
            <div class="alert alert-info mt-3" style="font-size: 14px;">
                Menampilkan hasil untuk
                @if (request()->filled('field_type_id'))
                    <strong>
                        {{ \App\Models\FieldType::find(request('field_type_id'))->type_name ?? 'Aktivitas tidak ditemukan' }}
                    </strong>
                @endif

                @if (request()->filled('location_id'))
                    di <strong>
                        {{ \App\Models\Location::find(request('location_id'))->name ?? 'Lokasi tidak ditemukan' }}
                    </strong>
                @endif

                @if (request()->filled('date'))
                    pada tanggal <strong>{{ \Carbon\Carbon::parse(request('date'))->format('d M Y') }}</strong>
                @endif
            </div>
        @endif

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
