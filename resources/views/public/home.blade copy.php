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
            <div class="mt-10 flex flex-col sm:flex-row justify-center items-center gap-6 sm:gap-12">
                <div class="flex flex-col items-center gap-2 w-full sm:w-auto">
                    <a href="{{ route('public.bumdes.list') }}"
                        class="px-8 py-4 border border-transparent text-lg font-bold rounded-full text-primary bg-accent hover:bg-yellow-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent shadow-lg transform transition hover:-translate-y-1 text-center w-full">
                        Kunjungi BUMDesa
                    </a>
                    <span class="text-xs font-semibold text-accent/90 italic tracking-wide">(Gratis dan Praktis)</span>
                </div>
                <div class="flex flex-col items-center gap-2 w-full sm:w-auto">
                    <a href="{{ route('public.register') }}"
                        class="px-8 py-4 border-2 border-accent text-lg font-bold rounded-full text-accent bg-transparent hover:bg-accent hover:text-primary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent shadow-lg transform transition hover:-translate-y-1 text-center w-full">
                        Buat Website BUMDES
                    </a>
                    <span class="text-xs font-semibold text-accent/80 italic tracking-wide">(Mudah dan Cepat)</span>
                </div>
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
                                <a href="{{ route('public.bumdes.profile', $item->slug ?? '') }}"
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
                                        class="text-xs font-semibold text-gray-700 group-hover/item:text-primary transition-colors line-clamp-2 leading-tight">BUMDesa
                                        "{{ $item->name }}" Desa</span>
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
                                Perkembangan BUMDesa</h3>
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
                                <a href="{{ route('public.bumdes.profile', $item->bumdes->slug ?? '') . '#materi-template' }}"
                                    class="group/item flex flex-col items-center gap-2 p-3 rounded-xl border border-gray-100 hover:border-green-400 hover:bg-green-50 transition-all text-center">
                                    <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center shrink-0">
                                        <i class="fa-solid fa-file-pdf text-green-600 text-sm"></i>
                                    </div>
                                    <span
                                        class="text-xs font-semibold text-gray-700 group-hover/item:text-green-700 transition-colors line-clamp-2 leading-tight">{{ $item->title }}</span>
                                    <span class="text-[10px] text-gray-400 line-clamp-1">BUMDesa
                                        "{{ $item->bumdes->name ?? 'Portal Pusat' }}" Desa</span>
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
                                <a href="{{ route('public.bumdes.profile', $item->bumdes->slug ?? '') . '#kabar-opini' }}"
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
                                    <span class="text-xs text-gray-400">BUMDesa
                                        "{{ $item->bumdes->name ?? 'Portal Pusat' }}" Desa</span>
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
                                <a href="{{ route('public.bumdes.profile', $item->bumdes->slug ?? '') . '#produk-desa' }}"
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
                                    <span class="text-xs text-gray-400">BUMDesa "{{ $item->bumdes->name ?? '-' }}"
                                        Desa</span>
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
                                <a href="{{ route('public.bumdes.profile', $item->bumdes->slug ?? '') . '#mitra' }}"
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
                                <a href="{{ route('public.bumdes.profile', $item->bumdes->slug ?? '') . '#galeri-kegiatan' }}"
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
                                        <span class="text-[10px] text-gray-400 line-clamp-1">BUMDesa
                                            "{{ $item->bumdes->name ?? 'Portal Pusat' }}" Desa</span>
                                    </div>
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
                                Pengumuman Dinas PMD Kabupaten</h3>
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
            <div class="w-full bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden mb-6">
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
                                Pengumuman dari BUMDesa</h3>
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
                                <a href="{{ route('public.bumdes.profile', $item->bumdes->slug ?? '') . '#pengumuman' }}"
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

            <!-- Menu 8: Pengumuman Portal -->
            <!-- Menu 8: Pengumuman Portal -->
            <div class="w-full bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
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
                                Pengumuman Platform Website</h3>
                            <p class="text-gray-500 text-sm mt-0.5">Informasi penting, agenda kegiatan, dan berita resmi
                                dari Pusat.</p>
                        </div>
                        <div class="shrink-0 flex items-center gap-2">
                            <span
                                class="text-xs font-semibold text-gray-400 group-hover:text-blue-500 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-2 group-hover:translate-x-0 hidden sm:block">Lihat
                                <div class="flex-grow">
                                    <h3
                                        class="text-lg font-bold text-gray-900 group-hover:text-purple-600 transition-colors">
                                        Galeri Kegiatan BUMDesa</h3>
                                    <p class="text-gray-500 text-sm mt-0.5">Tangkapan momen dan video kegiatan inovatif
                                        para
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
                                <a href="{{ route('public.bumdes.profile', $item->bumdes->slug ?? '') . '#galeri-kegiatan' }}"
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
                                        <span class="text-[10px] text-gray-400 line-clamp-1">BUMDesa
                                            "{{ $item->bumdes->name ?? 'Portal Pusat' }}" Desa</span>
                                    </div>
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
                                Pengumuman Dinas PMD Kabupaten</h3>
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
            <div class="w-full bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden mb-6">
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
                                Pengumuman dari BUMDesa</h3>
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
                                <a href="{{ route('public.bumdes.profile', $item->bumdes->slug ?? '') . '#pengumuman' }}"
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

            <!-- Menu 8: Pengumuman Portal -->
            <div class="w-full bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
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
                                Pengumuman Platform Website</h3>
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
        </div>
    </div>

    <!-- Detailed Info Section (About Us, Services, FAQ) -->
    <div class="bg-blue-50/50 py-24 border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-24">

                <!-- Section 1: Tentang Kami -->
                <section id="tentang-kami" class="max-w-4xl">
                    <div
                        class="inline-flex items-center px-4 py-2 rounded-full bg-blue-100 text-primary text-xs font-bold uppercase tracking-widest mb-6">
                        Tentang Platform
                    </div>
                    <h2 class="text-4xl font-extrabold text-gray-900 mb-8 leading-tight">
                        Digitalisasi Ekonomi Desa Melalui <span class="text-primary italic">Portal BUMDesa</span>
                    </h2>
                    <div class="prose prose-lg text-gray-600 space-y-6 text-justify">
                        <p><strong>PortalBUMDes</strong> merupakan platform digital yang dirancang untuk mendukung
                            pengelolaan dan pengembangan Badan Usaha Milik Desa (BUMDesa) secara modern, transparan, dan
                            terintegrasi.</p>
                        <p>Platform ini hadir sebagai solusi praktis bagi BUMDesa untuk memiliki <strong>website resmi
                                secara gratis</strong>, tanpa memerlukan kemampuan teknis atau keahlian coding. Dengan
                            demikian, setiap BUMDesa dapat dengan mudah menyampaikan informasi kepada masyarakat,
                            meningkatkan transparansi, serta memperkuat kepercayaan publik.</p>
                        <p>Selain sebagai sarana informasi publik, PortalBUMDes juga berfungsi sebagai <strong>alat bantu
                                bagi pemerintah kabupaten</strong> dalam melakukan identifikasi, pemantauan, dan analisa
                            data BUMDesa secara lebih efektif dan terstruktur. Data yang terintegrasi memungkinkan
                            pengambilan kebijakan yang lebih tepat sasaran dalam pengembangan ekonomi desa.</p>
                        <p>PortalBUMDes dikembangkan oleh <strong>BumdesPro Official</strong>, sebuah inisiatif yang
                            berfokus pada digitalisasi pengelolaan BUMDesa. Kami juga menyediakan aplikasi pendukung melalui
                            <strong>bumdespro.my.id</strong> untuk menyusun laporan keuangan, SPJ digital, dan administrasi
                            usaha desa secara efisien.</p>
                    </div>
                </section>

                <!-- Section 2: Layanan & Produk -->
                <section id="layanan-produk">
                    <div
                        class="inline-flex items-center px-4 py-2 rounded-full bg-accent/20 text-accent text-xs font-bold uppercase tracking-widest mb-6 border border-accent/20">
                        Solusi Terpadu
                    </div>
                    <h2 class="text-4xl font-extrabold text-gray-900 mb-8">Layanan & Produk</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Item 1 -->
                        <div
                            class="group p-8 bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl hover:border-primary/20 transition-all duration-300">
                            <div
                                class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center mb-6 group-hover:bg-primary group-hover:text-white transition-colors duration-300">
                                <i class="fa-solid fa-desktop text-2xl"></i>
                            </div>
                            <h4 class="font-bold text-xl text-gray-900 group-hover:text-primary transition-colors">Aplikasi
                                Website</h4>
                            <p class="text-primary/70 text-sm font-semibold mt-1">portalbumdes.com</p>
                            <p class="text-gray-500 text-sm mt-4 leading-relaxed line-clamp-3">Platform website resmi
                                gratis untuk publikasi profil, unit usaha, kegiatan, serta transparansi informasi BUMDesa.
                            </p>
                        </div>
                        <!-- Item 2 -->
                        <div
                            class="group p-8 bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl hover:border-green-400/20 transition-all duration-300">
                            <div
                                class="w-14 h-14 rounded-2xl bg-green-50 flex items-center justify-center mb-6 group-hover:bg-green-600 group-hover:text-white transition-colors duration-300">
                                <i class="fa-solid fa-calculator text-2xl text-green-600 group-hover:text-white"></i>
                            </div>
                            <h4 class="font-bold text-xl text-gray-900 group-hover:text-green-600 transition-colors">
                                Aplikasi Keuangan</h4>
                            <p class="text-green-600/70 text-sm font-semibold mt-1">bumdespro.my.id</p>
                            <p class="text-gray-500 text-sm mt-4 leading-relaxed line-clamp-3">Membantu pengurus mengelola
                                keuangan secara tertib, mulai dari transaksi hingga rekapitulasi data siap lapor.</p>
                        </div>
                        <!-- Item 3 -->
                        <div
                            class="group p-8 bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl hover:border-purple-400/20 transition-all duration-300">
                            <div
                                class="w-14 h-14 rounded-2xl bg-purple-50 flex items-center justify-center mb-6 group-hover:bg-purple-600 group-hover:text-white transition-colors duration-300">
                                <i class="fa-solid fa-file-invoice text-2xl text-purple-600 group-hover:text-white"></i>
                            </div>
                            <h4 class="font-bold text-xl text-gray-900 group-hover:text-purple-600 transition-colors">
                                Digitalisasi Dokumen</h4>
                            <p class="text-purple-600/70 text-sm font-semibold mt-1">bumdespro.my.id</p>
                            <p class="text-gray-500 text-sm mt-4 leading-relaxed line-clamp-3">Memudahkan penyusunan
                                dokumen dan SPJ secara digital, cepat, dan rapi sesuai kebutuhan pelaporan.</p>
                        </div>
                    </div>
                </section>

                <!-- Section 3: FAQ -->
                <section id="faq">
                    <div class="max-w-3xl">
                        <div
                            class="inline-flex items-center px-4 py-2 rounded-full bg-primary/10 text-primary text-xs font-bold uppercase tracking-widest mb-6 border border-primary/20">
                            Support & FAQ
                        </div>
                        <h2 class="text-4xl font-extrabold text-gray-900 mb-4">Pusat Bantuan</h2>
                        <p class="text-gray-500 mb-10 text-lg">Temukan jawaban cepat untuk pertanyaan umum mengenai Portal
                            BUMDesa.</p>

                        <div class="space-y-4" x-data="{ active: null }">
                            <!-- FAQ 1 -->
                            <div
                                class="rounded-3xl border border-gray-100 bg-white overflow-hidden shadow-sm hover:shadow-md transition-all">
                                <button @click="active = active === 1 ? null : 1"
                                    class="w-full text-left p-6 flex justify-between items-center group">
                                    <span class="font-extrabold text-gray-800 text-lg"
                                        :class="active === 1 ? 'text-primary' : ''">01. Apa itu PortalBUMDes?</span>
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center transition-all duration-300"
                                        :class="active === 1 ? 'bg-primary text-white rotate-180' :
                                            'bg-gray-100 text-gray-400 group-hover:bg-blue-50 group-hover:text-primary'">
                                        <i class="fa-solid fa-chevron-down text-xs"></i>
                                    </div>
                                </button>
                                <div class="overflow-hidden transition-all duration-300" x-show="active === 1" x-collapse>
                                    <div class="p-6 pt-0 text-gray-600 leading-relaxed border-t border-gray-50">
                                        PortalBUMDes adalah platform digital yang membantu BUMDesa memiliki website resmi
                                        secara gratis, serta memudahkan pengelolaan data dan informasi secara terintegrasi.
                                    </div>
                                </div>
                            </div>

                            <!-- FAQ 2 -->
                            <div
                                class="rounded-3xl border border-gray-100 bg-white overflow-hidden shadow-sm hover:shadow-md transition-all">
                                <button @click="active = active === 2 ? null : 2"
                                    class="w-full text-left p-6 flex justify-between items-center group">
                                    <span class="font-extrabold text-gray-800 text-lg"
                                        :class="active === 2 ? 'text-primary' : ''">02. Apakah harus bisa coding?</span>
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center transition-all duration-300"
                                        :class="active === 2 ? 'bg-primary text-white rotate-180' :
                                            'bg-gray-100 text-gray-400 group-hover:bg-blue-50 group-hover:text-primary'">
                                        <i class="fa-solid fa-chevron-down text-xs"></i>
                                    </div>
                                </button>
                                <div class="overflow-hidden transition-all duration-300" x-show="active === 2" x-collapse>
                                    <div class="p-6 pt-0 text-gray-600 leading-relaxed border-t border-gray-50">
                                        Tidak. PortalBUMDes dirancang agar mudah digunakan tanpa kemampuan coding, sehingga
                                        pengurus BUMDesa dapat langsung mengelola website dan data secara mandiri.
                                    </div>
                                </div>
                            </div>

                            <!-- FAQ 3 -->
                            <div
                                class="rounded-3xl border border-gray-100 bg-white overflow-hidden shadow-sm hover:shadow-md transition-all">
                                <button @click="active = active === 3 ? null : 3"
                                    class="w-full text-left p-6 flex justify-between items-center group">
                                    <span class="font-extrabold text-gray-800 text-lg"
                                        :class="active === 3 ? 'text-primary' : ''">03. Apakah layanan ini berbayar?</span>
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center transition-all duration-300"
                                        :class="active === 3 ? 'bg-primary text-white rotate-180' :
                                            'bg-gray-100 text-gray-400 group-hover:bg-blue-50 group-hover:text-primary'">
                                        <i class="fa-solid fa-chevron-down text-xs"></i>
                                    </div>
                                </button>
                                <div class="overflow-hidden transition-all duration-300" x-show="active === 3" x-collapse>
                                    <div class="p-6 pt-0 text-gray-600 leading-relaxed border-t border-gray-50">
                                        Untuk pembuatan dan pengelolaan website BUMDesa, layanan ini disediakan secara
                                        <strong>GRATIS</strong>. Beberapa produk atau layanan tambahan bersifat opsional.
                                    </div>
                                </div>
                            </div>

                            <!-- FAQ 4 -->
                            <div
                                class="rounded-3xl border border-gray-100 bg-white overflow-hidden shadow-sm hover:shadow-md transition-all">
                                <button @click="active = active === 4 ? null : 4"
                                    class="w-full text-left p-6 flex justify-between items-center group">
                                    <span class="font-extrabold text-gray-800 text-lg"
                                        :class="active === 4 ? 'text-primary' : ''">04. Manfaat bagi pemerintah
                                        kabupaten?</span>
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center transition-all duration-300"
                                        :class="active === 4 ? 'bg-primary text-white rotate-180' :
                                            'bg-gray-100 text-gray-400 group-hover:bg-blue-50 group-hover:text-primary'">
                                        <i class="fa-solid fa-chevron-down text-xs"></i>
                                    </div>
                                </button>
                                <div class="overflow-hidden transition-all duration-300" x-show="active === 4" x-collapse>
                                    <div class="p-6 pt-0 text-gray-600 leading-relaxed border-t border-gray-50">
                                        Membantu monitoring perkembangan BUMDesa secara real-time, identifikasi data cepat,
                                        serta analisa kinerja BUMDesa berbasis data secara lebih efektif dan terstruktur
                                        untuk pengambilan kebijakan PMD.
                                    </div>
                                </div>
                            </div>

                            <!-- FAQ 5 -->
                            <div
                                class="rounded-3xl border border-gray-100 bg-white overflow-hidden shadow-sm hover:shadow-md transition-all">
                                <button @click="active = active === 5 ? null : 5"
                                    class="w-full text-left p-6 flex justify-between items-center group">
                                    <span class="font-extrabold text-gray-800 text-lg"
                                        :class="active === 5 ? 'text-primary' : ''">05. Apa itu BumdesPro?</span>
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center transition-all duration-300"
                                        :class="active === 5 ? 'bg-primary text-white rotate-180' :
                                            'bg-gray-100 text-gray-400 group-hover:bg-blue-50 group-hover:text-primary'">
                                        <i class="fa-solid fa-chevron-down text-xs"></i>
                                    </div>
                                </button>
                                <div class="overflow-hidden transition-all duration-300" x-show="active === 5" x-collapse>
                                    <div class="p-6 pt-0 text-gray-600 leading-relaxed border-t border-gray-50">
                                        BumdesPro adalah penyedia layanan digitalisasi BUMDesa yang mengembangkan platform
                                        Portal BUMDesa serta berbagai aplikasi pendukung pengelolaan administrasi dan
                                        keuangan desa.
                                    </div>
                                </div>
                            </div>

                            <!-- FAQ 6 -->
                            <div
                                class="rounded-3xl border border-gray-100 bg-white overflow-hidden shadow-sm hover:shadow-md transition-all">
                                <button @click="active = active === 6 ? null : 6"
                                    class="w-full text-left p-6 flex justify-between items-center group">
                                    <span class="font-extrabold text-gray-800 text-lg"
                                        :class="active === 6 ? 'text-primary' : ''">06. Bagaimana cara mendaftar?</span>
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center transition-all duration-300"
                                        :class="active === 6 ? 'bg-primary text-white rotate-180' :
                                            'bg-gray-100 text-gray-400 group-hover:bg-blue-50 group-hover:text-primary'">
                                        <i class="fa-solid fa-chevron-down text-xs"></i>
                                    </div>
                                </button>
                                <div class="overflow-hidden transition-all duration-300" x-show="active === 6" x-collapse>
                                    <div class="p-6 pt-0 text-gray-600 leading-relaxed border-t border-gray-50">
                                        Anda dapat mendaftar langsung melalui tombol registrasi di platform ini. Ikuti
                                        langkah-langkah pengisian profil BUMDesa, maka website resmi BUMDesa Anda akan
                                        langsung aktif.
                                    </div>
                                </div>
                            </div>

                            <!-- FAQ 7 -->
                            <div
                                class="rounded-3xl border border-gray-100 bg-white overflow-hidden shadow-sm hover:shadow-md transition-all">
                                <button @click="active = active === 7 ? null : 7"
                                    class="w-full text-left p-6 flex justify-between items-center group">
                                    <span class="font-extrabold text-gray-800 text-lg"
                                        :class="active === 7 ? 'text-primary' : ''">07. Bisa mengunggah dokumen
                                        laporan?</span>
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center transition-all duration-300"
                                        :class="active === 7 ? 'bg-primary text-white rotate-180' :
                                            'bg-gray-100 text-gray-400 group-hover:bg-blue-50 group-hover:text-primary'">
                                        <i class="fa-solid fa-chevron-down text-xs"></i>
                                    </div>
                                </button>
                                <div class="overflow-hidden transition-all duration-300" x-show="active === 7" x-collapse>
                                    <div class="p-6 pt-0 text-gray-600 leading-relaxed border-t border-gray-50">
                                        Ya. Platform ini menyediakan fitur unggah dokumen untuk mempublikasikan laporan
                                        perkembangan, laporan keuangan, atau dokumen transparansi lainnya kepada publik.
                                    </div>
                                </div>
                            </div>

                            <!-- FAQ 8 -->
                            <div
                                class="rounded-3xl border border-gray-100 bg-white overflow-hidden shadow-sm hover:shadow-md transition-all">
                                <button @click="active = active === 8 ? null : 8"
                                    class="w-full text-left p-6 flex justify-between items-center group">
                                    <span class="font-extrabold text-gray-800 text-lg"
                                        :class="active === 8 ? 'text-primary' : ''">08. Apakah tersedia panduan
                                        penggunaan?</span>
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center transition-all duration-300"
                                        :class="active === 8 ? 'bg-primary text-white rotate-180' :
                                            'bg-gray-100 text-gray-400 group-hover:bg-blue-50 group-hover:text-primary'">
                                        <i class="fa-solid fa-chevron-down text-xs"></i>
                                    </div>
                                </button>
                                <div class="overflow-hidden transition-all duration-300" x-show="active === 8" x-collapse>
                                    <div class="p-6 pt-0 text-gray-600 leading-relaxed border-t border-gray-50">
                                        Kami menyediakan panduan lengkap dalam bentuk teks dan video tutorial di kanal
                                        YouTube **Dany Ndeso** untuk memudahkan pengurus dalam mengoperasikan platform.
                                    </div>
                                </div>
                            </div>

                            <!-- FAQ 9 -->
                            <div
                                class="rounded-3xl border border-gray-100 bg-white overflow-hidden shadow-sm hover:shadow-md transition-all">
                                <button @click="active = active === 9 ? null : 9"
                                    class="w-full text-left p-6 flex justify-between items-center group">
                                    <span class="font-extrabold text-gray-800 text-lg"
                                        :class="active === 9 ? 'text-primary' : ''">09. Bagaimana jika mengalami
                                        kendala?</span>
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center transition-all duration-300"
                                        :class="active === 9 ? 'bg-primary text-white rotate-180' :
                                            'bg-gray-100 text-gray-400 group-hover:bg-blue-50 group-hover:text-primary'">
                                        <i class="fa-solid fa-chevron-down text-xs"></i>
                                    </div>
                                </button>
                                <div class="overflow-hidden transition-all duration-300" x-show="active === 9" x-collapse>
                                    <div class="p-6 pt-0 text-gray-600 leading-relaxed border-t border-gray-50">
                                        Jika menghadapi kendala teknis atau pertanyaan lebih lanjut, Anda dapat menghubungi
                                        tim support kami melalui kontak WhatsApp yang tersedia di bagian bawah halaman ini.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CTA Box -->
                    <div
                        class="mt-12 p-8 bg-gradient-to-br from-primary to-blue-800 rounded-3xl text-white shadow-2xl relative overflow-hidden group">
                        <i
                            class="fa-solid fa-comments absolute -bottom-4 -right-4 text-7xl text-white/10 group-hover:scale-110 transition-transform duration-500"></i>
                        <div class="relative z-10">
                            <h4 class="text-2xl font-bold text-accent mb-2">Punya Pertanyaan Lain?</h4>
                            <p class="text-blue-100 mb-6 text-sm">Tim support BumdesPro Official siap membantu kendala
                                teknis atau pertanyaan seputar platform.</p>
                            <div class="flex flex-wrap gap-4">
                                <a href="https://wa.me/6282247758730"
                                    class="px-6 py-3 bg-accent text-primary rounded-2xl font-bold hover:bg-yellow-400 transition-all flex items-center gap-2 shadow-lg shadow-accent/20">
                                    <i class="fa-brands fa-whatsapp text-lg"></i> Hubungi WhatsApp
                                </a>
                                <a href="mailto:dany.dwin@gmail.com"
                                    class="px-6 py-3 bg-white/10 text-white border border-white/20 rounded-2xl font-bold hover:bg-white/20 transition-all flex items-center gap-2">
                                    <i class="fa-solid fa-envelope"></i> Kirim Email
                                </a>
                            </div>
                        </div>
                    </div>
            </div>
            </section>
        </div>
    </div>
    </div>


@endsection
