<x-layout bodyClass="g-sidenav-show bg-gray-200">
    <x-navbars.sidebar activePage="dashboard"></x-navbars.sidebar>

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-navbars.navs.auth titlePage="Dashboard Vendor"></x-navbars.navs.auth>

        <div class="container-fluid py-4">
            <div class="row">
                <!-- Box 1: Pesanan Terverifikasi -->
                <div class="col-xl-4 col-sm-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="text-muted">Pesanan Terverifikasi</h6>
                            <span class="h2 font-weight-bold mb-0">120</span>
                        </div>
                    </div>
                </div>

                <!-- Box 2: Pesanan Pending -->
                <div class="col-xl-4 col-sm-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="text-muted">Pesanan Pending</h6>
                            <span class="h2 font-weight-bold mb-0">45</span>
                        </div>
                    </div>
                </div>

                <!-- Box 3: Pesanan Batal -->
                <div class="col-xl-4 col-sm-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="text-muted">Pesanan Batal</h6>
                            <span class="h2 font-weight-bold mb-0">15</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Grafik Penjualan Lapangan Per Bulan -->
                <div class="col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <strong>Grafik Penjualan Lapangan Per Bulan</strong>
                        </div>
                        <div class="card-body">
                            <canvas id="chartPenjualan" style="width:100%; height:200px;"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Grafik Penghasilan Per Bulan -->
                <div class="col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <strong>Grafik Penghasilan Per Bulan</strong>
                        </div>
                        <div class="card-body">
                            <canvas id="chartPenghasilan" style="width:100%; height:200px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @push('js')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Data Dummy for Penjualan
                const penjualanData = {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Penjualan Lapangan',
                        data: [30, 40, 50, 60, 70, 80, 90, 85, 75, 65, 55, 45],
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                };

                // Data Dummy for Penghasilan
                const penghasilanData = {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Penghasilan',
                        data: [500, 700, 800, 900, 1000, 1200, 1400, 1300, 1250, 1100, 950, 850],
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }]
                };

                // Render Chart for Penjualan
                const ctxPenjualan = document.getElementById('chartPenjualan').getContext('2d');
                new Chart(ctxPenjualan, {
                    type: 'bar',
                    data: penjualanData,
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            }
                        }
                    }
                });

                // Render Chart for Penghasilan
                const ctxPenghasilan = document.getElementById('chartPenghasilan').getContext('2d');
                new Chart(ctxPenghasilan, {
                    type: 'line',
                    data: penghasilanData,
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            }
                        }
                    }
                });
            });
        </script>
    @endpush
</x-layout>
