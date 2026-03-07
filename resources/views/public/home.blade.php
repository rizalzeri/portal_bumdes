@extends('layouts.public')
@section('title', 'Beranda')

@section('content')
<!-- Hero Section -->
<div class="relative bg-primary overflow-hidden">
    <div class="absolute inset-0">
        <img class="w-full h-full object-cover opacity-20" src="https://images.unsplash.com/photo-1596423735880-5c6fa713b41e?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" alt="BUMDesa Landscape">
        <div class="absolute inset-0 bg-primary mix-blend-multiply"></div>
    </div>
    <div class="relative max-w-7xl mx-auto py-24 px-4 sm:py-32 sm:px-6 lg:px-8 text-center text-white">
        <h1 class="text-4xl font-extrabold tracking-tight sm:text-5xl lg:text-6xl text-transparent bg-clip-text bg-gradient-to-r from-accent to-yellow-200">
            Portal BUMDesa
        </h1>
        <p class="mt-6 text-xl max-w-3xl mx-auto text-blue-100">
            Pusat Inspirasi dan Informasi BUMDesa se-Indonesia. Temukan potensi, produk, dan perkembangan ribuan BUMDesa untuk memajukan ekonomi desa bersama.
        </p>
        <div class="mt-10 flex justify-center gap-4">
            <a href="{{ route('public.bumdes.list') }}" class="px-8 py-4 border border-transparent text-lg font-bold rounded-full text-primary bg-accent hover:bg-yellow-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent shadow-lg transform transition hover:-translate-y-1">
                Kunjungi BUMDesa
            </a>
            <a href="{{ route('public.infografis') }}" class="px-8 py-4 border-2 border-accent text-lg font-bold rounded-full text-white hover:bg-accent hover:text-primary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent shadow-lg transform transition hover:-translate-y-1">
                Lihat Infografis
            </a>
        </div>
    </div>
</div>

<!-- Main Navigation Menu Cards Grid -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 -mt-16 relative z-10">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        
        <!-- Menu 1 -->
        <a href="{{ route('public.bumdes.list') }}" class="group bg-white rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border-b-4 border-accent h-full flex flex-col">
            <div class="p-8 flex-grow flex flex-col items-center text-center">
                <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                    <i class="fa-solid fa-map-location-dot text-4xl text-primary"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Website BUMDesa</h3>
                <p class="text-gray-500 text-sm flex-grow">Akses langsung ke ratusan profil BUMDesa di seluruh penjuru Indonesia.</p>
            </div>
            <div class="bg-gray-50 py-3 px-6 text-center text-primary font-semibold group-hover:bg-primary group-hover:text-white transition-colors">Telusuri <i class="fa-solid fa-arrow-right ml-1"></i></div>
        </a>

        <!-- Menu 2 -->
        <a href="{{ route('public.infografis') }}" class="group bg-white rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border-b-4 border-blue-500 h-full flex flex-col">
            <div class="p-8 flex-grow flex flex-col items-center text-center">
                <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                    <i class="fa-solid fa-chart-pie text-4xl text-blue-500"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Info Grafis Data</h3>
                <p class="text-gray-500 text-sm flex-grow">Statistik dan data perkembangan ekonomi BUMDesa secara real-time.</p>
            </div>
            <div class="bg-gray-50 py-3 px-6 text-center text-blue-600 font-semibold group-hover:bg-blue-500 group-hover:text-white transition-colors">Lihat Data <i class="fa-solid fa-arrow-right ml-1"></i></div>
        </a>

        <!-- Menu 3 -->
        <a href="{{ route('public.materi') }}" class="group bg-white rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border-b-4 border-green-500 h-full flex flex-col">
            <div class="p-8 flex-grow flex flex-col items-center text-center">
                <div class="w-20 h-20 bg-green-50 rounded-full flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                    <i class="fa-solid fa-file-pdf text-4xl text-green-500"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Materi & Template</h3>
                <p class="text-gray-500 text-sm flex-grow">Unduhkan panduan, SOP, dan regulasi resmi.</p>
            </div>
            <div class="bg-gray-50 py-3 px-6 text-center text-green-600 font-semibold group-hover:bg-green-500 group-hover:text-white transition-colors">Unduh <i class="fa-solid fa-arrow-right ml-1"></i></div>
        </a>

        <!-- Menu 4 -->
        <a href="{{ route('public.informasi') }}" class="group bg-white rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border-b-4 border-indigo-500 h-full flex flex-col">
            <div class="p-8 flex-grow flex flex-col items-center text-center">
                <div class="w-20 h-20 bg-indigo-50 rounded-full flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                    <i class="fa-solid fa-newspaper text-4xl text-indigo-500"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Kabar & Opini</h3>
                <p class="text-gray-500 text-sm flex-grow">Berita terbaru dan opini pakar terkait desa.</p>
            </div>
            <div class="bg-gray-50 py-3 px-6 text-center text-indigo-600 font-semibold group-hover:bg-indigo-500 group-hover:text-white transition-colors">Baca <i class="fa-solid fa-arrow-right ml-1"></i></div>
        </a>

        <!-- Menu 5 -->
        <a href="{{ route('public.katalog') }}" class="group bg-white rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border-b-4 border-teal-500 h-full flex flex-col">
            <div class="p-8 flex-grow flex flex-col items-center text-center">
                <div class="w-20 h-20 bg-teal-50 rounded-full flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                    <i class="fa-solid fa-shop text-4xl text-teal-500"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Katalog Produk</h3>
                <p class="text-gray-500 text-sm flex-grow">Pasar digital produk unggulan karya BUMDesa.</p>
            </div>
            <div class="bg-gray-50 py-3 px-6 text-center text-teal-600 font-semibold group-hover:bg-teal-500 group-hover:text-white transition-colors">Eksplorasi <i class="fa-solid fa-arrow-right ml-1"></i></div>
        </a>

        <!-- Menu 6 -->
        <a href="{{ route('public.mitra') }}" class="group bg-white rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border-b-4 border-red-500 h-full flex flex-col">
            <div class="p-8 flex-grow flex flex-col items-center text-center">
                <div class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                    <i class="fa-solid fa-handshake text-4xl text-red-500"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Mitra Kerjasama</h3>
                <p class="text-gray-500 text-sm flex-grow">Jaringan kerjasama strategis lintas sektor.</p>
            </div>
            <div class="bg-gray-50 py-3 px-6 text-center text-red-600 font-semibold group-hover:bg-red-500 group-hover:text-white transition-colors">Lihat Mitra <i class="fa-solid fa-arrow-right ml-1"></i></div>
        </a>

        <!-- Menu 7 -->
        <a href="{{ route('public.galeri') }}" class="group bg-white rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border-b-4 border-purple-500 h-full flex flex-col md:col-span-2 lg:col-span-3 xl:col-span-2">
            <div class="p-8 flex-grow flex flex-col items-center text-center justify-center">
                <div class="w-20 h-20 bg-purple-50 rounded-full flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                    <i class="fa-solid fa-images text-4xl text-purple-500"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Galeri Kegiatan BUMDesa</h3>
                <p class="text-gray-500 text-sm">Tangakapan momen dan video kegiatan inovatif para pejuang desa.</p>
            </div>
            <div class="bg-gray-50 py-3 px-6 text-center text-purple-600 font-semibold group-hover:bg-purple-500 group-hover:text-white transition-colors">Buka Galeri <i class="fa-solid fa-arrow-right ml-1"></i></div>
        </a>

    </div>
</div>

<!-- 3 BUMDes Terbaru Section -->
@if(isset($latestBumdes) && $latestBumdes->count() > 0)
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="text-center mb-10">
        <h2 class="text-3xl font-bold text-primary">Spill BUMDes Terbaru</h2>
    </div>

    <div class="space-y-6">
        @foreach($latestBumdes as $bumdes)
            <!-- Card Fullwidth Feed-style -->
            <a href="{{ route('public.bumdes.profile', $bumdes->slug ?? '') }}#produk-desa" class="block w-full bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-200 overflow-hidden group">
                <div class="p-6 md:p-8 flex flex-col md:flex-row gap-6 md:gap-8 items-start md:items-center">
                    
                    <!-- BUMDes Logo/Image -->
                    <div class="w-16 h-16 md:w-24 md:h-24 rounded-full bg-gray-50 border-2 border-gray-100 shrink-0 flex items-center justify-center overflow-hidden group-hover:border-primary transition-colors">
                        @if($bumdes->logo)
                            <img src="{{ asset('storage/'.$bumdes->logo) }}" class="w-full h-full object-cover">
                        @else
                            <i class="fa-solid fa-store text-2xl md:text-3xl text-gray-400"></i>
                        @endif
                    </div>
                    
                    <!-- Content Info -->
                    <div class="flex-grow">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-green-100 text-green-800">
                                BUMDesa Baru
                            </span>
                            <span class="text-xs text-gray-400">
                                <i class="fa-solid fa-clock mr-1"></i> Baru bergabung
                            </span>
                        </div>
                        
                        <!-- Nama BUMDes sebagai judul -->
                        <h3 class="text-xl md:text-2xl font-bold text-gray-900 group-hover:text-primary transition-colors mb-2">
                            {{ $bumdes->name }}
                        </h3>
                        
                        <div class="text-gray-500 text-sm mb-3">
                            <i class="fa-solid fa-location-dot mr-1"></i> {{ $bumdes->kabupaten->name ?? '' }}, {{ $bumdes->kabupaten->province->name ?? '' }}
                        </div>
                        
                        <!-- Konten produk/info desa -->
                        <p class="text-gray-600 text-sm md:text-base line-clamp-2 md:line-clamp-3">
                            {{ $bumdes->description ?? 'Jelajahi berbagai produk unggulan, potensi desa, dan informasi lengkap profil BUMDesa ini untuk mendukung perekonomian lokal.' }}
                        </p>
                    </div>

                    <!-- Action Icon -->
                    <div class="hidden md:flex shrink-0 items-center justify-center w-12 h-12 rounded-full bg-blue-50 group-hover:bg-primary group-hover:text-white transition-colors text-primary">
                        <i class="fa-solid fa-arrow-right"></i>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</div>
@endif

@endsection
