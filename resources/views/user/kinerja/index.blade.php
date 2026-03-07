@extends('layouts.admin')
@section('title', 'Kinerja BUMDesa')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Indikator Kinerja Keuangan</h2>
    <p class="text-gray-500 text-sm mt-1">Grafik perkembangan pendapatan dan laba bersih BUMDesa Anda berdasarkan laporan yang diunggah.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-gradient-to-br from-blue-500 to-blue-700 text-white rounded-xl shadow-sm border p-6">
        <h3 class="font-bold text-blue-100 text-sm mb-1 uppercase tracking-wider">Total Aset Saat Ini</h3>
        <p class="text-3xl font-bold font-mono">
            @php $latestAset = $bumdes->laporanKeuangan->last()->aset ?? 0; @endphp
            Rp {{ number_format($latestAset, 0, ',', '.') }}
        </p>
    </div>
    <div class="bg-gradient-to-br from-green-500 to-green-700 text-white rounded-xl shadow-sm border p-6">
        <h3 class="font-bold text-green-100 text-sm mb-1 uppercase tracking-wider">Rekap Laba Total (Sistem)</h3>
        <p class="text-3xl font-bold font-mono">
            Rp {{ number_format($bumdes->laporanKeuangan->sum('laba_bersih'), 0, ',', '.') }}
        </p>
    </div>
    <div class="bg-gradient-to-br from-purple-500 to-purple-700 text-white rounded-xl shadow-sm border p-6">
        <h3 class="font-bold text-purple-100 text-sm mb-1 uppercase tracking-wider">Klasifikasi Saat Ini</h3>
        <p class="text-3xl font-bold uppercase mt-1 tracking-widest">{{ $bumdes->klasifikasi ?? 'BELUM DINILAI' }}</p>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border p-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="font-bold text-gray-900"><i class="fa-solid fa-chart-area mr-2 text-primary"></i> Grafik Tren Finansial</h3>
    </div>
    
    @if(count($labels) > 0)
        <!-- Chart Container -->
        <div class="w-full" style="height: 400px;">
            <canvas id="kinerjaChart"></canvas>
        </div>
    @else
        <div class="text-center py-16 text-gray-400">
            <i class="fa-solid fa-chart-line text-6xl mb-4 text-gray-200"></i>
            <p class="text-lg">Belum ada data laporan keuangan yang cukup untuk menampilkan grafik.</p>
            <a href="{{ route('user.finansial.index') }}" class="inline-block mt-4 text-primary font-bold hover:underline">Unggah Laporan Keuangan</a>
        </div>
    @endif
</div>

@if(count($labels) > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('kinerjaChart').getContext('2d');
        const labels = @json($labels);
        const dataPendapatan = @json($pendapatanData);
        const dataLaba = @json($labaData);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Pendapatan Kotor (Rp)',
                        data: dataPendapatan,
                        borderColor: '#3b82f6', // blue-500
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Laba Bersih (Rp)',
                        data: dataLaba,
                        borderColor: '#10b981', // green-500
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += 'Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                if(value >= 1000000) return 'Rp ' + (value/1000000) + ' Jt';
                                return 'Rp ' + value;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endif
@endsection
