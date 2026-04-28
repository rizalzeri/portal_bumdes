@extends('layouts.admin')
@section('title', 'Detail dan Analisa Data')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Detail dan Analisa Data BUMDesa</h2>
        <p class="text-gray-500 text-sm mt-1">Laporan analitik, filtering, dan pencarian data agregat BUMDesa.</p>
    </div>
</div>

<!-- Comprehensive Filter Form -->
<div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
    <h3 class="font-bold text-lg mb-4 text-gray-800 border-b pb-2"><i class="fa-solid fa-filter text-primary mr-2"></i> Filter Data BUMDesa</h3>
    
    <form action="{{ route('adminkab.analisa_data.index') }}" method="GET">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
            <!-- 1 Tingkat -->
            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Kecamatan</label>
                <select name="kecamatan" class="w-full border-gray-300 rounded-md text-sm p-2 bg-gray-50">
                    <option value="semua" {{ request('kecamatan') == 'semua' ? 'selected' : '' }}>Semua Kecamatan</option>
                    @foreach($kecamatanList as $kec)
                        <option value="{{ $kec->name }}" {{ request('kecamatan') == $kec->name ? 'selected' : '' }}>{{ $kec->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Keaktifan BUMDesa</label>
                <select name="status" class="w-full border-gray-300 rounded-md text-sm p-2 bg-gray-50">
                    <option value="semua" {{ request('status') == 'semua' ? 'selected' : '' }}>Semua</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="tidak_aktif" {{ request('status') == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>
            
            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Berbadan Hukum</label>
                <select name="badan_hukum" class="w-full border-gray-300 rounded-md text-sm p-2 bg-gray-50">
                    <option value="semua" {{ request('badan_hukum') == 'semua' ? 'selected' : '' }}>Semua</option>
                    <option value="berbadan_hukum" {{ request('badan_hukum') == 'berbadan_hukum' ? 'selected' : '' }}>Berbadan Hukum</option>
                    <option value="proses" {{ request('badan_hukum') == 'proses' ? 'selected' : '' }}>Proses</option>
                    <option value="belum" {{ request('badan_hukum') == 'belum' ? 'selected' : '' }}>Belum Mendaftar</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Unit Usaha (Master Data)</label>
                <select name="kategori_usaha" class="w-full border-gray-300 rounded-md text-sm p-2 bg-gray-50">
                    <option value="semua" {{ request('kategori_usaha') == 'semua' ? 'selected' : '' }}>Semua Kategori</option>
                    @foreach($kategoriList as $k)
                        <option value="{{ $k->id }}" {{ request('kategori_usaha') == $k->id ? 'selected' : '' }}>{{ $k->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Komoditas Pangan (Master Data)</label>
                <select name="komoditas" class="w-full border-gray-300 rounded-md text-sm p-2 bg-gray-50">
                    <option value="semua" {{ request('komoditas') == 'semua' ? 'selected' : '' }}>Semua Komoditas</option>
                    @foreach($komoditasList as $k)
                        <option value="{{ $k->id }}" {{ request('komoditas') == $k->id ? 'selected' : '' }}>{{ $k->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Mitra Kerjasama</label>
                <select name="mitra" class="w-full border-gray-300 rounded-md text-sm p-2 bg-gray-50">
                    <option value="semua" {{ request('mitra') == 'semua' ? 'selected' : '' }}>Semua Mitra</option>
                    @foreach($mitraList as $m)
                        <option value="{{ $m->id }}" {{ request('mitra') == $m->id ? 'selected' : '' }}>{{ $m->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2 lg:col-span-3 border-t my-2 pt-4">
                <h4 class="font-bold text-sm text-gray-800 mb-4 uppercase tracking-wider">Filter / Sortir Bertingkat</h4>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Data Tahun Laporan</label>
                        <select name="tahun" class="w-full border-gray-300 rounded-md text-sm p-2 bg-gray-50">
                            @foreach($tahunList as $t)
                                <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>Tahun {{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Klasifikasi Pemeringkatan</label>
                        <select name="pemeringkatan" class="w-full border-gray-300 rounded-md text-sm p-2 bg-gray-50">
                            <option value="semua" {{ request('pemeringkatan') == 'semua' ? 'selected' : '' }}>Semua Klasifikasi</option>
                            <option value="Perintis" {{ request('pemeringkatan') == 'Perintis' ? 'selected' : '' }}>Perintis</option>
                            <option value="Pemula" {{ request('pemeringkatan') == 'Pemula' ? 'selected' : '' }}>Pemula</option>
                            <option value="Berkembang" {{ request('pemeringkatan') == 'Berkembang' ? 'selected' : '' }}>Berkembang</option>
                            <option value="Maju" {{ request('pemeringkatan') == 'Maju' ? 'selected' : '' }}>Maju</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Kategori Laporan</label>
                        <select name="kategori_keuangan" class="w-full border-gray-300 rounded-md text-sm p-2 bg-gray-50">
                            <option value="semua" {{ request('kategori_keuangan') == 'semua' ? 'selected' : '' }}>Semua Kategori</option>
                            <option value="reguler" {{ request('kategori_keuangan') == 'reguler' ? 'selected' : '' }}>Reguler</option>
                            <option value="ketahanan_pangan" {{ request('kategori_keuangan') == 'ketahanan_pangan' ? 'selected' : '' }}>Ketahanan Pangan</option>
                            <option value="dbm" {{ request('kategori_keuangan') == 'dbm' ? 'selected' : '' }}>DBM / Dana Bergulir</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Jenis Laporan (Sort)</label>
                        <select name="jenis_keuangan" class="w-full border-gray-300 rounded-md text-sm p-2 bg-gray-50">
                            <option value="semua" {{ request('jenis_keuangan') == 'semua' ? 'selected' : '' }}>Semua Jenis (Sub-total)</option>
                            <option value="omset" {{ request('jenis_keuangan') == 'omset' ? 'selected' : '' }}>Tertinggi by Omset</option>
                            <option value="laba" {{ request('jenis_keuangan') == 'laba' ? 'selected' : '' }}>Tertinggi by Laba</option>
                            <option value="pades" {{ request('jenis_keuangan') == 'pades' ? 'selected' : '' }}>Tertinggi by PADes</option>
                            <option value="aset" {{ request('jenis_keuangan') == 'aset' ? 'selected' : '' }}>Tertinggi by Aset</option>
                            <option value="dansos" {{ request('jenis_keuangan') == 'dansos' ? 'selected' : '' }}>Tertinggi by Dana Sosial</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="flex justify-end gap-3 mt-4 pt-4 border-t border-gray-100">
            <a href="{{ route('adminkab.analisa_data.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-200 transition">Reset Filter</a>
            <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg text-sm font-bold hover:bg-primary-900 transition"><i class="fa-solid fa-search mr-2"></i> Terapkan Filter</button>
        </div>
    </form>
</div>

<div class="bg-white rounded-xl shadow-sm border p-6">
    <div class="mb-4">
        <h3 class="font-bold text-lg text-gray-800">Hasil Analisa Data</h3>
        <p class="text-sm text-gray-500">Total BUMDesa ditemukan: <span class="font-bold">{{ $bumdes->total() }}</span> entri</p>
    </div>

    <div class="table-responsive w-full overflow-x-auto">
        <table class="w-full whitespace-nowrap text-sm text-left text-gray-500 border">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-3 border-r">Nama BUMDesa</th>
                    <th class="px-6 py-3 border-r">Desa & Kecamatan</th>
                    <th class="px-6 py-3 border-r">Status</th>
                    @if($kategori_keuangan != 'semua' || $jenis_keuangan != 'semua')
                        <th class="px-6 py-3 border-r">NILAI CAPAIAN (TERFILTER)</th>
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
                    @if($kategori_keuangan != 'semua' || $jenis_keuangan != 'semua')
                        <td class="px-6 py-4 border-r font-mono text-green-600 font-bold">
                            Rp {{ number_format($b->sort_value ?? 0, 0, ',', '.') }}
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
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
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
