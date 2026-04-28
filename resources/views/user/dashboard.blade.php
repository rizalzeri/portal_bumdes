@extends('layouts.admin')
@section('title', 'Dashboard BUMDesa')

@section('content')
<div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Hi, Pengurus {{ $bumdes->name }}! 👋</h2>
        <p class="text-gray-500 text-sm mt-1">Selamat datang di Panel Manajemen BUMDesa Anda. Kelola seluruh aset dan informasi di sini.</p>
    </div>
    
    @if($subscription && $subscription->status === 'active')
        <div class="bg-gradient-to-r from-accent to-yellow-500 text-white px-4 py-2 rounded-lg shadow font-bold text-sm flex items-center">
            <i class="fa-solid fa-crown mr-2 mb-1"></i> Member Premium
        </div>
    @elseif($subscription && $subscription->status === 'pending')
        <a href="{{ route('user.langganan.index') }}" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg shadow font-bold text-sm flex items-center transition">
            <i class="fa-solid fa-triangle-exclamation mr-2"></i> Tagihan Menunggu Pembayaran
        </a>
    @else
        <a href="{{ route('user.langganan.index') }}" class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg shadow font-bold text-sm flex items-center transition">
            <i class="fa-solid fa-arrow-up-right-dots mr-2"></i> Upgrade ke Premium
        </a>
    @endif
</div>

<!-- Statistik Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm border p-5 flex flex-col hover:shadow-md transition relative overflow-hidden group">
        <div class="absolute -right-4 -bottom-4 text-blue-50 opacity-50 group-hover:scale-110 transition duration-500">
            <i class="fa-solid fa-store text-8xl"></i>
        </div>
        <h3 class="text-gray-500 text-sm font-medium z-10">Unit Usaha Aktif</h3>
        <p class="text-3xl font-bold text-gray-800 mt-2 z-10">{{ $bumdes->unit_usaha_count }}</p>
        <div class="mt-4 z-10 text-xs text-blue-500 font-bold"><a href="{{ route('user.unit_usaha.index') }}">Kelola Unit Usaha <i class="fa-solid fa-arrow-right ml-1"></i></a></div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm border p-5 flex flex-col hover:shadow-md transition relative overflow-hidden group">
        <div class="absolute -right-4 -bottom-4 text-green-50 opacity-50 group-hover:scale-110 transition duration-500">
            <i class="fa-solid fa-box text-8xl"></i>
        </div>
        <h3 class="text-gray-500 text-sm font-medium z-10">Produk BUMDes</h3>
        <p class="text-3xl font-bold text-gray-800 mt-2 z-10">{{ $bumdes->katalog_produk_count }}</p>
        <div class="mt-4 z-10 text-xs text-green-500 font-bold"><a href="{{ route('user.produk.index') }}">Lihat Katalog <i class="fa-solid fa-arrow-right ml-1"></i></a></div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border p-5 flex flex-col hover:shadow-md transition relative overflow-hidden group">
        <div class="absolute -right-4 -bottom-4 text-purple-50 opacity-50 group-hover:scale-110 transition duration-500">
            <i class="fa-solid fa-file-invoice-dollar text-8xl"></i>
        </div>
        <h3 class="text-gray-500 text-sm font-medium z-10">Laporan Finansial</h3>
        <p class="text-3xl font-bold text-gray-800 mt-2 z-10">{{ $bumdes->laporan_keuangan_count }}</p>
        <div class="mt-4 z-10 text-xs text-purple-500 font-bold"><a href="{{ route('user.finansial.index') }}">Lapor Keuangan <i class="fa-solid fa-arrow-right ml-1"></i></a></div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border p-5 flex flex-col hover:shadow-md transition relative overflow-hidden group">
        <div class="absolute -right-4 -bottom-4 text-orange-50 opacity-50 group-hover:scale-110 transition duration-500">
            <i class="fa-solid fa-newspaper text-8xl"></i>
        </div>
        <h3 class="text-gray-500 text-sm font-medium z-10">Total Artikel/Berita</h3>
        <p class="text-3xl font-bold text-gray-800 mt-2 z-10">{{ $bumdes->artikel_count }}</p>
        <div class="mt-4 z-10 text-xs text-orange-500 font-bold"><a href="{{ route('user.artikel.index') }}">Tulis Berita <i class="fa-solid fa-arrow-right ml-1"></i></a></div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2">
        <!-- Overview Profil / Quick actions -->
        <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
            <div class="border-b px-6 py-4 flex justify-between items-center bg-gray-50">
                <h3 class="font-bold text-gray-800"><i class="fa-solid fa-id-card mr-2 text-primary"></i> Kelengkapan Profil BUMDesa</h3>
                <a href="{{ route('user.profil.index') }}" class="text-sm border border-gray-300 px-3 py-1 rounded bg-white hover:bg-gray-50 font-medium">Lengkapi Profil</a>
            </div>
            <div class="p-6">
                <!-- Simple progress bar logic based on data availability (dummy visual) -->
                @php
                    $score = 20; // Base score
                    if($bumdes->logo) $score += 20;
                    if($bumdes->about) $score += 20;
                    if($bumdes->badan_hukum) $score += 20;
                    if($bumdes->phone) $score += 20;
                @endphp
                <div class="mb-4">
                    <div class="flex justify-between text-sm mb-1">
                        <span class="font-bold text-gray-700">Skor Kelengkapan Data</span>
                        <span class="font-bold text-primary">{{ $score }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-primary h-2.5 rounded-full" style="width: {{ $score }}%"></div>
                    </div>
                </div>
                
                @if($score < 100)
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mt-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fa-solid fa-circle-info text-yellow-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">Profil BUMDesa Anda belum lengkap. Silakan lengkapi logo, deskripsi singkat, dokumen badan hukum, dan kontak pada menu <a href="{{ route('user.profil.index') }}" class="font-bold underline">Profil BUMDesa</a> agar dipercaya oleh masyarakat dan mitra.</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection
