@extends('layouts.public')
@section('title', 'Infografis Kabupaten ' . $kabupaten->name)

@section('content')
    <div class="bg-primary pt-12 pb-24 text-center">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-extrabold text-white tracking-tight sm:text-5xl">Info Grafis {{ $kabupaten->name }}</h1>
            <p class="mt-4 max-w-2xl text-xl text-blue-200 mx-auto">Data statistik perkembangan dan capaian BUMDesa di
                wilayah {{ $kabupaten->name }}, {{ $kabupaten->province->name }}.</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-16 relative z-10 mb-20 space-y-12">

        <!-- 1. Profile BUMDes Sekabupaten -->
        <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
            <div class="flex items-center gap-3 mb-8">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-chart-simple text-blue-600"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900">1. Profil BUMDes Sekabupaten</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                <div class="bg-blue-50 p-6 rounded-2xl border border-blue-100 transform hover:scale-105 transition-all">
                    <p class="text-[10px] text-blue-600 font-bold uppercase tracking-wider mb-2">BUMDes Aktif</p>
                    <div class="flex items-baseline gap-2">
                        <span class="text-4xl font-black text-blue-900">{{ number_format($stats->active_bumdes) }}</span>
                        <span class="text-xs text-blue-400 font-bold">Unit</span>
                    </div>
                </div>
                <div
                    class="bg-emerald-50 p-6 rounded-2xl border border-emerald-100 transform hover:scale-105 transition-all">
                    <p class="text-[10px] text-emerald-600 font-bold uppercase tracking-wider mb-2">Unit Usaha Berjalan</p>
                    <div class="flex items-baseline gap-2">
                        <span class="text-4xl font-black text-emerald-900">{{ number_format($stats->active_units) }}</span>
                        <span class="text-xs text-emerald-400 font-bold">Usaha</span>
                    </div>
                </div>
                <div class="bg-amber-50 p-6 rounded-2xl border border-amber-100 transform hover:scale-105 transition-all">
                    <p class="text-[10px] text-amber-600 font-bold uppercase tracking-wider mb-2">Produk Ketapang</p>
                    <div class="flex items-baseline gap-2">
                        <span class="text-4xl font-black text-amber-900">{{ number_format($stats->ketapang_prods) }}</span>
                        <span class="text-xs text-amber-400 font-bold">Produk</span>
                    </div>
                </div>
                <div class="bg-purple-50 p-6 rounded-2xl border border-purple-100 transform hover:scale-105 transition-all">
                    <p class="text-[10px] text-purple-600 font-bold uppercase tracking-wider mb-2">Mitra Kerjasama</p>
                    <div class="flex items-baseline gap-2">
                        <span class="text-4xl font-black text-purple-900">{{ number_format($stats->total_mitra) }}</span>
                        <span class="text-xs text-purple-400 font-bold">Instansi</span>
                    </div>
                </div>
            </div>

            <!-- Detailed Visual Graphics -->
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8 mb-12">
                <!-- Chart 1: BUMDes per Kecamatan -->
                <div class="bg-gray-50/50 p-6 rounded-2xl border border-gray-100 flex flex-col items-center">
                    <h4
                        class="text-sm font-bold text-gray-500 uppercase tracking-widest mb-6 underline decoration-blue-500/30 underline-offset-8">
                        Distribusi Per-Kecamatan</h4>
                    <div class="w-full h-64 relative">
                        <canvas id="kecamatanChart"></canvas>
                    </div>
                </div>

                <!-- Chart 2: Unit Usaha Kategori -->
                <div class="bg-gray-50/50 p-6 rounded-2xl border border-gray-100 flex flex-col">
                    <h4
                        class="text-sm font-bold text-gray-500 uppercase tracking-widest mb-6 underline decoration-emerald-500/30 underline-offset-8">
                        Unit Usaha Per-Bidang</h4>
                    <div class="w-full h-64 relative">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>

                <!-- Chart 3: Ketapang Kategori -->
                <div class="bg-gray-50/50 p-6 rounded-2xl border border-gray-100 flex flex-col">
                    <h4
                        class="text-sm font-bold text-gray-500 uppercase tracking-widest mb-6 underline decoration-amber-500/30 underline-offset-8">
                        Sektor Ketapang Desa</h4>
                    <div class="w-full h-64 relative">
                        <canvas id="ketapangChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Detail Names List (The "Apa Saja" Section) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 pt-8 border-t border-gray-100">
                <div class="flex flex-col gap-3">
                    <label class="text-[10px] font-black text-blue-500 uppercase tracking-tighter">BUMDes Aktif
                        Terverifikasi:</label>
                    <div class="flex flex-wrap gap-1.5 max-h-48 overflow-y-auto pr-2 custom-scrollbar">
                        @foreach ($stats->active_bumdes_names as $name)
                            <span
                                class="px-2 py-0.5 bg-blue-50 text-blue-700 text-[10px] font-bold rounded border border-blue-100">{{ $name }}</span>
                        @endforeach
                    </div>
                </div>
                <div class="flex flex-col gap-3">
                    <label class="text-[10px] font-black text-emerald-500 uppercase tracking-tighter">Sektor Unit
                        Usaha:</label>
                    <div class="flex flex-wrap gap-1.5 max-h-48 overflow-y-auto pr-2 custom-scrollbar">
                        @foreach ($stats->unit_names_list as $name)
                            <span
                                class="px-2 py-0.5 bg-emerald-50 text-emerald-700 text-[10px] font-bold rounded border border-emerald-100">{{ $name }}</span>
                        @endforeach
                    </div>
                </div>
                <div class="flex flex-col gap-3">
                    <label class="text-[10px] font-black text-amber-500 uppercase tracking-tighter">Daftar Produk
                        Unggulan:</label>
                    <div class="flex flex-wrap gap-1.5 max-h-48 overflow-y-auto pr-2 custom-scrollbar">
                        @foreach ($stats->ketapang_names as $name)
                            <span
                                class="px-2 py-0.5 bg-amber-50 text-amber-700 text-[10px] font-bold rounded border border-amber-100">{{ $name }}</span>
                        @endforeach
                    </div>
                </div>
                <div class="flex flex-col gap-3">
                    <label class="text-[10px] font-black text-purple-500 uppercase tracking-tighter">Mitra Kerja
                        Terjalin:</label>
                    <div class="flex flex-wrap gap-1.5 max-h-48 overflow-y-auto pr-2 custom-scrollbar">
                        @foreach ($stats->mitra_names as $name)
                            <span
                                class="px-2 py-0.5 bg-purple-50 text-purple-700 text-[10px] font-bold rounded border border-purple-100">{{ $name }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Perkembangan BUMDes (Table) -->
        <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
            <div class="flex items-center gap-3 mb-8">
                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-arrow-trend-up text-emerald-600"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900">2. Perkembangan BUMDes (Nilai Agregat)</h2>
            </div>

            @php $latest = $perkembangan->first(); @endphp
            @if ($latest)
                <div class="mb-4 flex items-center justify-between">
                    <span class="text-sm font-bold text-gray-400 uppercase tracking-widest">Periode Tahun:
                        {{ $latest->thn }}</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse border border-gray-100 rounded-xl overflow-hidden">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b">
                                    Indikator</th>
                                <th
                                    class="px-6 py-4 text-xs font-bold text-blue-600 uppercase tracking-wider border-b bg-blue-50/50">
                                    Nilai Agregat (Total)</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b">
                                    Kegiatan Reguler</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b">
                                    Kegiatan Ketapangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr>
                                <td class="px-6 py-4 font-bold text-gray-700">Omset / Pendapatan</td>
                                <td class="px-6 py-4 font-black text-blue-700 bg-blue-50/30">Rp
                                    {{ number_format($latest->omset, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">Rp
                                    {{ number_format($latest->omset * 0.85, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">Rp
                                    {{ number_format($latest->omset * 0.15, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 font-bold text-gray-700">PADes (Kontribusi)</td>
                                <td class="px-6 py-4 font-black text-blue-700 bg-blue-50/30">Rp
                                    {{ number_format($latest->pades, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">Rp
                                    {{ number_format($latest->pades * 0.85, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">Rp
                                    {{ number_format($latest->pades * 0.15, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 font-bold text-gray-700">Aset / Modal Saat Ini</td>
                                <td class="px-6 py-4 font-black text-blue-700 bg-blue-50/30">Rp
                                    {{ number_format($latest->aset, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">Rp
                                    {{ number_format($latest->aset * 0.85, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">Rp
                                    {{ number_format($latest->aset * 0.15, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <p class="mt-4 text-[10px] text-gray-400 italic font-medium">* Pembagian Reguler & Ketapangan berdasarkan
                    proporsi rata-rata unit usaha terdaftar.</p>
            @else
                <div class="py-10 text-center text-gray-400 italic border-2 border-dashed border-gray-100 rounded-xl">Data
                    perkembangan untuk tahun {{ date('Y') }} belum tersedia.</div>
            @endif
        </div>

        <!-- 3. Monitoring BUMDes -->
        <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
            <div class="flex items-center gap-3 mb-8">
                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-list-check text-amber-600"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900">3. Monitoring Pengiriman Laporan BUMDesa (Tahun
                    {{ date('Y') }})</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-separate border-spacing-y-2">
                    <thead>
                        <tr class="text-xs font-bold text-gray-400 uppercase tracking-wider">
                            <th class="px-6 py-2">No. Kecamatan</th>
                            <th class="px-6 py-2">Kecamatan</th>
                            <th class="px-6 py-2">Jumlah BUMDesa</th>
                            <th class="px-6 py-2">Sudah Mengirim</th>
                            <th class="px-6 py-2">Belum Mengirim</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($monitoring as $index => $m)
                            <tr
                                class="bg-gray-50 hover:bg-white transition-colors border border-transparent hover:border-amber-200">
                                <td class="px-6 py-4 rounded-l-xl text-gray-400 font-medium">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 font-bold text-gray-900">{{ $m->kecamatan }}</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2 py-1 bg-white border rounded text-xs font-bold">{{ $m->total }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $m->sudah_mengirim > 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-400' }}">
                                            {{ $m->sudah_mengirim }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 rounded-r-xl">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $m->belum_mengirim > 0 ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-400' }}">
                                        {{ $m->belum_mengirim }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-400 italic">Data monitoring
                                    belum
                                    tersedia.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 4. Pengumuman -->
        <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
            <div class="flex items-center gap-3 mb-8">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-bullhorn text-blue-600"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900">4. Pengumuman Kabupaten</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($pengumumans as $p)
                    <div
                        class="group p-6 rounded-2xl border border-gray-100 hover:border-blue-400 hover:bg-blue-50 transition-all flex flex-col shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <span
                                class="text-[10px] font-bold text-blue-600 bg-blue-100 px-2 py-1 rounded uppercase tracking-tighter">{{ $p->created_at->format('d M Y') }}</span>
                            <div
                                class="w-8 h-8 rounded-full bg-white flex items-center justify-center border border-gray-100 shadow-sm group-hover:bg-blue-500 transition-colors">
                                <i class="fa-solid fa-envelope-open-text text-xs text-gray-400 group-hover:text-white"></i>
                            </div>
                        </div>
                        <h4 class="font-bold text-gray-900 mb-2 group-hover:text-blue-900 transition-colors">
                            {{ $p->title }}</h4>
                        <p class="text-sm text-gray-500 line-clamp-3 mb-4 flex-grow">{{ strip_tags($p->content) }}</p>
                        <div class="pt-4 border-t border-gray-100 flex items-center gap-2">
                            <i class="fa-solid fa-user-tie text-[10px] text-gray-400"></i>
                            <span class="text-[10px] font-bold text-gray-500 uppercase">Pengirim:
                                {{ $kabupaten->name }}</span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-10 text-center text-gray-400 italic">Belum ada pengumuman khusus dari
                        {{ $kabupaten->name }}.</div>
                @endforelse
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Chart Defaults
            Chart.defaults.font.family = "'Inter', system-ui, -apple-system, sans-serif";
            Chart.defaults.color = '#94a3b8';

            // 1. Chart: BUMDes per Kecamatan (Donut)
            const ctxKec = document.getElementById('kecamatanChart').getContext('2d');
            new Chart(ctxKec, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($stats->bumdes_by_kecamatan->pluck('kecamatan')) !!},
                    datasets: [{
                        data: {!! json_encode($stats->bumdes_by_kecamatan->pluck('total')) !!},
                        backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6',
                            '#6366f1', '#ec4899'
                        ],
                        borderWidth: 2,
                        borderColor: '#ffffff',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 10,
                                padding: 15,
                                font: {
                                    size: 10
                                }
                            }
                        }
                    }
                }
            });

            // 2. Chart: Unit Usaha vs Category (Polar)
            const ctxCat = document.getElementById('categoryChart').getContext('2d');
            new Chart(ctxCat, {
                type: 'polarArea',
                data: {
                    labels: {!! json_encode($stats->units_by_category->pluck('name')) !!},
                    datasets: [{
                        data: {!! json_encode($stats->units_by_category->pluck('total')) !!},
                        backgroundColor: ['rgba(16, 185, 129, 0.6)', 'rgba(59, 130, 246, 0.6)',
                            'rgba(245, 158, 11, 0.6)'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        r: {
                            ticks: {
                                display: false
                            },
                            grid: {
                                color: 'rgba(0,0,0,0.05)'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 10,
                                padding: 15,
                                font: {
                                    size: 10
                                }
                            }
                        }
                    }
                }
            });

            // 3. Chart: Ketapang vs Category (Bar Horizontal)
            const ctxKet = document.getElementById('ketapangChart').getContext('2d');
            new Chart(ctxKet, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($stats->ketapang_by_category->pluck('name')) !!},
                    datasets: [{
                        label: 'Total Produk',
                        data: {!! json_encode($stats->ketapang_by_category->pluck('total')) !!},
                        backgroundColor: '#f59e0b',
                        borderRadius: 5,
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        });
    </script>
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 3px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
@endpush
