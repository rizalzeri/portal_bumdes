@extends('layouts.public')
@section('title', 'BUMDesa "' . $bumdes->name . '" Desa')

@section('content')
    <!-- Header Section -->
    <div class="bg-primary text-white py-16 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col md:flex-row items-center md:items-start gap-8">
                <div
                    class="w-32 h-32 md:w-48 md:h-48 bg-white rounded-2xl shadow-2xl p-2 flex items-center justify-center overflow-hidden flex-shrink-0 border-4 border-accent">
                    @if ($bumdes->logo)
                        <img src="{{ asset('storage/' . $bumdes->logo) }}" alt="Logo {{ $bumdes->name }}"
                            class="max-w-full max-h-full object-contain">
                    @else
                        <i class="fa-solid fa-leaf text-6xl text-accent"></i>
                    @endif
                </div>
                <div class="flex-grow text-center md:text-left">
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $bumdes->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }} mb-4">
                        <i
                            class="fa-solid {{ $bumdes->status === 'active' ? 'fa-circle-check' : 'fa-circle-xmark' }} mr-2"></i>
                        Status BUMDesa: {{ $bumdes->status === 'active' ? 'Aktif' : 'Tidak Aktif' }}
                    </span>
                    <h1 class="text-3xl md:text-5xl font-extrabold mb-4">BUMDesa "{{ $bumdes->name }}" Desa</h1>
                    <div
                        class="flex flex-col sm:flex-row flex-wrap gap-4 justify-center md:justify-start text-blue-100 text-sm md:text-base">
                        <div class="flex items-center"><i class="fa-solid fa-map-location-dot w-5 text-accent"></i>
                            {{ $bumdes->address ?? 'Alamat belum diatur' }}, {{ $bumdes->kabupaten->name }}</div>
                        @if ($bumdes->phone)
                            <div class="flex items-center"><i class="fa-solid fa-phone w-5 text-accent"></i>
                                {{ $bumdes->phone }}</div>
                        @endif
                        @if ($bumdes->email)
                            <div class="flex items-center"><i class="fa-solid fa-envelope w-5 text-accent"></i>
                                {{ $bumdes->email }}</div>
                        @endif
                        @if ($bumdes->nomor_sertifikat)
                            <div class="flex items-center"><i class="fa-solid fa-certificate w-5 text-accent"></i>
                                No. Badan Hukum: {{ $bumdes->nomor_sertifikat }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Breadcrumbs -->
        <nav class="flex mb-8 text-sm text-gray-500" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-2">
                <li><a href="{{ route('public.bumdes.list') }}" class="hover:text-primary"><i class="fa-solid fa-home"></i>
                        BUMDesa</a></li>
                <li><i class="fa-solid fa-chevron-right text-xs"></i></li>
                <li><a href="{{ route('public.bumdes.list', ['province_id' => $bumdes->kabupaten->province_id]) }}"
                        class="hover:text-primary">{{ $bumdes->kabupaten->province->name }}</a></li>
                <li><i class="fa-solid fa-chevron-right text-xs"></i></li>
                <li><a href="{{ route('public.bumdes.list', ['province_id' => $bumdes->kabupaten->province_id, 'kabupaten_id' => $bumdes->kabupaten_id]) }}"
                        class="hover:text-primary">{{ $bumdes->kabupaten->name }}</a></li>
                <li><i class="fa-solid fa-chevron-right text-xs"></i></li>
                <li class="font-bold text-primary" aria-current="page">{{ $bumdes->name }}</li>
            </ol>
        </nav>

        <!-- Main Content Layout -->
        <!-- Main Content Layout (Full Width Vertical) -->
        <div class="flex flex-col gap-8 w-full">

            <!-- 1. Tentang Kami -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="text-xl font-bold text-primary border-b pb-2 mb-4"><i
                        class="fa-solid fa-circle-info mr-2 text-accent"></i> Tentang Kami</h3>
                <div class="space-y-6">
                    <div>
                        <h4 class="font-bold text-gray-800 mb-2">Deskripsi</h4>
                        <p class="text-gray-600 leading-relaxed">
                            {{ $bumdes->about ?? 'Deskripsi singkat BUMDesa ini belum dilengkapi oleh pengelola.' }}</p>
                    </div>
                    @if ($bumdes->visi_misi)
                        <div>
                            <h4 class="font-bold text-gray-800 mb-2">Visi & Misi</h4>
                            <div class="text-gray-600 leading-relaxed whitespace-pre-line">{{ trim($bumdes->visi_misi) }}</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- 2. Unit Usaha Aktif -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="text-xl font-bold text-primary border-b pb-2 mb-4"><i
                        class="fa-solid fa-briefcase mr-2 text-accent"></i> Unit Usaha Aktif</h3>
                @if ($bumdes->unitUsahaAktifs->isEmpty())
                    <p class="text-sm text-gray-500 italic">Belum ada unit usaha didaftarkan.</p>
                @else
                    <div class="flex flex-wrap gap-3">
                        @foreach ($bumdes->unitUsahaAktifs as $unit)
                            <span
                                class="bg-blue-100 text-blue-800 text-xs font-bold px-4 py-2 rounded-full border border-blue-200">
                                {{ $unit->unitUsahaOption->name ?? $unit->sektor }}
                            </span>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- 3. Kepengurusan -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="text-xl font-bold text-primary border-b pb-2 mb-4"><i
                        class="fa-solid fa-sitemap mr-2 text-accent"></i> Kepengurusan</h3>
                @if ($bumdes->personils->isEmpty() && !$bumdes->struktur_organisasi_image)
                    <p class="text-sm text-gray-500 italic">Data struktur organisasi belum tersedia.</p>
                @else
                    @if ($bumdes->struktur_organisasi_image)
                        <a href="{{ asset('storage/' . $bumdes->struktur_organisasi_image) }}" target="_blank"
                            class="block mb-6 overflow-hidden rounded-lg border hover:opacity-80 transition cursor-zoom-in">
                            <img src="{{ asset('storage/' . $bumdes->struktur_organisasi_image) }}"
                                alt="Struktur Organisasi" class="w-full h-auto">
                        </a>
                        <p class="text-sm text-gray-600 mb-6">{{ $bumdes->struktur_organisasi_desc }}</p>
                    @endif

                    <div class="flex flex-wrap justify-center gap-6 mt-4">
                        @php
                            $allowedRoles = ['Penasehat', 'Pengawas', 'Direktur', 'Sekertaris', 'Bendahara'];
                            $filteredPersonils = $bumdes->personils
                                ->filter(function ($p) use ($allowedRoles) {
                                    return in_array($p->role ?? $p->position, $allowedRoles);
                                })
                                ->take(5);
                        @endphp
                        @foreach ($filteredPersonils as $person)
                            <div class="bg-white border text-center rounded-xl p-4 hover:shadow-lg transition w-40">
                                <div
                                    class="w-16 h-16 mx-auto mb-3 bg-gray-200 rounded-full flex items-center justify-center overflow-hidden border-2 border-primary shadow-sm">
                                    @if ($person->photo)
                                        <img src="{{ asset('storage/' . $person->photo) }}"
                                            class="w-full h-full object-cover">
                                    @else
                                        <i class="fa-solid fa-user text-gray-400 text-xl"></i>
                                    @endif
                                </div>
                                <h4 class="font-bold text-gray-900 leading-tight line-clamp-1 text-sm"
                                    title="{{ $person->name }}">
                                    {{ $person->name }}</h4>
                                <p class="text-[10px] text-primary font-semibold uppercase mt-1">
                                    {{ $person->role ?? $person->position }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- 4. Produk Desa -->
            <div class="bg-white rounded-xl shadow-sm border p-6" id="produk-desa">
                <h2 class="text-2xl font-bold text-primary border-b pb-2 mb-6"><i
                        class="fa-solid fa-box-open mr-2 text-accent"></i> Produk Desa</h2>
                @if ($bumdes->katalogProduks->isEmpty())
                    <div class="text-center py-8 text-gray-400 border border-dashed rounded-lg">
                        <i class="fa-solid fa-box text-3xl mb-2"></i>
                        <p>Belum ada produk yang ditampilkan.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($bumdes->katalogProduks as $produk)
                            <div
                                class="flex flex-col bg-gray-50 border rounded-lg overflow-hidden hover:shadow-md transition">
                                <div class="h-48 bg-gray-200 relative overflow-hidden flex-shrink-0">
                                    @if ($produk->image)
                                        <img src="{{ asset('storage/' . $produk->image) }}"
                                            class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center"><i
                                                class="fa-solid fa-image text-gray-400 text-3xl"></i></div>
                                    @endif
                                </div>
                                <div class="p-4 flex flex-col flex-grow">
                                    <span
                                        class="text-xs font-semibold text-accent uppercase tracking-wider mb-1">{{ $produk->category ?? 'Produk' }}</span>
                                    <h4 class="font-bold text-lg text-gray-900 mb-1 line-clamp-2">{{ $produk->title }}</h4>
                                    <p class="text-sm text-gray-600 line-clamp-2 flex-grow">{{ $produk->description }}</p>
                                    @if ($produk->price)
                                        <div class="mt-4 pt-4 border-t border-gray-200 text-primary font-bold text-lg">
                                            Rp {{ number_format($produk->price, 0, ',', '.') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- 5. Produk Ketahanan Pangan -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h2 class="text-2xl font-bold text-primary border-b pb-2 mb-6"><i
                        class="fa-solid fa-wheat-awn mr-2 text-accent"></i> Produk Ketahanan Pangan</h2>
                @if ($bumdes->produkKetahananPangans->isEmpty())
                    <div class="text-center py-8 text-gray-400 border border-dashed rounded-lg">
                        <i class="fa-solid fa-seedling text-3xl mb-2"></i>
                        <p>Belum ada data ketahanan pangan.</p>
                    </div>
                @else
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
                        @foreach ($bumdes->produkKetahananPangans as $pangan)
                            <div
                                class="bg-white border rounded-xl p-4 flex flex-col items-center text-center hover:shadow-md transition">
                                <div
                                    class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mb-3 text-green-600">
                                    <i class="fa-solid fa-wheat-awn"></i>
                                </div>
                                <span
                                    class="text-xs font-bold text-gray-500 uppercase tracking-tighter mb-1">Kategori</span>
                                <h4 class="font-black text-primary uppercase text-sm mb-2">
                                    {{ $pangan->produkKetapangOption->name ?? 'Pangan' }}</h4>

                                <div class="mt-auto pt-2 border-t w-full">
                                    <span class="text-[10px] text-gray-400 uppercase font-bold block">Produksi Per
                                        Tahun</span>
                                    <span class="font-bold text-gray-700">{{ $pangan->produksi_pertahun ?? '-' }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- 6. Kinerja & Capaian -->
            <div class="bg-white rounded-xl shadow-sm border p-6" id="kinerja-capaian">
                <h3 class="text-2xl font-bold text-primary border-b pb-2 mb-6"><i class="fa-solid fa-chart-line mr-2 text-accent"></i> Kinerja & Capaian</h3>
                
                @if ($bumdes->kinerjaCapaians->isEmpty())
                    <div class="text-center py-8 text-gray-400 border border-dashed rounded-lg">
                        <i class="fa-solid fa-chart-line text-3xl mb-2"></i>
                        <p>Belum ada data kinerja yang dipublikasikan.</p>
                    </div>
                @else
                    <div class="space-y-6">
                        @php
                            $kinerjaGrouped = $bumdes->kinerjaCapaians->sortByDesc('year')->groupBy('year');
                        @endphp
                        
                        @foreach($kinerjaGrouped as $year => $items)
                        <div class="border rounded-lg overflow-hidden">
                            <div class="bg-gray-50 px-4 py-3 border-b font-bold text-gray-800 flex justify-between items-center">
                                <span>Periode Tahun {{ $year }}</span>
                            </div>
                            <div class="p-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                @foreach($items as $item)
                                <div class="bg-white border p-3 rounded-lg shadow-sm hover:shadow-md transition">
                                    <span class="text-[10px] uppercase font-bold text-gray-400 block mb-1">{{ $item->description }}</span>
                                    <h5 class="text-sm font-bold text-gray-900 mb-2">{{ $item->title }}</h5>
                                    <p class="text-base font-mono text-primary font-bold">Rp {{ number_format($item->value, 0, ',', '.') }}</p>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- 7. Transparansi -->
            <div class="bg-white rounded-xl shadow-sm border p-6" id="materi-template">
                <h3 class="text-2xl font-bold text-primary border-b pb-2 mb-6"><i
                        class="fa-solid fa-file-contract mr-2 text-accent"></i> Transparansi</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="flex flex-col items-center justify-center p-4 bg-gray-50 rounded-xl border">
                        <i class="fa-solid fa-calendar-check text-blue-500 text-3xl mb-3"></i>
                        <span class="text-xs font-bold text-gray-500 uppercase">Musdes Terakhir</span>
                        <span class="font-bold text-gray-900 mt-1">
                            {{ $bumdes->musdes_terakhir ? \Carbon\Carbon::parse($bumdes->musdes_terakhir)->translatedFormat('d F Y') : 'Belum Ada Data' }}
                        </span>
                    </div>
                    <div class="flex flex-col items-center justify-center p-4 bg-gray-50 rounded-xl border">
                        <i class="fa-solid fa-magnifying-glass-chart text-green-500 text-3xl mb-3"></i>
                        <span class="text-xs font-bold text-gray-500 uppercase">Audit Internal Terakhir</span>
                        <span class="font-bold text-gray-900 mt-1">
                            {{ $bumdes->audit_internal_terakhir ? \Carbon\Carbon::parse($bumdes->audit_internal_terakhir)->translatedFormat('d F Y') : 'Belum Ada Data' }}
                        </span>
                    </div>
                    <div class="flex flex-col items-center justify-center p-4 bg-gray-50 rounded-xl border">
                        <i class="fa-solid fa-file-export text-red-500 text-3xl mb-3"></i>
                        <span class="text-xs font-bold text-gray-500 uppercase">Status Laporan Dinas</span>
                        <span class="font-bold {{ $bumdes->laporan_dinas_status === 'sudah' ? 'text-emerald-600' : 'text-gray-900' }} mt-1">
                            {{ $bumdes->laporan_dinas_status === 'sudah' ? 'Sudah Dikirim' : 'Belum Dikirim' }}
                        </span>
                        @if($bumdes->laporan_dinas_status === 'sudah' && $bumdes->laporan_dinas_link)
                            <a href="{{ $bumdes->laporan_dinas_link }}" target="_blank" class="mt-2 text-xs bg-blue-100 text-blue-700 px-3 py-1 rounded-full font-bold hover:bg-blue-200 transition">Lihat Laporan <i class="fa-solid fa-arrow-up-right-from-square ml-1 text-[10px]"></i></a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- 8. Mitra -->
            <div class="bg-white rounded-xl shadow-sm border p-6" id="mitra">
                <h3 class="text-2xl font-bold text-primary border-b pb-2 mb-6"><i
                        class="fa-solid fa-handshake mr-2 text-accent"></i> Mitra</h3>
                @if ($bumdes->mitraKerjasamas->isEmpty())
                    <p class="text-gray-500 italic text-center py-6">Data mitra kerjasama belum dilengkapi.</p>
                @else
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6">
                        @foreach ($bumdes->mitraKerjasamas as $mitra)
                            <div
                                class="bg-white border rounded-xl p-6 flex flex-col items-center justify-center text-center group hover:shadow-md transition">
                                <div
                                    class="w-20 h-20 bg-gray-50 rounded-full mb-4 flex items-center justify-center border overflow-hidden group-hover:border-accent">
                                    @if ($mitra->logo)
                                        <img src="{{ asset('storage/' . $mitra->logo) }}"
                                            class="w-full h-full object-contain p-2">
                                    @else
                                        <i class="fa-solid fa-building text-gray-300 text-3xl"></i>
                                    @endif
                                </div>
                                <h4 class="font-bold text-sm text-gray-800 line-clamp-2">{{ $mitra->name }}</h4>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- 9. Galeri Kegiatan -->
            <div class="bg-white rounded-xl shadow-sm border p-6" id="galeri-kegiatan">
                <h3 class="text-2xl font-bold text-primary border-b pb-2 mb-6"><i
                        class="fa-solid fa-images mr-2 text-accent"></i> Galeri Kegiatan BUMDesa</h3>
                @if ($bumdes->galeris->isEmpty())
                    <p class="text-gray-500 italic text-center py-6">Galeri kegiatan belum tersedia.</p>
                @else
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                        @foreach ($bumdes->galeris as $galeri)
                            <div class="relative group rounded-xl overflow-hidden shadow-sm hover:shadow-md transition">
                                <img src="{{ asset('storage/' . $galeri->image) }}"
                                    class="w-full h-40 object-cover group-hover:scale-110 transition duration-300">
                                <div
                                    class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center p-4">
                                    <p class="text-white text-sm font-bold text-center line-clamp-2">{{ $galeri->title }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- 10. Kabar & Opini -->
            @if ($bumdes->pengumuman->count() > 0 || $bumdes->artikels->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border p-6" id="kabar-opini">
                    <h2 class="text-2xl font-bold text-primary mb-6"><i
                            class="fa-solid fa-newspaper mr-2 text-accent"></i> Kabar & Opini BUMDesa</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Pengumuman -->
                        <div>
                            <h3 class="font-bold text-lg mb-4 text-accent"><i class="fa-solid fa-bullhorn mr-2"></i>
                                Pengumuman Terbaru</h3>
                            <div class="space-y-4">
                                @forelse($bumdes->pengumuman->sortByDesc('created_at')->take(3) as $umum)
                                    <div class="border rounded-lg p-4 bg-gray-50 hover:shadow transition">
                                        <h4 class="font-bold text-gray-900">{{ $umum->title }}</h4>
                                        <p class="text-[10px] font-bold text-accent uppercase tracking-wider mt-1">
                                            @if ($umum->bumdes)
                                                {{ $umum->bumdes->name }}
                                            @elseif($umum->type === 'kabupaten' && $umum->kabupaten)
                                                Portal {{ $umum->kabupaten->name }}
                                            @else
                                                Portal Pusat
                                            @endif
                                        </p>
                                        <p class="text-xs text-gray-400 mb-2">
                                            {{ $umum->created_at->translatedFormat('d F Y') }}</p>
                                        <p class="text-sm text-gray-700 line-clamp-2">
                                            {{ Str::limit(strip_tags($umum->content), 100) }}</p>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500 italic">Belum ada pengumuman.</p>
                                @endforelse
                            </div>
                        </div>

                        <!-- Artikel -->
                        <div>
                            <h3 class="font-bold text-lg mb-4 text-accent"><i class="fa-solid fa-pen-nib mr-2"></i>
                                Artikel & Opini</h3>
                            <div class="space-y-4">
                                @forelse($bumdes->artikels->sortByDesc('created_at')->take(3) as $art)
                                    <div class="border rounded-lg p-4 bg-gray-50 hover:shadow transition flex gap-4">
                                        @if ($art->image)
                                            <div
                                                class="w-20 h-20 flex-shrink-0 bg-gray-200 rounded-lg overflow-hidden hidden sm:block">
                                                <img src="{{ asset('storage/' . $art->image) }}"
                                                    class="w-full h-full object-cover">
                                            </div>
                                        @endif
                                        <div>
                                            <h4 class="font-bold text-gray-900 line-clamp-1">{{ $art->title }}</h4>
                                            <p class="text-xs text-gray-500 mt-1 mb-2">
                                                {{ $art->created_at->translatedFormat('d F Y') }} |
                                                {{ $art->author ?? 'Admin' }}</p>
                                            <p class="text-sm text-gray-700 line-clamp-2">
                                                {{ Str::limit(strip_tags($art->content), 80) }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500 italic">Belum ada artikel.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>

@endsection
