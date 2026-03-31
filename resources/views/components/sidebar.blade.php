<!-- Sidebar -->
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="absolute z-20 flex flex-col w-64 h-screen px-4 py-8 overflow-y-auto bg-primary border-r rtl:border-r-0 rtl:border-l border-primary-900 transition-transform duration-300 ease-in-out md:static md:translate-x-0">
    <div class="flex items-center justify-between md:justify-center">
        <a href="#" class="flex items-center gap-2">
            <i class="fa-solid fa-leaf text-accent text-3xl"></i>
            <span class="text-xl font-bold text-white tracking-wide">BUMDespro Member</span>
        </a>
        <button @click="sidebarOpen = false" class="md:hidden text-gray-300 hover:text-white focus:outline-none">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
    </div>

    <div class="flex flex-col justify-between flex-1 mt-6">
        <nav class="-mx-3 space-y-3 p-2">

            <!-- Dashboard link (common for all) -->
            <a href="{{ Auth::user()->role === 'superadmin' ? route('superadmin.dashboard') : (Auth::user()->role === 'admin_kabupaten' ? route('adminkab.dashboard') : route('user.dashboard', ['slug' => Auth::user()->username])) }}"
                class="flex items-center px-3 py-2 text-gray-200 transition-colors rounded-lg hover:bg-primary-800 hover:text-white">
                <i class="fa-solid fa-gauge w-5 h-5"></i>
                <span class="mx-2 text-sm font-medium">Dashboard</span>
            </a>

            @if (Auth::user()->role === 'superadmin')
                <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mt-4 mb-2 px-3">Master Data
                </div>
                <a href="{{ route('superadmin.datamaster.index') }}"
                    class="flex items-center px-3 py-2 text-gray-200 transition-colors rounded-lg hover:bg-primary-800 hover:text-white">
                    <i class="fa-solid fa-database w-5 h-5"></i>
                    <span class="mx-2 text-sm font-medium">Atur Data Master</span>
                </a>
                <a href="{{ route('superadmin.kabupaten.index') }}"
                    class="flex items-center px-3 py-2 text-gray-200 transition-colors rounded-lg hover:bg-primary-800 hover:text-white">
                    <i class="fa-solid fa-map-location-dot w-5 h-5"></i>
                    <span class="mx-2 text-sm font-medium">Kabupaten</span>
                </a>
                <a href="{{ route('superadmin.user.index') }}"
                    class="flex items-center px-3 py-2 text-gray-200 transition-colors rounded-lg hover:bg-primary-800 hover:text-white">
                    <i class="fa-solid fa-users w-5 h-5"></i>
                    <span class="mx-2 text-sm font-medium">Pengguna</span>
                </a>
                <a href="{{ route('superadmin.langganan.index') }}"
                    class="flex items-center px-3 py-2 text-gray-200 transition-colors rounded-lg hover:bg-primary-800 hover:text-white">
                    <i class="fa-solid fa-money-check-dollar w-5 h-5"></i>
                    <span class="mx-2 text-sm font-medium">Atur Langganan</span>
                </a>
                <a href="{{ route('superadmin.pricing-config.index') }}"
                    class="flex items-center px-3 py-2 text-gray-200 transition-colors rounded-lg hover:bg-primary-800 hover:text-white">
                    <i class="fa-solid fa-tags w-5 h-5"></i>
                    <span class="mx-2 text-sm font-medium">Paket Harga</span>
                </a>

                <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mt-4 mb-2 px-3">Konten Portal
                </div>
                <a href="{{ route('superadmin.materi.index') }}"
                    class="flex items-center px-3 py-2 text-gray-200 transition-colors rounded-lg hover:bg-primary-800 hover:text-white">
                    <i class="fa-solid fa-file-pdf w-5 h-5"></i>
                    <span class="mx-2 text-sm font-medium">Materi & Template</span>
                </a>
                <a href="{{ route('superadmin.artikel.index') }}"
                    class="flex items-center px-3 py-2 text-gray-200 transition-colors rounded-lg hover:bg-primary-800 hover:text-white">
                    <i class="fa-solid fa-newspaper w-5 h-5"></i>
                    <span class="mx-2 text-sm font-medium">Artikel & Opini</span>
                </a>
                <a href="{{ route('superadmin.pengumuman.index') }}"
                    class="flex items-center px-3 py-2 text-gray-200 transition-colors rounded-lg hover:bg-primary-800 hover:text-white">
                    <i class="fa-solid fa-bullhorn w-5 h-5"></i>
                    <span class="mx-2 text-sm font-medium">Pengumuman</span>
                </a>
                <a href="{{ route('superadmin.infografis.index') }}"
                    class="flex items-center px-3 py-2 text-gray-200 transition-colors rounded-lg hover:bg-primary-800 hover:text-white">
                    <i class="fa-solid fa-chart-pie w-5 h-5"></i>
                    <span class="mx-2 text-sm font-medium">Infografis</span>
                </a>
                <a href="{{ route('superadmin.katalog.index') }}"
                    class="flex items-center px-3 py-2 text-gray-200 transition-colors rounded-lg hover:bg-primary-800 hover:text-white">
                    <i class="fa-solid fa-shop w-5 h-5"></i>
                    <span class="mx-2 text-sm font-medium">Produk Desa</span>
                </a>
                <a href="{{ route('superadmin.mitra.index') }}"
                    class="flex items-center px-3 py-2 text-gray-200 transition-colors rounded-lg hover:bg-primary-800 hover:text-white">
                    <i class="fa-solid fa-handshake w-5 h-5"></i>
                    <span class="mx-2 text-sm font-medium">Mitra Kerjasama</span>
                </a>
                <a href="{{ route('superadmin.galeri.index') }}"
                    class="flex items-center px-3 py-2 text-gray-200 transition-colors rounded-lg hover:bg-primary-800 hover:text-white">
                    <i class="fa-solid fa-images w-5 h-5"></i>
                    <span class="mx-2 text-sm font-medium">Galeri Kegiatan</span>
                </a>
                
                <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mt-4 mb-2 px-3">Sistem</div>
                <a href="{{ route('superadmin.premium-features.index') }}"
                    class="flex items-center px-3 py-2 text-gray-200 transition-colors rounded-lg hover:bg-primary-800 hover:text-white">
                    <i class="fa-solid fa-star w-5 h-5 text-yellow-400"></i>
                    <span class="mx-2 text-sm font-medium">Fitur Premium</span>
                </a>
            @endif

            @if (Auth::user()->role === 'admin_kabupaten')
            @php
                $isKabPremium = \App\Models\Langganan::where('kabupaten_id', Auth::user()->kabupaten_id)
                    ->whereNull('bumdes_id')
                    ->where('status', 'active')
                    ->where('end_date', '>', now())
                    ->exists();
            @endphp
                <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mt-4 mb-2 px-3">Menu Kabupaten
                </div>
                <a href="{{ route('adminkab.bumdes.index') }}"
                    class="flex items-center px-3 py-2 text-gray-200 transition-colors rounded-lg hover:bg-primary-800 hover:text-white">
                    <i class="fa-solid fa-house-flag w-5 h-5"></i>
                    <span class="mx-2 text-sm font-medium">Daftar Akun BUMDesa</span>
                </a>
                
                @if($isKabPremium)
                    <a href="{{ route('adminkab.kecamatan.index') }}"
                        class="flex items-center px-3 py-2 text-gray-200 transition-colors rounded-lg hover:bg-primary-800 hover:text-white">
                        <i class="fa-solid fa-map-location-dot w-5 h-5"></i>
                        <span class="mx-2 text-sm font-medium">Daftarkan Kecamatan</span>
                    </a>
                    <a href="{{ route('adminkab.analisa_data.index') }}"
                        class="flex items-center px-3 py-2 text-gray-200 transition-colors rounded-lg hover:bg-primary-800 hover:text-white">
                        <i class="fa-solid fa-chart-line w-5 h-5"></i>
                        <span class="mx-2 text-sm font-medium">Detail dan Analisa Data</span>
                    </a>
                    <a href="{{ route('adminkab.monitoring.index') }}"
                        class="flex items-center px-3 py-2 text-gray-200 transition-colors rounded-lg hover:bg-primary-800 hover:text-white">
                        <i class="fa-solid fa-magnifying-glass-chart w-5 h-5"></i>
                        <span class="mx-2 text-sm font-medium">Monitoring BUMDesa</span>
                    </a>
                    <a href="{{ route('adminkab.pengumuman.index') }}"
                        class="flex items-center px-3 py-2 text-gray-200 transition-colors rounded-lg hover:bg-primary-800 hover:text-white">
                        <i class="fa-solid fa-bullhorn w-5 h-5"></i>
                        <span class="mx-2 text-sm font-medium">Pengumuman Portal</span>
                    </a>
                @endif
                <a href="{{ route('adminkab.langganan.index') }}"
                    class="flex items-center px-3 py-2 text-gray-200 transition-colors rounded-lg hover:bg-primary-800 hover:text-white">
                    <i class="fa-solid fa-crown w-5 h-5 text-accent"></i>
                    <span class="mx-2 text-sm font-medium">Langganan</span>
                </a>
            @endif

            @if (Auth::user()->role === 'user')
                <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mt-4 mb-2 px-3">Data Profil &
                    Usaha</div>
    @premium('profil')
    <a href="{{ route('user.profil.index', ['slug' => Auth::user()->username]) }}"
        class="flex items-center px-3 py-2 text-gray-200 transition-colors rounded-lg hover:bg-primary-800 hover:text-white">
        <i class="fa-solid fa-id-card w-5 h-5"></i>
        <span class="mx-2 text-sm font-medium">Profil BUMDes</span>
    </a>
    @endpremium
    @premium('personalia')
    <a href="{{ route('user.personalia.index', ['slug' => Auth::user()->username]) }}"
        class="flex items-center px-3 py-2 text-gray-200 transition-colors rounded-lg hover:bg-primary-800 hover:text-white">
        <i class="fa-solid fa-user-tie w-5 h-5"></i>
        <span class="mx-2 text-sm font-medium">Personil / Struktur</span>
    </a>
    @endpremium
    @premium('unit_usaha')
    <a href="{{ route('user.unit_usaha.index', ['slug' => Auth::user()->username]) }}"
        class="flex items-center px-3 py-2 text-gray-200 transition-colors rounded-lg hover:bg-primary-800 hover:text-white">
        <i class="fa-solid fa-shop w-5 h-5"></i>
        <span class="mx-2 text-sm font-medium">Unit Usaha</span>
    </a>
    @endpremium
    @premium('produk')
    <a href="{{ route('user.produk.index', ['slug' => Auth::user()->username]) }}"
        class="flex items-center px-3 py-2 text-gray-200 transition-colors rounded-lg hover:bg-primary-800 hover:text-white">
        <i class="fa-solid fa-box w-5 h-5"></i>
        <span class="mx-2 text-sm font-medium">Produk Desa</span>
    </a>
    @endpremium
    @premium('ketapang')
    <a href="{{ route('user.ketapang.index', ['slug' => Auth::user()->username]) }}"
        class="flex items-center px-3 py-2 text-gray-200 transition-colors rounded-lg hover:bg-primary-800 hover:text-white">
        <i class="fa-solid fa-wheat-awn w-5 h-5"></i>
        <span class="mx-2 text-sm font-medium">Ketahanan Pangan</span>
    </a>
    @endpremium

                <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mt-4 mb-2 px-3">Laporan &
                    Publikasi</div>
    @premium('transparansi')
    <a href="{{ route('user.transparansi.index', ['slug' => Auth::user()->username]) }}"
        class="flex items-center px-3 py-2 text-gray-200 transition-colors rounded-lg hover:bg-primary-800 hover:text-white">
        <i class="fa-solid fa-magnifying-glass-chart w-5 h-5"></i>
        <span class="mx-2 text-sm font-medium">Transparansi</span>
    </a>
    @endpremium
    @premium('kinerja')
    <a href="{{ route('user.kinerja.index', ['slug' => Auth::user()->username]) }}"
        class="flex items-center px-3 py-2 text-gray-200 transition-colors rounded-lg hover:bg-primary-800 hover:text-white">
        <i class="fa-solid fa-arrow-trend-up w-5 h-5"></i>
        <span class="mx-2 text-sm font-medium">Kinerja & Capaian</span>
    </a>
    @endpremium
    @premium('mitra')
    <a href="{{ route('user.mitra.index', ['slug' => Auth::user()->username]) }}"
        class="flex items-center px-3 py-2 text-gray-200 transition-colors rounded-lg hover:bg-primary-800 hover:text-white">
        <i class="fa-solid fa-handshake w-5 h-5"></i>
        <span class="mx-2 text-sm font-medium">Mitra Kerjasama</span>
    </a>
    @endpremium
    @premium('artikel')
    <a href="{{ route('user.artikel.index', ['slug' => Auth::user()->username]) }}"
        class="flex items-center px-3 py-2 text-gray-200 transition-colors rounded-lg hover:bg-primary-800 hover:text-white">
        <i class="fa-solid fa-newspaper w-5 h-5"></i>
        <span class="mx-2 text-sm font-medium">Artikel & Opini</span>
    </a>
    @endpremium
    @premium('galeri')
    <a href="{{ route('user.galeri.index', ['slug' => Auth::user()->username]) }}"
        class="flex items-center px-3 py-2 text-gray-200 transition-colors rounded-lg hover:bg-primary-800 hover:text-white">
        <i class="fa-solid fa-images w-5 h-5"></i>
        <span class="mx-2 text-sm font-medium">Galeri Kegiatan</span>
    </a>
    @endpremium
    @premium('pengumuman')
    <a href="{{ route('user.pengumuman.index', ['slug' => Auth::user()->username]) }}"
        class="flex items-center px-3 py-2 text-gray-200 transition-colors rounded-lg hover:bg-primary-800 hover:text-white">
        <i class="fa-solid fa-bullhorn w-5 h-5"></i>
        <span class="mx-2 text-sm font-medium">Pengumuman dari BUMDesa</span>
    </a>
    @endpremium

    <div class="mt-4 px-3">
        @php
            $activeSub = \App\Models\Langganan::where('bumdes_id', Auth::user()->bumdes_id)
                ->where('status', 'active')
                ->where('end_date', '>', now())
                ->first();
        @endphp
        <a href="{{ route('user.langganan.index', ['slug' => Auth::user()->username]) }}"
            class="flex items-center justify-center px-4 py-2 {{ $activeSub ? 'bg-gradient-to-r from-emerald-500 to-teal-600' : 'bg-gradient-to-r from-amber-500 to-orange-600' }} text-white font-bold rounded-lg hover:opacity-90 w-full transition shadow-md whitespace-nowrap overflow-hidden">
            <i class="fa-solid fa-crown mr-2 shrink-0"></i> 
            <span class="text-xs truncate">
                {{ $activeSub ? ($activeSub->days_remaining . ' Hari Tersisa') : 'Langganan Premium' }}
            </span>
        </a>
    </div>
            @endif

        </nav>
    </div>
</aside>
