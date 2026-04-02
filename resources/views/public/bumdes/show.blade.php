@extends('layouts.public')
@section('title', $bumdes->name . ' Desa ' . ($bumdes->desa ?? ''))

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
                    <h1 class="text-3xl md:text-5xl font-extrabold mb-4">{{ $bumdes->name }} Desa {{ $bumdes->desa ?? '' }}</h1>
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
                    <div class="flex flex-wrap gap-4 mt-2">
                        @foreach ($bumdes->unitUsahaAktifs as $unit)
                            <div class="border-2 border-primary bg-blue-50 text-primary text-xl font-bold px-6 py-3 rounded-lg shadow-sm">
                                {{ $unit->unitUsahaOption->name ?? $unit->sektor }}
                            </div>
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
                            $allowedRoles = ['Penasehat', 'Pengawas', 'Direktur', 'Sekretaris', 'Bendahara'];
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

            @php
                $isPremium = $bumdes->langganan()->where('status', 'active')->where('end_date', '>', now())->exists();
                if (!$isPremium && $bumdes->user && $bumdes->user->subscription_status == 'active' && $bumdes->user->subscription_expiry > now()) {
                    $isPremium = true;
                }
            @endphp

            <!-- 4. Produk Desa -->
            @if($isPremium)
            <div class="bg-white rounded-xl shadow-sm border p-6" id="produk-desa">
                <h2 class="text-2xl font-bold text-primary border-b pb-2 mb-6"><i
                        class="fa-solid fa-box-open mr-2 text-accent"></i> Produk BUMDesa / Desa</h2>
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
            @endif

            <!-- 5. Produk Ketahanan Pangan -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h2 class="text-2xl font-bold text-primary border-b pb-2 mb-6"><i
                        class="fa-solid fa-wheat-awn mr-2 text-accent"></i> Komoditas Ketahanan Pangan</h2>
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
                                    class="text-xs font-bold text-gray-500 uppercase tracking-tighter mb-1">Komoditas</span>
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
            <div class="bg-white rounded-xl shadow-sm border p-6" id="kinerja-capaian" x-data="kinerjaBumdesData()">
                <h3 class="text-2xl font-bold text-primary border-b pb-2 mb-6"><i class="fa-solid fa-chart-line mr-2 text-accent"></i> Kinerja & Capaian</h3>
                
                

                    <div class="bg-gray-50 rounded-xl border border-gray-200 px-5 py-3 shadow-sm mb-8 flex items-center gap-4 text-gray-900 w-full sm:w-1/3">
                        <label class="font-bold text-sm text-gray-600 whitespace-nowrap">Periode :</label>
                        <select x-model="selectedTahun" class="border border-gray-300 rounded-lg px-4 py-1.5 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-primary w-full bg-white">
                            <template x-for="tahun in getReverseTahunList()" :key="tahun">
                                <option :value="tahun" x-text="tahun"></option>
                            </template>
                        </select>
                    </div>

                    <!-- Hasil Pemeringkatan -->
                    <h4 class="text-md font-bold text-gray-900 mb-4">1. Hasil Pemeringkatan</h4>
                    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 mb-8 max-w-sm flex flex-col relative">
                        <span class="text-xs font-semibold text-gray-400 mb-1 block">Klasifikasi</span>
                        <span class="text-2xl font-black text-primary">{{ $bumdes->pemeringkatan ?? $bumdes->klasifikasi ?? 'Perintis' }}</span>
                        @if ($bumdes->pemeringkatan_tahun)
                            <div class="absolute top-4 right-4 bg-gray-100 text-gray-600 text-xs font-bold px-2 py-1 rounded-md">
                                Tahun {{ $bumdes->pemeringkatan_tahun }}
                            </div>
                        @endif
                    </div>

                    <!-- Kegiatan Reguler -->
                    <h4 class="text-md font-bold text-gray-900 mb-4">2. Kegiatan Reguler</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-2">
                        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-between">
                            <span class="text-xs font-semibold text-gray-400 mb-1 block tracking-wide uppercase">Omset</span>
                            <span class="text-xl font-black text-primary" x-text="'Rp ' + formatRupiah(getCurrentData().reguler.omset)"></span>
                        </div>
                        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-between">
                            <span class="text-xs font-semibold text-gray-400 mb-1 block tracking-wide uppercase">Laba</span>
                            <span class="text-xl font-black text-primary" x-text="'Rp ' + formatRupiah(getCurrentData().reguler.laba)"></span>
                        </div>
                        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-between">
                            <span class="text-xs font-semibold text-gray-400 mb-1 block tracking-wide uppercase">PADes</span>
                            <span class="text-xl font-black text-primary" x-text="'Rp ' + formatRupiah(getCurrentData().reguler.pades)"></span>
                        </div>
                        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-between">
                            <span class="text-xs font-semibold text-gray-400 mb-1 block tracking-wide uppercase">Aset</span>
                            <span class="text-xl font-black text-primary" x-text="'Rp ' + formatRupiah(getCurrentData().reguler.aset)"></span>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 mb-8 italic ml-1">Akumulasi dari laporan dokumen BUMDesa</p>

                    <!-- Kegiatan Ketahanan Pangan -->
                    <h4 class="text-md font-bold text-gray-900 mb-4">3. Kegiatan Ketahanan Pangan</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-2">
                        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-between">
                            <span class="text-xs font-semibold text-gray-400 mb-1 block tracking-wide uppercase">Omset</span>
                            <span class="text-xl font-black text-primary" x-text="'Rp ' + formatRupiah(getCurrentData().ketapang.omset)"></span>
                        </div>
                        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-between">
                            <span class="text-xs font-semibold text-gray-400 mb-1 block tracking-wide uppercase">Laba</span>
                            <span class="text-xl font-black text-primary" x-text="'Rp ' + formatRupiah(getCurrentData().ketapang.laba)"></span>
                        </div>
                        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-between">
                            <span class="text-xs font-semibold text-gray-400 mb-1 block tracking-wide uppercase">PADes</span>
                            <span class="text-xl font-black text-primary" x-text="'Rp ' + formatRupiah(getCurrentData().ketapang.pades)"></span>
                        </div>
                        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-between">
                            <span class="text-xs font-semibold text-gray-400 mb-1 block tracking-wide uppercase">Aset</span>
                            <span class="text-xl font-black text-primary" x-text="'Rp ' + formatRupiah(getCurrentData().ketapang.aset)"></span>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 italic ml-1 mb-4">Akumulasi dari laporan dokumen BUMDesa</p>            </div>

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
            <div class="bg-white rounded-xl shadow-sm border p-6" id="galeri-kegiatan" x-data="{ imgModal: false, imgModalSrc: '' }">
                <!-- Modal -->
                <template x-if="imgModal">
                    <div @click="imgModal = false" class="fixed inset-0 z-[100] flex items-center justify-center bg-black bg-opacity-90 p-4 cursor-zoom-out duration-300 transition-opacity">
                        <img :src="imgModalSrc" class="max-h-full max-w-full rounded-lg shadow-2xl">
                    </div>
                </template>

                <h3 class="text-2xl font-bold text-primary border-b pb-2 mb-6"><i
                        class="fa-solid fa-images mr-2 text-accent"></i> Galeri Kegiatan BUMDesa</h3>
                @php
                    $galeriList = $isPremium ? $bumdes->galeris : $bumdes->galeris->take(1);
                @endphp
                @if ($galeriList->isEmpty())
                    <p class="text-gray-500 italic text-center py-6">Galeri kegiatan belum tersedia.</p>
                @else
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                        @foreach ($galeriList as $galeri)
                            <div class="relative group rounded-xl overflow-hidden shadow-sm hover:shadow-md transition cursor-zoom-in"
                                 @click="imgModalSrc = '{{ asset('storage/' . $galeri->image) }}'; imgModal = true;">
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

            <!-- 10. Papan Pengumuman -->
            <div class="bg-white rounded-xl shadow-sm border p-6" id="papan-pengumuman">
                <h2 class="text-2xl font-bold text-primary mb-6"><i class="fa-solid fa-bullhorn mr-2 text-accent"></i> Papan Pengumuman BUMDesa</h2>
                @if ($bumdes->pengumuman->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($bumdes->pengumuman->sortByDesc('created_at')->take(3) as $umum)
                            <div class="border rounded-lg p-5 bg-gray-50 hover:shadow-md transition">
                                <h4 class="font-bold text-gray-900 text-lg mb-2">{{ $umum->title }}</h4>
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="text-[10px] font-bold text-white bg-accent px-2 py-1 rounded-full uppercase tracking-wider">
                                        @if ($umum->bumdes)
                                            {{ $umum->bumdes->name }}
                                        @elseif($umum->type === 'kabupaten' && $umum->kabupaten)
                                            Portal {{ $umum->kabupaten->name }}
                                        @else
                                            Portal Pusat
                                        @endif
                                    </span>
                                    <span class="text-xs text-gray-400"><i class="fa-regular fa-clock mr-1"></i> {{ $umum->created_at->translatedFormat('d F Y') }}</span>
                                </div>
                                <p class="text-sm text-gray-700 line-clamp-3">{{ Str::limit(strip_tags($umum->content), 150) }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-400 border border-dashed rounded-lg">
                        <i class="fa-solid fa-bullhorn text-3xl mb-2"></i>
                        <p>Belum ada pengumuman dari BUMDesa ini.</p>
                    </div>
                @endif
            </div>

            <!-- 11. Artikel & Opini -->
            <div class="bg-white rounded-xl shadow-sm border p-6" id="artikel-opini">
                <h2 class="text-2xl font-bold text-primary mb-6"><i class="fa-solid fa-pen-nib mr-2 text-accent"></i> Artikel dan Opini BUMDesa</h2>
                @if ($bumdes->artikels->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($bumdes->artikels->sortByDesc('created_at')->take(4) as $art)
                            <div class="border rounded-lg p-4 bg-gray-50 hover:shadow-md transition flex flex-col sm:flex-row gap-4">
                                @if ($art->image)
                                    <div class="w-full sm:w-32 h-32 flex-shrink-0 bg-gray-200 rounded-lg overflow-hidden">
                                        <img src="{{ asset('storage/' . $art->image) }}" class="w-full h-full object-cover">
                                    </div>
                                @endif
                                <div class="flex flex-col">
                                    <h4 class="font-bold text-gray-900 text-lg leading-tight mb-2 line-clamp-2">{{ $art->title }}</h4>
                                    <div class="flex items-center text-xs text-gray-500 mb-2 gap-3">
                                        <span><i class="fa-regular fa-calendar mr-1"></i> {{ $art->created_at->translatedFormat('d F Y') }}</span>
                                        <span><i class="fa-solid fa-user-pen mr-1"></i> {{ $art->author ?? 'Admin' }}</span>
                                    </div>
                                    <p class="text-sm text-gray-700 line-clamp-2 mt-auto">{{ Str::limit(strip_tags($art->content), 100) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-400 border border-dashed rounded-lg">
                        <i class="fa-solid fa-pen-nib text-3xl mb-2"></i>
                        <p>Belum ada artikel dan opini dari BUMDesa ini.</p>
                    </div>
                @endif
            </div>

        </div>
    </div>

@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('kinerjaBumdesData', () => ({
            rawData: @json($kinerjaTahunan ?? []),
            selectedTahun: null,

            init() {
                // Inisialisasi ke tahun pertama dalam data (terbaru), bukan hardcode tahun ini
                if (this.rawData && this.rawData.length > 0) {
                    this.selectedTahun = this.rawData[0].thn;
                } else {
                    this.selectedTahun = String(new Date().getFullYear());
                }
            },

            getReverseTahunList() {
                if (!this.rawData || this.rawData.length === 0) {
                    return [String(new Date().getFullYear())];
                }
                // rawData sudah di-sort descending dari PHP
                return this.rawData.map(v => String(v.thn));
            },

            getCurrentData() {
                let yearData = this.rawData.find(d => String(d.thn) === String(this.selectedTahun));
                const empty = { omset: 0, laba: 0, pades: 0, aset: 0 };
                if (!yearData) {
                    return { reguler: { ...empty }, ketapang: { ...empty } };
                }
                return {
                    reguler:  { 
                        omset: yearData.reguler?.omset  || 0, 
                        laba:  yearData.reguler?.laba   || 0, 
                        pades: yearData.reguler?.pades  || 0, 
                        aset:  yearData.reguler?.aset   || 0 
                    },
                    ketapang: { 
                        omset: yearData.ketapang?.omset || 0, 
                        laba:  yearData.ketapang?.laba  || 0, 
                        pades: yearData.ketapang?.pades || 0, 
                        aset:  yearData.ketapang?.aset  || 0 
                    }
                };
            },

            formatRupiah(value) {
                if (!value || value === 0) return '0';
                const num = parseFloat(value);
                if (num >= 1000000000) return (num / 1000000000).toLocaleString('id-ID', {minimumFractionDigits: 1, maximumFractionDigits: 1}) + ' M';
                if (num >= 1000000) return (num / 1000000).toLocaleString('id-ID', {minimumFractionDigits: 1, maximumFractionDigits: 1}) + ' Jt';
                return num.toLocaleString('id-ID');
            }
        }))
    });
</script>
@endpush
