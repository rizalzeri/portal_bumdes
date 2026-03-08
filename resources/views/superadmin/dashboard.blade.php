@extends('layouts.admin')
@section('title', 'Super Admin Dashboard')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Dashboard Super Admin</h2>
            <p class="text-gray-500 text-sm mt-1">Ringkasan aktivitas dan metrik aplikasi Portal BUMDesa.</p>
        </div>
        <div>
            <span class="inline-flex bg-primary text-white rounded-md px-3 py-2 text-sm font-semibold shadow-sm">
                <i class="fa-solid fa-calendar mr-2"></i> {{ date('d F Y') }}
            </span>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border p-6 flex items-center">
            <div class="rounded-full bg-blue-100 p-4 mr-4">
                <i class="fa-solid fa-building-flag text-2xl text-blue-600"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium uppercase tracking-wider">Total BUMDesa Aktif</p>
                <h3 class="text-3xl font-extrabold text-gray-800">{{ $stats['total_bumdes_aktif'] }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border p-6 flex items-center">
            <div class="rounded-full bg-green-100 p-4 mr-4">
                <i class="fa-solid fa-users-gear text-2xl text-green-600"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium uppercase tracking-wider">Admin Kabupaten</p>
                <h3 class="text-3xl font-extrabold text-gray-800">{{ $stats['total_admin_kabupaten'] }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border p-6 flex items-center">
            <div class="rounded-full bg-accent bg-opacity-20 p-4 mr-4">
                <i class="fa-solid fa-map-location-dot text-2xl text-accent"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium uppercase tracking-wider">Kabupaten Terdaftar</p>
                <h3 class="text-3xl font-extrabold text-gray-800">{{ $stats['total_kabupaten'] }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border p-6 flex items-center">
            <div class="rounded-full bg-purple-100 p-4 mr-4">
                <i class="fa-solid fa-crown text-2xl text-purple-600"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium uppercase tracking-wider">Langganan Aktif</p>
                <h3 class="text-3xl font-extrabold text-gray-800">{{ $stats['langganan_aktif'] }}</h3>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Chart Section -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border p-6">
            <div class="flex justify-between items-center mb-6 border-b pb-4">
                <h3 class="text-lg font-bold text-gray-800"><i class="fa-solid fa-chart-area text-primary mr-2"></i> Grafik
                    Pendaftaran BUMDesa</h3>
            </div>
            <div class="relative h-72 w-full">
                <canvas id="registrationChart"></canvas>
            </div>
        </div>

        <!-- Latest Registrations -->
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-6 border-b pb-4"><i class="fa-solid fa-bolt text-accent mr-2"></i>
                Pendaftaran BUMDesa Terbaru</h3>

            @if ($latestBumdes->isEmpty())
                <div class="text-center py-8">
                    <p class="text-gray-500">Belum ada BUMDesa yang terdaftar.</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach ($latestBumdes as $bd)
                        <div class="flex items-start">
                            <div
                                class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center border font-bold text-primary flex-shrink-0">
                                {{ substr($bd->name, 0, 1) }}
                            </div>
                            <div class="ml-3">
                                <p
                                    class="text-sm font-bold text-gray-900 border-b-2 border-transparent hover:border-accent inline-block cursor-pointer transition">
                                    {{ $bd->name }}</p>
                                <p class="text-xs text-gray-500">{{ $bd->kabupaten->name ?? '-' }},
                                    {{ $bd->kabupaten->province->name ?? '-' }}</p>
                                <p class="text-[10px] text-gray-400 mt-1"><i class="fa-regular fa-clock"></i>
                                    {{ $bd->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-6 border-t pt-4">
                    <a href="{{ route('superadmin.user.index') }}"
                        class="text-sm font-medium text-primary hover:text-accent w-full text-center block">Lihat Semua
                        Pengguna <i class="fa-solid fa-arrow-right text-xs ml-1"></i></a>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const ctx = document.getElementById('registrationChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: 'BUMDesa Baru',
                    data: {!! json_encode($chartValues) !!},
                    borderColor: '#1e3a5f',
                    backgroundColor: 'rgba(30, 58, 95, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
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
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endpush
