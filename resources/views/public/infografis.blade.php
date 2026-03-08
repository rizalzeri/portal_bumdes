@extends('layouts.public')
@section('title', 'Infografis Perkembangan BUMDesa')

@section('content')
    <div class="bg-primary pt-12 pb-24 text-center">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-extrabold text-white tracking-tight sm:text-5xl">Info Grafis Data BUMDesa</h1>
            <p class="mt-4 max-w-2xl text-xl text-blue-200 mx-auto">Pantau perkembangan dan capaian luar biasa Badan Usaha
                Milik Desa se-Indonesia.</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-16 relative z-10 mb-20">
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
            <!-- Summary Cards -->
            <div class="bg-white overflow-hidden shadow-xl rounded-2xl border-b-4 border-blue-500 relative group">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-100 rounded-xl p-4 group-hover:bg-blue-500 transition-colors">
                            <i class="fa-solid fa-building-flag text-2xl text-blue-600 group-hover:text-white"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate uppercase">Total BUMDesa Terdaftar
                                </dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-bold text-gray-900">
                                        {{ number_format($nationalData->total_bumdes ?? 0, 0, ',', '.') }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-xl rounded-2xl border-b-4 border-green-500 relative group">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-100 rounded-xl p-4 group-hover:bg-green-500 transition-colors">
                            <i class="fa-solid fa-circle-check text-2xl text-green-600 group-hover:text-white"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate uppercase">BUMDesa Aktif (Sertifikasi)
                                </dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-bold text-gray-900">
                                        {{ number_format($nationalData->bumdes_aktif ?? 0, 0, ',', '.') }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-xl rounded-2xl border-b-4 border-accent relative group">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-100 rounded-xl p-4 group-hover:bg-accent transition-colors">
                            <i class="fa-solid fa-coins text-2xl text-yellow-600 group-hover:text-white"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate uppercase">Total Pendapatan</dt>
                                <dd class="flex items-baseline">
                                    <div class="text-xl font-bold text-gray-900">Rp
                                        {{ number_format(floor(($nationalData->total_pendapatan ?? 0) / 1000), 0, ',', '.') }}
                                        K</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-xl rounded-2xl border-b-4 border-purple-500 relative group">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-purple-100 rounded-xl p-4 group-hover:bg-purple-500 transition-colors">
                            <i class="fa-solid fa-briefcase text-2xl text-purple-600 group-hover:text-white"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate uppercase">Total Unit Usaha</dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-bold text-gray-900">
                                        {{ number_format($nationalData->total_unit_usaha ?? 0, 0, ',', '.') }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Container -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">

            <!-- BUMDes Aktif vs Non-Aktif Chart -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                <h3 class="text-lg leading-6 font-bold text-gray-900 mb-4 text-center">Rasio Keaktifan BUMDesa</h3>
                <div class="relative h-72 w-full">
                    <canvas id="keaktifanChart"></canvas>
                </div>
            </div>

            <!-- Trend Aset Chart -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                <h3 class="text-lg leading-6 font-bold text-gray-900 mb-4 text-center">Pertumbuhan Aset (Miliar Rp)
                    2021-2025</h3>
                <div class="relative h-72 w-full">
                    <canvas id="trendAsetChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Provinsi Chart Full width -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 mb-8">
            <h3 class="text-lg leading-6 font-bold text-gray-900 mb-4">Sebaran BUMDesa Berdasarkan Provinsi (Top 10)</h3>
            <div class="relative h-96 w-full">
                <canvas id="provinsiChart"></canvas>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const total = {{ $nationalData->total_bumdes ?? 0 }};
            const aktif = {{ $nationalData->bumdes_aktif ?? 0 }};
            const nonAktif = total - aktif;

            // Pie Chart
            const ctxPie = document.getElementById('keaktifanChart').getContext('2d');
            new Chart(ctxPie, {
                type: 'doughnut',
                data: {
                    labels: ['BUMDesa Aktif (Tersertifikasi)', 'BUMDesa Registrasi / Belum Aktif'],
                    datasets: [{
                        data: [aktif, nonAktif],
                        backgroundColor: ['#22c55e', '#cbd5e1'],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Line Chart (Trend Data)
            const trendLabels = @json($trendLabels);
            const trendData = @json($trendData);

            const ctxLine = document.getElementById('trendAsetChart').getContext('2d');
            new Chart(ctxLine, {
                type: 'line',
                data: {
                    labels: trendLabels.length > 0 ? trendLabels : ['Data Belum Tersedia'],
                    datasets: [{
                        label: 'Total Aset BUMDesa (M)',
                        data: trendData.length > 0 ? trendData : [0],
                        borderColor: '#f59e0b',
                        backgroundColor: 'rgba(245, 158, 11, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#1e3a5f',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                borderDash: [2, 4],
                                color: '#e5e7eb'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            // Bar Chart (Provincial Data)
            const provLabels = @json($provLabels);
            const provData = @json($provData);

            const ctxBar = document.getElementById('provinsiChart').getContext('2d');
            new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: provLabels.length > 0 ? provLabels : ['Data Belum Tersedia'],
                    datasets: [{
                        label: 'Jumlah BUMDesa Terdaftar',
                        data: provData.length > 0 ? provData : [0],
                        backgroundColor: '#1e3a5f',
                        borderRadius: 6,
                        barPercentage: 0.7
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                borderDash: [2, 4]
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                maxRotation: 45,
                                minRotation: 45
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush
