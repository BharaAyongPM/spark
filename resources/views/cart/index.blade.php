@extends('layouts.app')

@section('title', 'Home - MyApp')
@section('content')
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header">
                <h5>Keranjang Anda</h5>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @if (empty($cart))
                    <p>Keranjang kosong.</p>
                @else
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Jam</th>
                                <th>Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cart as $index => $item)
                                <tr>
                                    <td>{{ $item['date'] }}</td>
                                    <td>{{ $item['time_slot'] }}</td>
                                    <td>Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                                    <td>
                                        <form action="{{ route('cart.checkout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-primary btn-sm">Checkout</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
    </main>
@endsection
