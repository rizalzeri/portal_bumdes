@extends('layouts.public')
@section('title', 'Beranda')

@section('content')
    <!-- Hero Section -->
    <div class="relative bg-primary overflow-hidden">
        <div class="absolute inset-0">
            <img class="w-full h-full object-cover opacity-20"
                src="https://images.unsplash.com/photo-1596423735880-5c6fa713b41e?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80"
                alt="BUMDesa Landscape">
            <div class="absolute inset-0 bg-primary mix-blend-multiply"></div>
        </div>
        <div class="relative max-w-7xl mx-auto py-24 px-4 sm:py-32 sm:px-6 lg:px-8 text-center text-white">
            <h1
                class="text-4xl font-extrabold tracking-tight sm:text-5xl lg:text-6xl text-transparent bg-clip-text bg-gradient-to-r from-accent to-yellow-200">
                Portal BUMDesa
            </h1>
            <p class="mt-6 text-xl max-w-3xl mx-auto text-blue-100">
                Pusat Inspirasi dan Informasi BUMDesa se-Indonesia. Temukan potensi, produk, dan perkembangan ribuan BUMDesa
                untuk memajukan ekonomi desa bersama.
            </p>
            <div class="mt-10 flex flex-col sm:flex-row justify-center items-center gap-4">
                <a href="{{ route('public.bumdes.list') }}"
                    class="px-8 py-4 border border-transparent text-lg font-bold rounded-full text-primary bg-accent hover:bg-yellow-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent shadow-lg transform transition hover:-translate-y-1 text-center w-full sm:w-auto">
                    Kunjungi BUMDesa
                </a>
                <a href="{{ route('public.register') }}"
                    class="px-8 py-4 border-2 border-accent text-lg font-bold rounded-full text-accent bg-transparent hover:bg-accent hover:text-primary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent shadow-lg transform transition hover:-translate-y-1 text-center w-full sm:w-auto">
                    Buat Website BUMDES
                </a>
            </div>
        </div>
    </div>

    <!-- Main Navigation Menu - Full Width Vertical Cards -->
    <div class="w-full px-16 py-16 -mt-16 relative z-10">
        <div class="flex flex-col gap-4">

            <!-- Menu 1: Website BUMDesa -->
            <div class="w-full bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                <a href="{{ route('public.bumdes.list') }}"
                    class="group flex flex-row items-center gap-0 hover:bg-gray-50 transition-colors">
                    <div class="w-1.5 self-stretch bg-accent shrink-0 rounded-l-2xl"></div>
                    <div class="flex flex-row items-center gap-5 p-5 flex-grow">
                        <div
                            class="w-14 h-14 bg-blue-50 rounded-full flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform duration-300">
                            <i class="fa-solid fa-map-location-dot text-2xl text-primary"></i>
                        </div>
                        <div class="flex-grow">
                            <h3 class="text-lg font-bold text-gray-900 group-hover:text-primary transition-colors">Website
                                BUMDesa</h3>
                            <p class="text-gray-500 text-sm mt-0.5">Akses langsung ke ratusan profil BUMDesa di seluruh
                                penjuru Indonesia.</p>
                        </div>
                        <div class="shrink-0 flex items-center gap-2">
                            <span
                                class="text-xs font-semibold text-gray-400 group-hover:text-primary opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-2 group-hover:translate-x-0 hidden sm:block">Lihat
                                lebih lanjut</span>
                            <div
                                class="w-9 h-9 rounded-full bg-gray-100 group-hover:bg-primary flex items-center justify-center transition-colors shadow-sm group-hover:shadow">
                                <i
                                    class="fa-solid fa-arrow-right text-sm text-gray-500 group-hover:text-white transition-colors"></i>
                            </div>
                        </div>
                    </div>
                </a>
                @if ($previewBumdes->count() > 0)
                    <div class="px-7 pb-5">
                        <div class="grid grid-cols-4 gap-3">
                            @foreach ($previewBumdes->take(4) as $item)
                                <a href="{{ url('bumdes/' . ($item->slug ?? '')) }}"
                                    class="group/item flex flex-col items-center gap-2 p-3 rounded-xl border border-gray-100 hover:border-primary hover:bg-blue-50 transition-all text-center">
                                    <div
                                        class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center overflow-hidden shrink-0">
                                        @if ($item->logo)
                                            <img src="{{ asset('storage/' . $item->logo) }}"
                                                class="w-full h-full object-cover">
                                        @else
                                            <i class="fa-solid fa-store text-gray-400"></i>
                                        @endif
                                    </div>
                                    <span
                                        class="text-xs font-semibold text-gray-700 group-hover/item:text-primary transition-colors line-clamp-2 leading-tight">BUMDesa "{{ $item->name }}" Desa</span>
                                    <span class="text-xs text-gray-400">{{ $item->kabupaten->name ?? '-' }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Menu 2: Info Grafis Data -->
            <div class="w-full bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                <a href="{{ route('public.infografis') }}"
                    class="group flex flex-row items-center gap-0 hover:bg-gray-50 transition-colors">
                    <div class="w-1.5 self-stretch bg-blue-500 shrink-0 rounded-l-2xl"></div>
                    <div class="flex flex-row items-center gap-5 p-5 flex-grow">
                        <div
                            class="w-14 h-14 bg-blue-50 rounded-full flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform duration-300">
                            <i class="fa-solid fa-chart-pie text-2xl text-blue-500"></i>
                        </div>
                        <div class="flex-grow">
                            <h3 class="text-lg font-bold text-gray-900 group-hover:text-blue-600 transition-colors">Info
                                Grafis
                                Data</h3>
                            <p class="text-gray-500 text-sm mt-0.5">Statistik dan data perkembangan ekonomi BUMDesa secara
                                real-time.</p>
                        </div>
                        <div class="shrink-0 flex items-center gap-2">
                            <span
                                class="text-xs font-semibold text-gray-400 group-hover:text-blue-500 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-2 group-hover:translate-x-0 hidden sm:block">Lihat
                                lebih lanjut</span>
                            <div
                                class="w-9 h-9 rounded-full bg-gray-100 group-hover:bg-blue-500 flex items-center justify-center transition-colors shadow-sm group-hover:shadow">
                                <i
                                    class="fa-solid fa-arrow-right text-sm text-gray-500 group-hover:text-white transition-colors"></i>
                            </div>
                        </div>
                    </div>
                </a>
                @if ($previewKabupaten->count() > 0)
                    <div class="px-7 pb-5">
                        <p class="text-xs text-gray-400 mb-2 font-medium">Infografis Per-Kabupaten:</p>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            @foreach ($previewKabupaten as $item)
                                <a href="{{ route('public.infografis.kabupaten', $item->id) }}"
                                    class="group/item flex items-center gap-2 p-3 rounded-xl border border-gray-100 hover:border-blue-400 hover:bg-blue-50 transition-all">
                                    <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center shrink-0">
                                        <i class="fa-solid fa-map-location-dot text-blue-600 text-sm"></i>
                                    </div>
                                    <span
                                        class="text-xs font-semibold text-gray-700 group-hover/item:text-blue-700 transition-colors line-clamp-2 leading-tight">{{ $item->name }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Menu 3: Materi & Template -->
            <div class="w-full bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                <a href="{{ route('public.materi') }}"
                    class="group flex flex-row items-center gap-0 hover:bg-gray-50 transition-colors">
                    <div class="w-1.5 self-stretch bg-green-500 shrink-0 rounded-l-2xl"></div>
                    <div class="flex flex-row items-center gap-5 p-5 flex-grow">
                        <div
                            class="w-14 h-14 bg-green-50 rounded-full flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform duration-300">
                            <i class="fa-solid fa-file-pdf text-2xl text-green-500"></i>
                        </div>
                        <div class="flex-grow">
                            <h3 class="text-lg font-bold text-gray-900 group-hover:text-green-600 transition-colors">Materi
                                & Template</h3>
                            <p class="text-gray-500 text-sm mt-0.5">Unduh panduan, SOP, dan regulasi resmi untuk pengelolaan
                                BUMDesa.</p>
                        </div>
                        <div class="shrink-0 flex items-center gap-2">
                            <span
                                class="text-xs font-semibold text-gray-400 group-hover:text-green-500 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-2 group-hover:translate-x-0 hidden sm:block">Lihat
                                lebih lanjut</span>
                            <div
                                class="w-9 h-9 rounded-full bg-gray-100 group-hover:bg-green-500 flex items-center justify-center transition-colors shadow-sm group-hover:shadow">
                                <i
                                    class="fa-solid fa-arrow-right text-sm text-gray-500 group-hover:text-white transition-colors"></i>
                            </div>
                        </div>
                    </div>
                </a>
                @if (isset($previewMateri) && $previewMateri->count() > 0)
                    <div class="px-7 pb-5">
                        <p class="text-xs text-gray-400 mb-2 font-medium">Materi dari BUMDesa:</p>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            @foreach ($previewMateri as $item)
                                <a href="{{ url('bumdes/' . ($item->bumdes->slug ?? '') . '#materi-template') }}"
                                    class="group/item flex flex-col items-center gap-2 p-3 rounded-xl border border-gray-100 hover:border-green-400 hover:bg-green-50 transition-all text-center">
                                    <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center shrink-0">
                                        <i class="fa-solid fa-file-pdf text-green-600 text-sm"></i>
                                    </div>
                                    <span
                                        class="text-xs font-semibold text-gray-700 group-hover/item:text-green-700 transition-colors line-clamp-2 leading-tight">{{ $item->title }}</span>
                                    <span
                                        class="text-[10px] text-gray-400 line-clamp-1">BUMDesa "{{ $item->bumdes->name ?? 'Portal Pusat' }}" Desa</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Menu 4: Kabar & Opini -->
            <div class="w-full bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                <a href="{{ route('public.informasi') }}"
                    class="group flex flex-row items-center gap-0 hover:bg-gray-50 transition-colors">
                    <div class="w-1.5 self-stretch bg-indigo-500 shrink-0 rounded-l-2xl"></div>
                    <div class="flex flex-row items-center gap-5 p-5 flex-grow">
                        <div
                            class="w-14 h-14 bg-indigo-50 rounded-full flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform duration-300">
                            <i class="fa-solid fa-newspaper text-2xl text-indigo-500"></i>
                        </div>
                        <div class="flex-grow">
                            <h3 class="text-lg font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">Kabar
                                & Opini</h3>
                            <p class="text-gray-500 text-sm mt-0.5">Berita terbaru dan opini pakar terkait perkembangan desa
                                dan BUMDesa.</p>
                        </div>
                        <div class="shrink-0 flex items-center gap-2">
                            <span
                                class="text-xs font-semibold text-gray-400 group-hover:text-indigo-500 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-2 group-hover:translate-x-0 hidden sm:block">Lihat
                                lebih lanjut</span>
                            <div
                                class="w-9 h-9 rounded-full bg-gray-100 group-hover:bg-indigo-500 flex items-center justify-center transition-colors shadow-sm group-hover:shadow">
                                <i
                                    class="fa-solid fa-arrow-right text-sm text-gray-500 group-hover:text-white transition-colors"></i>
                            </div>
                        </div>
                    </div>
                </a>
                @if ($previewArtikel->count() > 0)
                    <div class="px-7 pb-5">
                        <p class="text-xs text-gray-400 mb-2 font-medium">Artikel terbaru:</p>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            @foreach ($previewArtikel as $item)
                                <a href="{{ url('bumdes/' . ($item->bumdes->slug ?? '') . '#kabar-opini') }}"
                                    class="group/item flex flex-col gap-1.5 p-3 rounded-xl border border-gray-100 hover:border-indigo-400 hover:bg-indigo-50 transition-all">
                                    @if ($item->image)
                                        <img src="{{ asset('storage/' . $item->image) }}"
                                            class="w-full h-20 object-cover rounded-lg mb-1">
                                    @else
                                        <div
                                            class="w-full h-20 bg-indigo-100 rounded-lg mb-1 flex items-center justify-center">
                                            <i class="fa-solid fa-newspaper text-indigo-300 text-2xl"></i>
                                        </div>
                                    @endif
                                    <span
                                        class="text-xs font-semibold text-gray-700 group-hover/item:text-indigo-700 transition-colors line-clamp-2 leading-tight">{{ $item->title }}</span>
                                    <span class="text-xs text-gray-400">BUMDesa "{{ $item->bumdes->name ?? 'Portal Pusat' }}" Desa</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Menu 5: Katalog Produk -->
            <div class="w-full bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                <a href="{{ route('public.katalog') }}"
                    class="group flex flex-row items-center gap-0 hover:bg-gray-50 transition-colors">
                    <div class="w-1.5 self-stretch bg-teal-500 shrink-0 rounded-l-2xl"></div>
                    <div class="flex flex-row items-center gap-5 p-5 flex-grow">
                        <div
                            class="w-14 h-14 bg-teal-50 rounded-full flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform duration-300">
                            <i class="fa-solid fa-shop text-2xl text-teal-500"></i>
                        </div>
                        <div class="flex-grow">
                            <h3 class="text-lg font-bold text-gray-900 group-hover:text-teal-600 transition-colors">Katalog
                                Produk</h3>
                            <p class="text-gray-500 text-sm mt-0.5">Pasar digital produk unggulan karya BUMDesa dari
                                seluruh Indonesia.</p>
                        </div>
                        <div class="shrink-0 flex items-center gap-2">
                            <span
                                class="text-xs font-semibold text-gray-400 group-hover:text-teal-500 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-2 group-hover:translate-x-0 hidden sm:block">Lihat
                                lebih lanjut</span>
                            <div
                                class="w-9 h-9 rounded-full bg-gray-100 group-hover:bg-teal-500 flex items-center justify-center transition-colors shadow-sm group-hover:shadow">
                                <i
                                    class="fa-solid fa-arrow-right text-sm text-gray-500 group-hover:text-white transition-colors"></i>
                            </div>
                        </div>
                    </div>
                </a>
                @if ($previewKatalog->count() > 0)
                    <div class="px-7 pb-5">
                        <p class="text-xs text-gray-400 mb-2 font-medium">Produk terbaru:</p>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            @foreach ($previewKatalog as $item)
                                <a href="{{ url('bumdes/' . ($item->bumdes->slug ?? '') . '#produk-desa') }}"
                                    class="group/item flex flex-col gap-1.5 p-3 rounded-xl border border-gray-100 hover:border-teal-400 hover:bg-teal-50 transition-all">
                                    @if ($item->image)
                                        <img src="{{ asset('storage/' . $item->image) }}"
                                            class="w-full h-20 object-cover rounded-lg mb-1">
                                    @else
                                        <div
                                            class="w-full h-20 bg-teal-100 rounded-lg mb-1 flex items-center justify-center">
                                            <i class="fa-solid fa-box-open text-teal-300 text-2xl"></i>
                                        </div>
                                    @endif
                                    <span
                                        class="text-xs font-semibold text-gray-700 group-hover/item:text-teal-700 transition-colors line-clamp-2 leading-tight">{{ $item->title }}</span>
                                    @if ($item->price)
                                        <span class="text-xs text-teal-600 font-bold">Rp
                                            {{ number_format($item->price, 0, ',', '.') }}</span>
                                    @endif
                                    <span class="text-xs text-gray-400">BUMDesa "{{ $item->bumdes->name ?? '-' }}" Desa</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Menu 6: Mitra Kerjasama -->
            <div class="w-full bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                <a href="{{ route('public.mitra') }}"
                    class="group flex flex-row items-center gap-0 hover:bg-gray-50 transition-colors">
                    <div class="w-1.5 self-stretch bg-red-500 shrink-0 rounded-l-2xl"></div>
                    <div class="flex flex-row items-center gap-5 p-5 flex-grow">
                        <div
                            class="w-14 h-14 bg-red-50 rounded-full flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform duration-300">
                            <i class="fa-solid fa-handshake text-2xl text-red-500"></i>
                        </div>
                        <div class="flex-grow">
                            <h3 class="text-lg font-bold text-gray-900 group-hover:text-red-600 transition-colors">Mitra
                                Kerjasama</h3>
                            <p class="text-gray-500 text-sm mt-0.5">Jaringan kerjasama strategis lintas sektor untuk
                                kemajuan desa.</p>
                        </div>
                        <div class="shrink-0 flex items-center gap-2">
                            <span
                                class="text-xs font-semibold text-gray-400 group-hover:text-red-500 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-2 group-hover:translate-x-0 hidden sm:block">Lihat
                                lebih lanjut</span>
                            <div
                                class="w-9 h-9 rounded-full bg-gray-100 group-hover:bg-red-500 flex items-center justify-center transition-colors shadow-sm group-hover:shadow">
                                <i
                                    class="fa-solid fa-arrow-right text-sm text-gray-500 group-hover:text-white transition-colors"></i>
                            </div>
                        </div>
                    </div>
                </a>
                @if ($previewMitra->count() > 0)
                    <div class="px-7 pb-5">
                        <p class="text-xs text-gray-400 mb-2 font-medium">Mitra terbaru:</p>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            @foreach ($previewMitra as $item)
                                <a href="{{ url('bumdes/' . ($item->bumdes->slug ?? '') . '#mitra') }}"
                                    class="group/item flex items-center gap-2 p-3 rounded-xl border border-gray-100 hover:border-red-400 hover:bg-red-50 transition-all">
                                    <div
                                        class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center shrink-0 overflow-hidden">
                                        @if ($item->logo)
                                            <img src="{{ asset('storage/' . $item->logo) }}"
                                                class="w-full h-full object-cover">
                                        @else
                                            <i class="fa-solid fa-handshake text-red-500 text-xs"></i>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <p
                                            class="text-xs font-semibold text-gray-700 group-hover/item:text-red-700 transition-colors line-clamp-1">
                                            {{ $item->name }}</p>
                                        <p class="text-xs text-gray-400 line-clamp-1">
                                            BUMDesa "{{ $item->bumdes->name ?? 'Portal Pusat' }}" Desa</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Menu 7: Galeri Kegiatan BUMDesa -->
            <div class="w-full bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                <a href="{{ route('public.galeri') }}"
                    class="group flex flex-row items-center gap-0 hover:bg-gray-50 transition-colors">
                    <div class="w-1.5 self-stretch bg-purple-500 shrink-0 rounded-l-2xl"></div>
                    <div class="flex flex-row items-center gap-5 p-5 flex-grow">
                        <div
                            class="w-14 h-14 bg-purple-50 rounded-full flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform duration-300">
                            <i class="fa-solid fa-images text-2xl text-purple-500"></i>
                        </div>
                        <div class="flex-grow">
                            <h3 class="text-lg font-bold text-gray-900 group-hover:text-purple-600 transition-colors">
                                Galeri Kegiatan BUMDesa</h3>
                            <p class="text-gray-500 text-sm mt-0.5">Tangkapan momen dan video kegiatan inovatif para
                                pejuang desa.</p>
                        </div>
                        <div class="shrink-0 flex items-center gap-2">
                            <span
                                class="text-xs font-semibold text-gray-400 group-hover:text-purple-500 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-2 group-hover:translate-x-0 hidden sm:block">Lihat
                                lebih lanjut</span>
                            <div
                                class="w-9 h-9 rounded-full bg-gray-100 group-hover:bg-purple-500 flex items-center justify-center transition-colors shadow-sm group-hover:shadow">
                                <i
                                    class="fa-solid fa-arrow-right text-sm text-gray-500 group-hover:text-white transition-colors"></i>
                            </div>
                        </div>
                    </div>
                </a>
                @if ($previewGaleri->count() > 0)
                    <div class="px-7 pb-5">
                        <p class="text-xs text-gray-400 mb-2 font-medium">Foto terbaru:</p>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            @foreach ($previewGaleri as $item)
                                <a href="{{ url('bumdes/' . ($item->bumdes->slug ?? '') . '#galeri-kegiatan') }}"
                                    class="group/item flex flex-col gap-1.5 p-3 rounded-xl border border-gray-100 hover:border-purple-400 hover:bg-purple-50 transition-all">
                                    <div class="w-full h-24 rounded-lg overflow-hidden shrink-0 border border-gray-50">
                                        @if ($item->type === 'video' || $item->video_url)
                                            <div class="w-full h-full relative">
                                                <img src="https://img.youtube.com/vi/{{ \Illuminate\Support\Str::afterLast($item->video_url, 'v=') }}/0.jpg"
                                                    class="w-full h-full object-cover"
                                                    onerror="this.src='https://via.placeholder.com/400x300?text=Video'">
                                                <div class="absolute inset-0 flex items-center justify-center bg-black/20">
                                                    <i class="fa-solid fa-play text-white text-xl"></i>
                                                </div>
                                            </div>
                                        @else
                                            @php
                                                $imageUrl = $item->image ?? $item->image_url;
                                            @endphp
                                            @if ($imageUrl)
                                                <img src="{{ asset('storage/' . $imageUrl) }}"
                                                    class="w-full h-full object-cover group-hover/item:scale-105 transition-transform duration-300">
                                            @else
                                                <div class="w-full h-full bg-purple-100 flex items-center justify-center">
                                                    <i class="fa-solid fa-image text-purple-300 text-2xl"></i>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                    <div class="flex flex-col min-w-0">
                                        <span
                                            class="text-xs font-semibold text-gray-700 group-hover/item:text-purple-700 transition-colors line-clamp-1 leading-tight">{{ $item->title }}</span>
                                        <span
                                            class="text-[10px] text-gray-400 line-clamp-1">BUMDesa "{{ $item->bumdes->name ?? 'Portal Pusat' }}" Desa</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Menu 8: Pengumuman Portal -->
            <div class="w-full bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden mb-6">
                <a href="{{ route('public.pengumuman') }}"
                    class="group flex flex-row items-center gap-0 hover:bg-gray-50 transition-colors">
                    <div class="w-1.5 self-stretch bg-blue-500 shrink-0 rounded-l-2xl"></div>
                    <div class="flex flex-row items-center gap-5 p-5 flex-grow">
                        <div
                            class="w-14 h-14 bg-blue-50 rounded-full flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform duration-300">
                            <i class="fa-solid fa-bullhorn text-2xl text-blue-500"></i>
                        </div>
                        <div class="flex-grow">
                            <h3 class="text-lg font-bold text-gray-900 group-hover:text-blue-600 transition-colors">
                                Pengumuman Portal Pusat</h3>
                            <p class="text-gray-500 text-sm mt-0.5">Informasi penting, agenda kegiatan, dan berita resmi
                                dari Pusat.</p>
                        </div>
                        <div class="shrink-0 flex items-center gap-2">
                            <span
                                class="text-xs font-semibold text-gray-400 group-hover:text-blue-500 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-2 group-hover:translate-x-0 hidden sm:block">Lihat
                                semua</span>
                            <div
                                class="w-9 h-9 rounded-full bg-gray-100 group-hover:bg-blue-500 flex items-center justify-center transition-colors shadow-sm group-hover:shadow">
                                <i
                                    class="fa-solid fa-arrow-right text-sm text-gray-500 group-hover:text-white transition-colors"></i>
                            </div>
                        </div>
                    </div>
                </a>
                @if ($previewPengumumanPortal->count() > 0)
                    <div class="px-7 pb-5">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            @foreach ($previewPengumumanPortal as $item)
                                <a href="javascript:void(0)"
                                    class="group/item flex flex-col gap-1.5 p-3 rounded-xl border border-gray-100 hover:border-blue-400 hover:bg-blue-50 transition-all">
                                    <div class="flex items-center gap-2 mb-1">
                                        <div
                                            class="w-7 h-7 bg-blue-100 rounded-full flex items-center justify-center shrink-0">
                                            <i class="fa-solid fa-bell text-blue-600 text-[10px]"></i>
                                        </div>
                                        <span
                                            class="text-[10px] text-gray-400">{{ $item->created_at->format('d M Y') }}</span>
                                    </div>
                                    <span
                                        class="text-xs font-semibold text-gray-700 group-hover/item:text-blue-700 transition-colors line-clamp-2 leading-tight">{{ $item->title }}</span>
                                    <span class="text-[10px] text-gray-400 mt-auto">Portal Pusat</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Menu 8b: Pengumuman Kabupaten -->
            <div class="w-full bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden mb-6">
                <a href="{{ route('public.pengumuman') }}"
                    class="group flex flex-row items-center gap-0 hover:bg-gray-50 transition-colors">
                    <div class="w-1.5 self-stretch bg-amber-500 shrink-0 rounded-l-2xl"></div>
                    <div class="flex flex-row items-center gap-5 p-5 flex-grow">
                        <div
                            class="w-14 h-14 bg-amber-50 rounded-full flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform duration-300">
                            <i class="fa-solid fa-building-flag text-2xl text-amber-500"></i>
                        </div>
                        <div class="flex-grow">
                            <h3 class="text-lg font-bold text-gray-900 group-hover:text-amber-600 transition-colors">
                                Pengumuman Kabupaten</h3>
                            <p class="text-gray-500 text-sm mt-0.5">Informasi dan edaran dari Pemerintah Kabupaten untuk
                                BUMDesa.</p>
                        </div>
                        <div class="shrink-0 flex items-center gap-2">
                            <span
                                class="text-xs font-semibold text-gray-400 group-hover:text-amber-500 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-2 group-hover:translate-x-0 hidden sm:block">Lihat
                                semua</span>
                            <div
                                class="w-9 h-9 rounded-full bg-gray-100 group-hover:bg-amber-500 flex items-center justify-center transition-colors shadow-sm group-hover:shadow">
                                <i
                                    class="fa-solid fa-arrow-right text-sm text-gray-500 group-hover:text-white transition-colors"></i>
                            </div>
                        </div>
                    </div>
                </a>
                @if ($previewPengumumanKabupaten->count() > 0)
                    <div class="px-7 pb-5">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            @foreach ($previewPengumumanKabupaten as $item)
                                <a href="javascript:void(0)"
                                    class="group/item flex flex-col gap-1.5 p-3 rounded-xl border border-gray-100 hover:border-amber-400 hover:bg-amber-50 transition-all">
                                    <div class="flex items-center gap-2 mb-1">
                                        <div
                                            class="w-7 h-7 bg-amber-100 rounded-full flex items-center justify-center shrink-0">
                                            <i class="fa-solid fa-bell text-amber-600 text-[10px]"></i>
                                        </div>
                                        <span
                                            class="text-[10px] text-gray-400">{{ $item->created_at->format('d M Y') }}</span>
                                    </div>
                                    <span
                                        class="text-xs font-semibold text-gray-700 group-hover/item:text-amber-700 transition-colors line-clamp-2 leading-tight">{{ $item->title }}</span>
                                    <span class="text-[10px] text-gray-400 mt-auto">
                                        Portal {{ $item->kabupaten->name ?? 'Kabupaten' }}
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Menu 8c: Pengumuman BUMDesa -->
            <div class="w-full bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                <a href="{{ route('public.pengumuman') }}"
                    class="group flex flex-row items-center gap-0 hover:bg-gray-50 transition-colors">
                    <div class="w-1.5 self-stretch bg-emerald-500 shrink-0 rounded-l-2xl"></div>
                    <div class="flex flex-row items-center gap-5 p-5 flex-grow">
                        <div
                            class="w-14 h-14 bg-emerald-50 rounded-full flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform duration-300">
                            <i class="fa-solid fa-house-chimney-window text-2xl text-emerald-500"></i>
                        </div>
                        <div class="flex-grow">
                            <h3 class="text-lg font-bold text-gray-900 group-hover:text-emerald-600 transition-colors">
                                Pengumuman BUMDesa</h3>
                            <p class="text-gray-500 text-sm mt-0.5">Informasi layanan dan pengumuman operasional dari unit
                                BUMDesa.</p>
                        </div>
                        <div class="shrink-0 flex items-center gap-2">
                            <span
                                class="text-xs font-semibold text-gray-400 group-hover:text-emerald-500 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-2 group-hover:translate-x-0 hidden sm:block">Lihat
                                semua</span>
                            <div
                                class="w-9 h-9 rounded-full bg-gray-100 group-hover:bg-emerald-500 flex items-center justify-center transition-colors shadow-sm group-hover:shadow">
                                <i
                                    class="fa-solid fa-arrow-right text-sm text-gray-500 group-hover:text-white transition-colors"></i>
                            </div>
                        </div>
                    </div>
                </a>
                @if ($previewPengumumanBumdes->count() > 0)
                    <div class="px-7 pb-5">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            @foreach ($previewPengumumanBumdes as $item)
                                <a href="{{ url('bumdes/' . ($item->bumdes->slug ?? '') . '#pengumuman') }}"
                                    class="group/item flex flex-col gap-1.5 p-3 rounded-xl border border-gray-100 hover:border-emerald-400 hover:bg-emerald-50 transition-all">
                                    <div class="flex items-center gap-2 mb-1">
                                        <div
                                            class="w-7 h-7 bg-emerald-100 rounded-full flex items-center justify-center shrink-0">
                                            <i class="fa-solid fa-bell text-emerald-600 text-[10px]"></i>
                                        </div>
                                        <span
                                            class="text-[10px] text-gray-400">{{ $item->created_at->format('d M Y') }}</span>
                                    </div>
                                    <span
                                        class="text-xs font-semibold text-gray-700 group-hover/item:text-emerald-700 transition-colors line-clamp-2 leading-tight">{{ $item->title }}</span>
                                    <span class="text-[10px] text-gray-400 mt-auto">
                                        BUMDesa "{{ $item->bumdes->name ?? 'BUMDesa' }}" Desa
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>

@endsection
