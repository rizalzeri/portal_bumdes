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

            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4 border-b pb-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Semua {{ $title }}</h2>
                    <p class="text-gray-500 text-sm">Menampilkan dari seluruh BUMDesa di Indonesia.</p>
                </div>
                <a href="{{ route('home') }}"
                    class="text-sm inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition"><i
                        class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Beranda</a>
            </div>

            @if (count($items) === 0)
                <div class="text-center py-16 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                    <i class="fa-solid fa-folder-open text-5xl mb-4 text-gray-300"></i>
                    <h3 class="text-lg font-medium text-gray-900">Belum Ada Data</h3>
                    <p class="mt-1 text-gray-500">Data {{ strtolower($title) }} belum tersedia.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 w-full mb-8">
                    @foreach ($items as $item)
                        <!-- Dynamic rendering based on type -->
                        <a href="{{ $item->bumdes ? url('bumdes/' . $item->bumdes->slug . $anchor) : 'javascript:void(0)' }}"
                            class="block bg-white border border-gray-200 rounded-xl p-5 hover:shadow-lg hover:border-accent transition-all group h-full flex flex-col">

                            <!-- Header with BUMDes Context -->
                            <div class="mb-4 pb-3 border-b flex items-center gap-2">
                                <div
                                    class="w-7 h-7 bg-gray-100 rounded-full flex items-center justify-center overflow-hidden border shadow-sm shrink-0">
                                    @if ($item->bumdes && $item->bumdes->logo)
                                        <img src="{{ asset('storage/' . $item->bumdes->logo) }}"
                                            class="w-full h-full object-cover">
                                    @else
                                        <div
                                            class="w-full h-full flex items-center justify-center {{ $item->bumdes ? 'bg-gray-50' : 'bg-blue-600' }}">
                                            <i
                                                class="fa-solid {{ $item->bumdes ? 'fa-store text-gray-400' : 'fa-leaf text-white' }} text-[10px]"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p
                                        class="text-[10px] font-bold text-gray-900 group-hover:text-primary transition-colors truncate">
                                        {{ $item->bumdes ? $item->bumdes->name : 'Portal Pusat' }}</p>
                                    <p class="text-[9px] text-gray-500 truncate">
                                        {{ $item->bumdes && $item->bumdes->kabupaten ? $item->bumdes->kabupaten->name : 'Informasi Global' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Content Render Specific -->
                            <div class="flex-grow">
                                @if ($type === 'materi')
                                    <div class="flex flex-col items-center text-center">
                                        <div class="w-16 h-16 bg-red-50 rounded-xl flex items-center justify-center mb-3">
                                            <i class="fa-solid fa-file-pdf text-3xl text-red-500"></i>
                                        </div>
                                        <h4 class="font-bold text-gray-800 text-sm line-clamp-2 min-h-[40px]">
                                            {{ $item->title }}</h4>
                                        <p class="text-[10px] text-gray-500 mt-2">{{ $item->type }} -
                                            {{ $item->year ?? date('Y') }}</p>
                                    </div>
                                @elseif($type === 'kabar')
                                    <div class="flex flex-col gap-3">
                                        <div class="w-full h-32 bg-gray-100 rounded-lg overflow-hidden shrink-0">
                                            @if ($item->image)
                                                <img src="{{ asset('storage/' . $item->image) }}"
                                                    class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center">
                                                    <i class="fa-solid fa-newspaper text-gray-300 text-3xl"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-900 text-sm mb-1 line-clamp-2 min-h-[40px]">
                                                {{ $item->title }}</h4>
                                            <p class="text-[10px] text-gray-500 mb-2">
                                                {{ $item->created_at->translatedFormat('d F Y') }}</p>
                                            <p class="text-xs text-gray-600 line-clamp-2">
                                                {{ Str::limit(strip_tags($item->content), 80) }}</p>
                                        </div>
                                    </div>
                                @elseif($type === 'katalog')
                                    <div class="flex flex-col gap-3">
                                        <div
                                            class="w-full h-32 bg-gray-100 rounded-lg overflow-hidden border flex items-center justify-center shrink-0">
                                            @if ($item->image)
                                                <img src="{{ asset('storage/' . $item->image) }}"
                                                    class="w-full h-full object-cover">
                                            @else
                                                <i class="fa-solid fa-box text-gray-300 text-3xl"></i>
                                            @endif
                                        </div>
                                        <div class="flex flex-col">
                                            <span
                                                class="text-[10px] font-semibold text-accent uppercase tracking-wider mb-1">{{ $item->category ?? 'Produk' }}</span>
                                            <h4 class="font-bold text-gray-900 text-sm line-clamp-1">
                                                {{ $item->name ?? $item->title }}
                                            </h4>
                                            <p class="text-xs text-gray-600 mt-1 line-clamp-2 h-8 leading-tight">
                                                {{ $item->description }}</p>
                                            @if ($item->price)
                                                <div class="mt-2 text-primary font-bold text-base">Rp
                                                    {{ number_format($item->price, 0, ',', '.') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                @elseif($type === 'mitra')
                                    <div class="flex flex-col items-center text-center py-2">
                                        <div
                                            class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center border overflow-hidden mb-3">
                                            @if ($item->logo)
                                                <img src="{{ asset('storage/' . $item->logo) }}"
                                                    class="w-full h-full object-contain p-2">
                                            @else
                                                <i class="fa-solid fa-handshake text-gray-300 text-3xl"></i>
                                            @endif
                                        </div>
                                        <h4 class="font-bold text-sm text-gray-800 line-clamp-2 h-10">{{ $item->name }}
                                        </h4>
                                    </div>
                                @elseif($type === 'galeri')
                                    <div class="flex flex-col gap-3">
                                        <div class="w-full h-32 bg-gray-100 rounded-lg overflow-hidden border shrink-0">
                                            @php $imageUrl = $item->image ?? $item->image_url; @endphp
                                            @if ($item->type === 'video' || $item->video_url)
                                                <div class="w-full h-full relative">
                                                    <img src="https://img.youtube.com/vi/{{ \Illuminate\Support\Str::afterLast($item->video_url, 'v=') }}/0.jpg"
                                                        class="w-full h-full object-cover">
                                                    <div
                                                        class="absolute inset-0 flex items-center justify-center bg-black/20">
                                                        <i class="fa-solid fa-play text-white text-xl"></i>
                                                    </div>
                                                </div>
                                            @elseif ($imageUrl)
                                                <img src="{{ asset('storage/' . $imageUrl) }}"
                                                    class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center">
                                                    <i class="fa-solid fa-image text-gray-300 text-3xl"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <h4 class="font-bold text-sm text-gray-900 line-clamp-2 h-10">{{ $item->title }}
                                        </h4>
                                    </div>
                                @elseif($type === 'pengumuman')
                                    <div class="flex flex-col gap-2">
                                        <div class="flex items-center gap-2 mb-1">
                                            <div class="w-7 h-7 bg-amber-100 rounded-full flex items-center justify-center">
                                                <i class="fa-solid fa-bell text-amber-600 text-[10px]"></i>
                                            </div>
                                            <span
                                                class="text-[10px] text-gray-400">{{ $item->created_at->format('d M Y') }}</span>
                                        </div>
                                        <h4 class="font-bold text-gray-900 text-sm line-clamp-2 min-h-[40px]">
                                            {{ $item->title }}</h4>
                                        <p class="text-xs text-gray-600 line-clamp-3 italic">
                                            {{ Str::limit(strip_tags($item->content), 100) }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>

                <!-- Pagination Controls -->
                <div class="mt-6">
                    {{ $items->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
