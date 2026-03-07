@extends('layouts.admin')
@section('title', 'Dashboard Kabupaten')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Dashboard Admin {{ auth()->user()->kabupaten->name ?? 'Kabupaten' }}</h2>
    <p class="text-gray-500 text-sm mt-1">Pantau perkembangan dan keaktifan BUMDesa di wilayah Anda secara real-time.</p>
</div>

<!-- Statistik Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm border p-5 flex items-center group hover:shadow-md transition">
        <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-full flex justify-center items-center text-2xl group-hover:scale-110 transition shrink-0">
            <i class="fa-solid fa-house-flag"></i>
        </div>
        <div class="ml-4">
            <h3 class="text-gray-500 text-sm font-medium">BUMDes Terdaftar</h3>
            <p class="text-2xl font-bold text-gray-800">{{ number_format($totalBumdes) }}</p>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm border p-5 flex items-center group hover:shadow-md transition">
        <div class="w-14 h-14 bg-green-100 text-green-600 rounded-full flex justify-center items-center text-2xl group-hover:scale-110 transition shrink-0">
            <i class="fa-solid fa-check-circle"></i>
        </div>
        <div class="ml-4">
            <h3 class="text-gray-500 text-sm font-medium">BUMDes Aktif Sistem</h3>
            <p class="text-2xl font-bold text-gray-800">{{ number_format($activeBumdes) }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border p-5 flex items-center group hover:shadow-md transition">
        <div class="w-14 h-14 bg-purple-100 text-purple-600 rounded-full flex justify-center items-center text-2xl group-hover:scale-110 transition shrink-0">
            <i class="fa-solid fa-crown"></i>
        </div>
        <div class="ml-4">
            <h3 class="text-gray-500 text-sm font-medium">Langganan Aktif</h3>
            <p class="text-2xl font-bold text-gray-800">{{ number_format($subscriptionStats['active'] ?? 0) }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border p-5 flex items-center group hover:shadow-md transition">
        <div class="w-14 h-14 bg-red-100 text-red-600 rounded-full flex justify-center items-center text-2xl group-hover:scale-110 transition shrink-0">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <div class="ml-4">
            <h3 class="text-gray-500 text-sm font-medium">Perlu Perpanjang</h3>
            <p class="text-2xl font-bold text-gray-800">{{ number_format(($subscriptionStats['inactive'] ?? 0) + ($subscriptionStats['pending'] ?? 0)) }}</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Tabel BUMDesa Terbaru -->
    <div class="bg-white rounded-xl shadow-sm border">
        <div class="border-b px-6 py-4 flex justify-between items-center">
            <h3 class="font-bold text-gray-800">BUMDesa Pendaftar Terbaru</h3>
            <a href="{{ route('adminkab.bumdes.index') }}" class="text-sm font-semibold text-primary hover:underline">Lihat Semua</a>
        </div>
        <div class="p-0 overflow-x-auto">
            <table class="w-full text-sm text-left whitespace-nowrap">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3 font-medium">Nama BUMDesa</th>
                        <th class="px-6 py-3 font-medium">Status & Klasifikasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($latestBumdes as $b)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-800">{{ $b->name }}</div>
                            <div class="text-xs text-gray-500 mt-0.5"><i class="fa-solid fa-location-dot mr-1"></i> {{ $b->desa }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-block px-2 text-[10px] font-bold uppercase rounded 
                                @if($b->status === 'active') bg-green-100 text-green-700
                                @else bg-gray-100 text-gray-700 @endif">
                                {{ $b->status }}
                            </span>
                            @if($b->klasifikasi)
                            <span class="inline-block px-2 text-[10px] font-bold uppercase rounded bg-blue-100 text-blue-700 ml-1">
                                {{ $b->klasifikasi }}
                            </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" class="px-6 py-8 text-center text-gray-400">Belum ada BUMDesa yang terdaftar di wilayah ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Informasi / Catatan Admin -->
    <div class="bg-gradient-to-br from-primary to-primary-900 rounded-xl shadow-sm border p-6 text-white relative overflow-hidden">
        <div class="absolute -right-10 -top-10 text-white opacity-10">
            <i class="fa-solid fa-map-location-dot text-9xl"></i>
        </div>
        <h3 class="font-bold text-xl mb-3 relative z-10"><i class="fa-solid fa-bullhorn mr-2"></i> Informasi Sistem</h3>
        <p class="text-sm leading-relaxed text-blue-100 relative z-10">
            Selamat datang di Portal BUMDesa.<br><br>
            Sebagai Admin Kabupaten, Anda memiliki kendali penuh untuk memantau, memverifikasi, dan membantu pendaftaran BUMDesa di wilayah Anda. 
            Pastikan seluruh data yang dimasukkan oleh BUMDesa akurat guna mendukung pembinaan yang tepat sasaran.
        </p>
        <div class="mt-6 relative z-10">
            <a href="{{ route('adminkab.bumdes.index') }}" class="inline-block bg-white text-primary font-bold px-4 py-2 rounded-lg text-sm shadow hover:bg-gray-50 transition">
                <i class="fa-solid fa-arrow-right-long mr-2"></i> Kelola BUMDesa Sekarang
            </a>
        </div>
    </div>
</div>
@endsection
