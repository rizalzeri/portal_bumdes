@extends('layouts.public')
@section('title', 'Kunjungi BUMDesa')

@section('content')
<div class="bg-primary pt-12 pb-24 text-center">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-extrabold text-white tracking-tight sm:text-5xl">Database BUMDesa</h1>
        <p class="mt-4 max-w-2xl text-xl text-blue-200 mx-auto">Sistem Informasi Basis Data BUMDesa Seluruh Indonesia.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-12 relative z-10 mb-24">
    <div class="bg-white rounded-xl shadow-xl border border-gray-100 p-8">
        
        <!-- Breadcrumbs Navigation -->
        <nav class="flex mb-8 border-b pb-4" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('public.bumdes.list') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-accent">
                        <i class="fa-solid fa-map mr-2"></i> Indonesia (Semua Provinsi)
                    </a>
                </li>
                @if($selectedProvince)
                <li>
                    <div class="flex items-center">
                        <i class="fa-solid fa-chevron-right text-gray-400 mx-1 text-xs"></i>
                        <a href="{{ route('public.bumdes.list', ['province_id' => $selectedProvince->id]) }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-accent md:ml-2">{{ $selectedProvince->name }}</a>
                    </div>
                </li>
                @endif
                @if($selectedKabupaten)
                <li>
                    <div class="flex items-center">
                        <i class="fa-solid fa-chevron-right text-gray-400 mx-1 text-xs"></i>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ $selectedKabupaten->name }}</span>
                    </div>
                </li>
                @endif
            </ol>
        </nav>

        <!-- Dynamic Content Rendering -->

        @if(!$selectedProvince && !$selectedKabupaten)
            <!-- Step 1: Listing Provinces -->
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Pilih Provinsi</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                @foreach($provinces as $prov)
                    <a href="{{ route('public.bumdes.list', ['province_id' => $prov->id]) }}" class="group flex items-center justify-center p-4 border rounded-lg hover:border-accent hover:shadow-md transition-all">
                        <div class="text-center">
                            <i class="fa-solid fa-location-dot text-primary text-2xl mb-2 group-hover:text-accent transition-colors"></i>
                            <h3 class="font-semibold text-gray-800">{{ $prov->name }}</h3>
                        </div>
                    </a>
                @endforeach
            </div>
            
        @elseif($selectedProvince && !$selectedKabupaten)
            <!-- Step 2: Listing Kabupatens inside Province -->
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Kabupaten di {{ $selectedProvince->name }}</h2>
                <a href="{{ route('public.bumdes.list') }}" class="text-sm text-primary hover:text-accent"><i class="fa-solid fa-arrow-left"></i> Kembali ke Provinsi</a>
            </div>
            
            @if($kabupatens->isEmpty())
                <div class="text-center py-12 text-gray-500">
                    <i class="fa-solid fa-folder-open text-4xl mb-4 text-gray-300"></i>
                    <p>Belum ada data kabupaten terdaftar di provinsi ini.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    @foreach($kabupatens as $kab)
                        <a href="{{ route('public.bumdes.list', ['province_id' => $selectedProvince->id, 'kabupaten_id' => $kab->id]) }}" class="group flex items-center p-4 bg-gray-50 border rounded-lg hover:bg-white hover:border-accent hover:shadow-md transition-all">
                            <i class="fa-solid fa-building-flag text-primary text-xl mr-4 group-hover:text-accent transition-colors"></i>
                            <h3 class="font-semibold text-gray-800">{{ $kab->name }}</h3>
                        </a>
                    @endforeach
                </div>
            @endif

        @elseif($selectedKabupaten)
            <!-- Step 3: Listing BUMDes inside Kabupaten -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Daftar BUMDesa di {{ $selectedKabupaten->name }}</h2>
                    <p class="text-gray-500 text-sm">Ditemukan {{ $bumdes->count() }} BUMDesa aktif.</p>
                </div>
                <a href="{{ route('public.bumdes.list', ['province_id' => $selectedProvince->id]) }}" class="text-sm inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition"><i class="fa-solid fa-arrow-left mr-2"></i> Kembali</a>
            </div>

            @if($bumdes->isEmpty())
                <div class="text-center py-16 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                    <i class="fa-solid fa-store-slash text-5xl mb-4 text-gray-300"></i>
                    <h3 class="text-lg font-medium text-gray-900">Belum Ada BUMDesa</h3>
                    <p class="mt-1 text-gray-500">Belum ada BUMDesa yang terdaftar atau aktif di kabupaten ini.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-6">
                    @foreach($bumdes as $bd)
                        <div class="flex flex-col bg-white border rounded-xl overflow-hidden hover:shadow-xl transition-shadow duration-300">
                            <!-- Header with dummy pattern if no logo -->
                            <div class="h-24 bg-gradient-to-r from-primary to-blue-800 relative">
                                <div class="absolute -bottom-8 left-4">
                                    <div class="w-16 h-16 bg-white rounded-full p-1 shadow-md border border-gray-100 flex items-center justify-center overflow-hidden">
                                        @if($bd->logo)
                                            <img src="{{ asset('storage/'.$bd->logo) }}" alt="Logo" class="w-full h-full object-cover rounded-full">
                                        @else
                                            <i class="fa-solid fa-leaf text-2xl text-accent"></i>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="p-6 pt-10 flex-grow flex flex-col">
                                <h3 class="font-bold text-lg text-gray-900 tracking-tight">{{ $bd->name }}</h3>
                                <p class="text-xs text-green-600 font-semibold mb-3"><i class="fa-solid fa-certificate"></i> {{ $bd->legal_status ?? 'Status Hukum Belum Diperbarui' }}</p>
                                
                                <p class="text-sm text-gray-500 mb-4 line-clamp-2 flex-grow">{{ $bd->about ?? 'Deskripsi profil BUMDesa belum dilengkapi.' }}</p>
                                
                                <div class="pt-4 border-t flex flex-col gap-2 relative">
                                    <a href="{{ route('public.bumdes.profile', $bd->slug) }}" class="inline-flex justify-center items-center py-2 px-4 shadow-sm text-sm font-medium rounded-md text-white bg-primary hover:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary w-full transition">
                                        Lihat Profil Lengkap <i class="fa-solid fa-arrow-right ml-2"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
