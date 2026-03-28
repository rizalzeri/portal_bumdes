@extends('layouts.admin')
@section('title', 'Detail dan Analisa Data')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Detail dan Analisa Data BUMDesa</h2>
        <p class="text-gray-500 text-sm mt-1">Laporan analitik, filtering, dan pencarian data agregat BUMDesa.</p>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
    <h3 class="font-bold text-lg mb-4 text-gray-800 border-b pb-2"><i class="fa-solid fa-filter text-primary mr-2"></i> Kategori Filter Data BUMDesa</h3>
    
    <div class="flex flex-wrap gap-2 mb-4">
        <!-- 1, 2 -->
        <a href="{{ route('adminkab.analisa_data.index', ['tab' => 'aktif']) }}" class="px-4 py-2 text-sm font-semibold rounded-lg border {{ $tab == 'aktif' ? 'bg-primary text-white border-primary' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-gray-100' }}">BUMDesa Aktif</a>
        <a href="{{ route('adminkab.analisa_data.index', ['tab' => 'tidak_aktif']) }}" class="px-4 py-2 text-sm font-semibold rounded-lg border {{ $tab == 'tidak_aktif' ? 'bg-primary text-white border-primary' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-gray-100' }}">BUMDesa Tidak Aktif</a>
        
        <!-- 3, 4 -->
        <a href="{{ route('adminkab.analisa_data.index', ['tab' => 'berbadan_hukum']) }}" class="px-4 py-2 text-sm font-semibold rounded-lg border {{ $tab == 'berbadan_hukum' ? 'bg-primary text-white border-primary' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-gray-100' }}">Berbadan Hukum</a>
        <a href="{{ route('adminkab.analisa_data.index', ['tab' => 'belum_berbadan_hukum']) }}" class="px-4 py-2 text-sm font-semibold rounded-lg border {{ $tab == 'belum_berbadan_hukum' ? 'bg-primary text-white border-primary' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-gray-100' }}">Belum Berbadan Hukum</a>
        
        <!-- 7 -->
        <a href="{{ route('adminkab.analisa_data.index', ['tab' => 'pemeringkatan']) }}" class="px-4 py-2 text-sm font-semibold rounded-lg border {{ $tab == 'pemeringkatan' ? 'bg-primary text-white border-primary' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-gray-100' }}">Berdasarkan Pemeringkatan</a>

        <!-- 8, 9 -->
        <a href="{{ route('adminkab.analisa_data.index', ['tab' => 'omset_regular']) }}" class="px-4 py-2 text-sm font-semibold rounded-lg border {{ $tab == 'omset_regular' ? 'bg-primary text-white border-primary' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-gray-100' }}">Omset & Laba (Reguler)</a>
        <a href="{{ route('adminkab.analisa_data.index', ['tab' => 'omset_ketapang']) }}" class="px-4 py-2 text-sm font-semibold rounded-lg border {{ $tab == 'omset_ketapang' ? 'bg-primary text-white border-primary' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-gray-100' }}">Omset & Laba (Ketahanan Pangan)</a>
        
        <!-- 10 -->
        <a href="{{ route('adminkab.analisa_data.index', ['tab' => 'mitra']) }}" class="px-4 py-2 text-sm font-semibold rounded-lg border {{ $tab == 'mitra' ? 'bg-primary text-white border-primary' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-gray-100' }}">Berdasarkan Mitra Kerjasama</a>
    </div>

    <!-- Dropdown Filters (5, 6, 7) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
        <!-- 5 -->
        <form action="{{ route('adminkab.analisa_data.index') }}" method="GET" class="flex flex-col">
            <input type="hidden" name="tab" value="kategori_usaha">
            <label class="text-xs font-bold text-gray-600 uppercase mb-1">Filter Kategori Unit Usaha</label>
            <div class="flex">
                <select name="filter_val" class="flex-1 border-gray-300 rounded-l-md text-sm p-2 bg-white" onchange="this.form.submit()">
                    <option value="">-- Semua Kategori --</option>
                    @foreach($kategoriList as $k)
                        <option value="{{ $k->id }}" {{ request('filter_val') == $k->id && $tab == 'kategori_usaha' ? 'selected' : '' }}>{{ $k->name }}</option>
                    @endforeach
                </select>
                @if(request('filter_val') && $tab == 'kategori_usaha')
                    <a href="{{ route('adminkab.analisa_data.index', ['tab' => 'kategori_usaha']) }}" class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-r-md text-sm transition tooltip" title="Reset Filter"><i class="fa-solid fa-xmark"></i></a>
                @else
                    <button type="submit" class="bg-primary hover:bg-primary-900 border-primary text-white px-3 py-2 rounded-r-md text-sm transition"><i class="fa-solid fa-search"></i></button>
                @endif
            </div>
        </form>

        <!-- 6 -->
        <form action="{{ route('adminkab.analisa_data.index') }}" method="GET" class="flex flex-col">
            <input type="hidden" name="tab" value="komoditas_pangan">
            <label class="text-xs font-bold text-gray-600 uppercase mb-1">Filter Komoditas Pangan</label>
            <div class="flex">
                <select name="filter_val" class="flex-1 border-gray-300 rounded-l-md text-sm p-2 bg-white" onchange="this.form.submit()">
                    <option value="">-- Semua Komoditas --</option>
                    @foreach($komoditasList as $c)
                        <option value="{{ $c->id }}" {{ request('filter_val') == $c->id && $tab == 'komoditas_pangan' ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
                @if(request('filter_val') && $tab == 'komoditas_pangan')
                    <a href="{{ route('adminkab.analisa_data.index', ['tab' => 'komoditas_pangan']) }}" class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-r-md text-sm transition tooltip" title="Reset Filter"><i class="fa-solid fa-xmark"></i></a>
                @else
                    <button type="submit" class="bg-primary hover:bg-primary-900 border-primary text-white px-3 py-2 rounded-r-md text-sm transition"><i class="fa-solid fa-search"></i></button>
                @endif
            </div>
        </form>

        <form action="{{ route('adminkab.analisa_data.index') }}" method="GET" class="flex flex-col">
            <input type="hidden" name="tab" value="pemeringkatan">
            <label class="text-xs font-bold text-gray-600 uppercase mb-1">Cari Klasifikasi Tertentu</label>
            <div class="flex">
                <select name="filter_val" class="flex-1 border-gray-300 rounded-l-md text-sm p-2 bg-white" onchange="this.form.submit()">
                    <option value="">-- Semua Pemeringkatan --</option>
                    <option value="Dasar" {{ request('filter_val') == 'Dasar' && $tab == 'pemeringkatan' ? 'selected' : '' }}>Dasar</option>
                    <option value="Berkembang" {{ request('filter_val') == 'Berkembang' && $tab == 'pemeringkatan' ? 'selected' : '' }}>Berkembang</option>
                    <option value="Maju" {{ request('filter_val') == 'Maju' && $tab == 'pemeringkatan' ? 'selected' : '' }}>Maju</option>
                </select>
                @if(request('filter_val') && $tab == 'pemeringkatan')
                    <a href="{{ route('adminkab.analisa_data.index', ['tab' => 'pemeringkatan']) }}" class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-r-md text-sm transition tooltip" title="Reset Filter"><i class="fa-solid fa-xmark"></i></a>
                @else
                    <button type="submit" class="bg-primary hover:bg-primary-900 border-primary text-white px-3 py-2 rounded-r-md text-sm transition"><i class="fa-solid fa-search"></i></button>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border p-6">
    <div class="mb-4">
        <h3 class="font-bold text-lg text-gray-800">Menampilkan Hasil: <span class="text-primary">{{ ucwords(str_replace('_', ' ', $tab)) }}</span></h3>
        <p class="text-sm text-gray-500">Total data ditemukan: <span class="font-bold">{{ $bumdes->total() }}</span> entri</p>
    </div>

    <div class="table-responsive w-full overflow-x-auto">
        <table class="w-full whitespace-nowrap text-sm text-left text-gray-500 border">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-3 border-r">Nama BUMDesa</th>
                    <th class="px-6 py-3 border-r">Desa & Kecamatan</th>
                    <th class="px-6 py-3 border-r">Status</th>
                    @if(in_array($tab, ['omset_regular', 'omset_ketapang']))
                        <th class="px-6 py-3 border-r">Total Omset Data</th>
                        <th class="px-6 py-3 border-r">Total Laba/Rugi</th>
                    @endif
                    <th class="px-6 py-3 text-center">Profil</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bumdes as $b)
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="px-6 py-4 border-r">
                        <div class="font-bold text-gray-900">{{ $b->name }}</div>
                        <div class="text-xs text-gray-500">Sertifikat: {{ $b->nomor_sertifikat ?? 'Belum ada' }}</div>
                    </td>
                    <td class="px-6 py-4 border-r">
                        <div class="font-medium">{{ $b->desa }}</div>
                        <div class="text-xs text-gray-500">{{ $b->kecamatan }}</div>
                    </td>
                    <td class="px-6 py-4 border-r">
                        @if ($b->is_active || $b->status === 'active')
                            <span class="text-green-600 font-bold"><i class="fa-solid fa-check-circle mr-1"></i> Aktif</span>
                        @else
                            <span class="text-red-500 font-bold"><i class="fa-solid fa-times-circle mr-1"></i> Inaktif</span>
                        @endif
                        @if($b->klasifikasi)
                            <span class="ml-2 bg-blue-100 text-blue-800 text-[10px] px-2 py-0.5 rounded">{{ $b->klasifikasi }}</span>
                        @endif
                    </td>
                    @if(in_array($tab, ['omset_regular', 'omset_ketapang']))
                        <td class="px-6 py-4 border-r font-mono text-green-600 font-bold">
                            Rp {{ number_format($b->laporan_keuangan_sum_pendapatan ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 border-r font-mono font-bold {{ ($b->laporan_keuangan_sum_laba_rugi >= 0) ? 'text-blue-600' : 'text-red-600' }}">
                            Rp {{ number_format($b->laporan_keuangan_sum_laba_rugi ?? 0, 0, ',', '.') }}
                        </td>
                    @endif
                    <td class="px-6 py-4 text-center">
                        <a href="{{ route('public.bumdes.profile', ['slug' => $b->slug]) }}" target="_blank" class="bg-gray-100 hover:bg-gray-200 text-gray-700 p-2 rounded-md tooltip text-xs font-semibold" title="Lihat Profil BUMDes">
                            <i class="fa-solid fa-external-link-alt mr-1"></i> Buka
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        <i class="fa-solid fa-folder-open text-4xl text-gray-300 mb-2 block"></i>
                        Tidak ada data BUMDesa yang sesuai dengan filter ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $bumdes->links() }}
    </div>
</div>
@endsection
