<x-layout bodyClass="g-sidenav-show bg-gray-200">
    <x-navbars.sidebar activePage="dashboard"></x-navbars.sidebar>

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-navbars.navs.auth titlePage="Dashboard Admin"></x-navbars.navs.auth>

        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-xl-4 col-sm-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="text-muted">Jumlah Penyewa</h6>
                            <span class="h2 font-weight-bold mb-0">{{ $countPenyewa }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-sm-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="text-muted">Jumlah Pemilik</h6>
                            <span class="h2 font-weight-bold mb-0">{{ $countPemilik }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-sm-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="text-muted">Jumlah Lapangan</h6>
                            <span class="h2 font-weight-bold mb-0">{{ $countFields }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grafik Pemesanan -->
            <div class="card">
                <div class="card-header">
                    <strong>Grafik Pemesanan Lapangan</strong>
                </div>
                <div class="card-body">
                    <canvas id="bookingChart" style="width:100%; max-width:600px; height:300px;"></canvas>
                </div>
            </div>
        </div>
    </main>

    @push('js')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var bookingData = @json($bookingData);
                var ctx = document.getElementById('bookingChart').getContext('2d');
                var bookingChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: Object.keys(bookingData),
                        datasets: [{
                            label: 'Jumlah Pemesanan',
                            data: Object.values(bookingData),
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true, // Membuat grafik responsif terhadap ukuran container
                        maintainAspectRatio: false, // Mencegah pengubahan rasio aspek
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            });
        </script>
    @endpush


</x-layout>
