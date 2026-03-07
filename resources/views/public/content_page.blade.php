@extends('layouts.public')
@section('title', $title)

@section('content')
<div class="bg-primary pt-12 pb-24 text-center">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-extrabold text-white tracking-tight sm:text-5xl">{{ $title }}</h1>
        <p class="mt-4 max-w-2xl text-xl text-blue-200 mx-auto">{{ $desc }}</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-12 relative z-10 mb-24">
    <div class="bg-white rounded-xl shadow-xl border border-gray-100 p-8">
        
        <!-- Breadcrumbs Navigation -->
        <nav class="flex mb-8 border-b pb-4" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route($routeName) }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-accent">
                        <i class="fa-solid fa-map mr-2"></i> Indonesia (Semua Provinsi)
                    </a>
                </li>
                @if($selectedProvince)
                <li>
                    <div class="flex items-center">
                        <i class="fa-solid fa-chevron-right text-gray-400 mx-1 text-xs"></i>
                        <a href="{{ route($routeName, ['province_id' => $selectedProvince->id]) }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-accent md:ml-2">{{ $selectedProvince->name }}</a>
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

        @if(!$selectedProvince && !$selectedKabupaten)
            <!-- Step 1: Listing Provinces -->
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Pilih Provinsi</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                @foreach($provinces as $prov)
                    <a href="{{ route($routeName, ['province_id' => $prov->id]) }}" class="group flex items-center justify-center p-4 border rounded-lg hover:border-accent hover:shadow-md transition-all">
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
                <a href="{{ route($routeName) }}" class="text-sm text-primary hover:text-accent"><i class="fa-solid fa-arrow-left"></i> Kembali ke Provinsi</a>
            </div>
            
            @if($kabupatens->isEmpty())
                <div class="text-center py-12 text-gray-500">
                    <i class="fa-solid fa-folder-open text-4xl mb-4 text-gray-300"></i>
                    <p>Belum ada data kabupaten terdaftar di provinsi ini.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    @foreach($kabupatens as $kab)
                        <a href="{{ route($routeName, ['province_id' => $selectedProvince->id, 'kabupaten_id' => $kab->id]) }}" class="group flex items-center p-4 bg-gray-50 border rounded-lg hover:bg-white hover:border-accent hover:shadow-md transition-all">
                            <i class="fa-solid fa-building-flag text-primary text-xl mr-4 group-hover:text-accent transition-colors"></i>
                            <h3 class="font-semibold text-gray-800">{{ $kab->name }}</h3>
                        </a>
                    @endforeach
                </div>
            @endif

        @elseif($selectedKabupaten)
            <!-- Step 3: Listing Items inside Kabupaten -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $title }} di {{ $selectedKabupaten->name }}</h2>
                    <p class="text-gray-500 text-sm">Menampilkan maksimal 3 data teratas.</p>
                </div>
                <a href="{{ route($routeName, ['province_id' => $selectedProvince->id]) }}" class="text-sm inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition"><i class="fa-solid fa-arrow-left mr-2"></i> Kembali</a>
            </div>

            @if(count($items) === 0)
                <div class="text-center py-16 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                    <i class="fa-solid fa-folder-open text-5xl mb-4 text-gray-300"></i>
                    <h3 class="text-lg font-medium text-gray-900">Belum Ada Data</h3>
                    <p class="mt-1 text-gray-500">Belum ada {{ strtolower($title) }} yang terdaftar di kabupaten ini.</p>
                </div>
            @else
                <div class="flex flex-col gap-6 w-full">
                    @foreach($items as $item)
                    <!-- Dynamic rendering based on type -->
                    <a href="{{ route('public.bumdes.profile', $item->bumdes->slug) }}{{ $anchor }}" class="block bg-white border border-gray-200 rounded-xl p-6 hover:shadow-lg hover:border-accent transition-all group">
                        
                        <!-- Header with BUMDes Context -->
                        <div class="mb-4 pb-4 border-b flex items-center gap-3">
                            <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center overflow-hidden border shadow-sm">
                                @if($item->bumdes->logo)
                                    <img src="{{ asset('storage/'.$item->bumdes->logo) }}" class="w-full h-full object-cover">
                                @else
                                    <i class="fa-solid fa-leaf text-gray-400"></i>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900 group-hover:text-primary transition-colors">{{ $item->bumdes->name }}</p>
                                <p class="text-xs text-gray-500">BUMDesa - {{ $selectedKabupaten->name }}</p>
                            </div>
                        </div>

                        <!-- Content Render Specific -->
                        @if($type === 'materi')
                            <div class="flex items-center">
                                <i class="fa-solid fa-file-pdf text-4xl text-red-500 mr-4"></i>
                                <div>
                                    <h4 class="font-bold text-gray-800 text-lg">{{ $item->title }}</h4>
                                    <p class="text-sm text-gray-500 mt-1">{{ $item->type }} - {{ $item->year }}</p>
                                </div>
                            </div>
                        @elseif($type === 'kabar')
                            <div class="flex flex-col sm:flex-row gap-4">
                                @if($item->image)
                                <div class="w-full sm:w-32 h-32 flex-shrink-0 bg-gray-200 rounded-lg overflow-hidden">
                                    <img src="{{ asset('storage/'.$item->image) }}" class="w-full h-full object-cover">
                                </div>
                                @endif
                                <div>
                                    <h4 class="font-bold text-gray-900 text-xl mb-2">{{ $item->title }}</h4>
                                    <p class="text-xs text-gray-500 mb-2">{{ $item->created_at->translatedFormat('d F Y') }}</p>
                                    <p class="text-sm text-gray-700 line-clamp-2">{{ Str::limit(strip_tags($item->content), 120) }}</p>
                                </div>
                            </div>
                        @elseif($type === 'katalog')
                            <div class="flex flex-col sm:flex-row gap-4">
                                <div class="w-full sm:w-40 h-40 flex-shrink-0 bg-gray-100 rounded-lg overflow-hidden border flex items-center justify-center">
                                    @if($item->image)
                                        <img src="{{ asset('storage/'.$item->image) }}" class="w-full h-full object-cover">
                                    @else
                                        <i class="fa-solid fa-box text-gray-300 text-3xl"></i>
                                    @endif
                                </div>
                                <div class="flex flex-col justify-center">
                                    <span class="text-xs font-semibold text-accent uppercase tracking-wider mb-1">{{ $item->produkOption->name ?? 'Produk' }}</span>
                                    <h4 class="font-bold text-gray-900 text-xl">{{ $item->name }}</h4>
                                    <p class="text-sm text-gray-600 mt-2 line-clamp-2">{{ $item->description }}</p>
                                    @if($item->price)
                                        <div class="mt-3 text-primary font-bold text-lg">Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                                    @endif
                                </div>
                            </div>
                        @elseif($type === 'mitra')
                            <div class="flex items-center gap-4">
                                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center border overflow-hidden">
                                    @if($item->logo)
                                        <img src="{{ asset('storage/'.$item->logo) }}" class="w-full h-full object-contain p-2">
                                    @else
                                        <i class="fa-solid fa-handshake text-gray-300 text-3xl"></i>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="font-bold text-xl text-gray-800">{{ $item->name }}</h4>
                                </div>
                            </div>
                        @elseif($type === 'galeri')
                            <div class="flex flex-col sm:flex-row gap-4">
                                <div class="w-full sm:w-64 h-40 flex-shrink-0 bg-gray-200 rounded-lg overflow-hidden border">
                                    @if($item->image)
                                        <img src="{{ asset('storage/'.$item->image) }}" class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <div class="flex items-center">
                                    <h4 class="font-bold text-xl text-gray-900">{{ $item->title }}</h4>
                                </div>
                            </div>
                        @endif
                    </a>
                    @endforeach
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
