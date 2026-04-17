@extends('layouts.public')
@section('title', 'Infografis Kabupaten ' . $kabupaten->name)

@section('content')
<div class="bg-gray-50 min-h-screen pt-12 text-gray-900" x-data="infografisData()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">

        <!-- 1. Profil BUMDesa -->
        <section>
            <h2 class="text-xl font-extrabold text-gray-900 mb-6">Profil BUMDesa</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <!-- Jumlah BUMDesa -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-between">
                    <span class="text-xs font-semibold text-gray-400 mb-1 block">Jumlah BUMDesa</span>
                    <span class="text-3xl font-black text-teal-700">{{ number_format($total_bumdes) }}</span>
                </div>
                <!-- Aktif -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-between">
                    <span class="text-xs font-semibold text-gray-400 mb-1 block">Aktif</span>
                    <span class="text-3xl font-black text-teal-700">{{ number_format($active_bumdes) }}</span>
                </div>
                <!-- Tidak Aktif -->
                <div class="bg-red-600 p-6 rounded-xl shadow-sm flex flex-col justify-between text-white border border-red-700">
                    <span class="text-xs font-semibold mb-1 block text-red-100">Tidak Aktif</span>
                    <span class="text-3xl font-black">{{ number_format($inactive_bumdes) }}</span>
                </div>
                <!-- Berbadan Hukum -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-between">
                    <span class="text-xs font-semibold text-gray-400 mb-1 block">Berbadan Hukum</span>
                    <span class="text-3xl font-black text-teal-700">{{ number_format($berbadan_hukum) }}</span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Unit Usaha BUMDesa -->
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Unit Usaha BUMDesa</h3>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-teal-700 text-white border-b border-teal-800">
                                    <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider">Jenis Usaha</th>
                                    <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider text-right">Jumlah BUMDesa</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($units_by_category as $unit)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-3 text-sm text-gray-700 font-medium">{{ $unit->name }}</td>
                                    <td class="px-6 py-3 text-sm text-gray-900 text-right font-medium">{{ number_format($unit->total) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="px-6 py-8 text-center text-sm text-gray-400 italic">Data unit usaha belum tersedia.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Ketahanan Pangan -->
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Ketahanan Pangan</h3>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-teal-700 text-white border-b border-teal-800">
                                    <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider">Komoditas</th>
                                    <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider text-right">Jumlah BUMDesa</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($ketapang_by_category as $ket)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-3 text-sm text-gray-700 font-medium">{{ $ket->name }}</td>
                                    <td class="px-6 py-3 text-sm text-gray-900 text-right font-medium">{{ number_format($ket->total) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="px-6 py-8 text-center text-sm text-gray-400 italic">Data ketahanan pangan belum tersedia.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

        <!-- 2. Kinerja BUMDesa -->
        <section>
            <h2 class="text-xl font-extrabold text-gray-900 mb-6">Kinerja BUMDesa</h2>
            
            <div class="bg-white rounded-xl border border-gray-200 px-4 py-3 shadow-sm mb-8 flex flex-col md:flex-row items-start md:items-center gap-4 text-gray-900 w-full lg:w-max">
                <div class="flex items-center gap-2 w-full md:w-auto">
                    <label class="font-bold text-sm text-gray-600 whitespace-nowrap">Periode</label>
                    <select x-model="selectedTahun" class="border border-gray-300 rounded-lg px-4 py-1.5 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-teal-500 w-full md:w-28 bg-gray-50">
                        <template x-for="tahun in getReverseTahunList()" :key="tahun">
                            <option :value="tahun" x-text="tahun"></option>
                        </template>
                    </select>
                </div>
                <div class="flex items-center gap-2 w-full md:w-auto">
                    <label class="font-bold text-sm text-gray-600 whitespace-nowrap">Kategori</label>
                    <select x-model="selectedKategori" class="border border-gray-300 rounded-lg px-4 py-1.5 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-teal-500 w-full md:w-44 bg-gray-50">
                        <option value="semua">Semua Kategori</option>
                        <option value="reguler">Reguler</option>
                        <option value="ketapang">Ketahanan Pangan</option>
                        <option value="dbm">Dana Bergulir (DBM)</option>
                    </select>
                </div>
                <div class="flex items-center gap-2 w-full md:w-auto">
                    <label class="font-bold text-sm text-gray-600 whitespace-nowrap">Jenis</label>
                    <select x-model="selectedJenis" class="border border-gray-300 rounded-lg px-4 py-1.5 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-teal-500 w-full md:w-40 bg-gray-50">
                        <option value="semua">Semua Jenis</option>
                        <option value="omset">Omset</option>
                        <option value="laba">Laba</option>
                        <option value="pades">PADes</option>
                        <option value="aset">Aset</option>
                        <option value="danasosial">Dana Sosial</option>
                    </select>
                </div>
            </div>

            <!-- Hasil Pemeringkatan -->
            <h3 class="text-md font-bold text-gray-900 mb-4">Pemeringkatan</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <span class="text-xs font-semibold text-gray-400 mb-1 block">Maju</span>
                    <span class="text-2xl font-black text-teal-700" x-text="getCurrentData().klasifikasi?.Maju ?? 0"></span>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <span class="text-xs font-semibold text-gray-400 mb-1 block">Berkembang</span>
                    <span class="text-2xl font-black text-teal-700" x-text="getCurrentData().klasifikasi?.Berkembang ?? 0"></span>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <span class="text-xs font-semibold text-gray-400 mb-1 block">Pemula</span>
                    <span class="text-2xl font-black text-teal-700" x-text="getCurrentData().klasifikasi?.Pemula ?? 0"></span>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <span class="text-xs font-semibold text-gray-400 mb-1 block">Perintis</span>
                    <span class="text-2xl font-black text-teal-700" x-text="getCurrentData().klasifikasi?.Perintis ?? 0"></span>
                </div>
            </div>
            
            <!-- Reguler -->
            <div x-show="hasGroup('reguler') && (selectedKategori === 'semua' || selectedKategori === 'reguler')" class="mb-6">
                <h3 class="text-md font-bold text-gray-900 mb-4">Reguler</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-2">
                    <div x-show="hasValue(getCurrentData().reguler.omset) && (selectedJenis === 'semua' || selectedJenis === 'omset')" class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-between">
                        <span class="text-xs font-semibold text-gray-400 mb-1 block tracking-wide uppercase">Omset</span>
                        <span class="text-xl font-black text-teal-700" x-text="formatRupiah(getCurrentData().reguler.omset)"></span>
                    </div>
                    <div x-show="hasValue(getCurrentData().reguler.laba) && (selectedJenis === 'semua' || selectedJenis === 'laba')" class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-between">
                        <span class="text-xs font-semibold text-gray-400 mb-1 block tracking-wide uppercase">Laba</span>
                        <span class="text-xl font-black text-teal-700" x-text="formatRupiah(getCurrentData().reguler.laba)"></span>
                    </div>
                    <div x-show="hasValue(getCurrentData().reguler.pades) && (selectedJenis === 'semua' || selectedJenis === 'pades')" class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-between">
                        <span class="text-xs font-semibold text-gray-400 mb-1 block tracking-wide uppercase">PADes</span>
                        <span class="text-xl font-black text-teal-700" x-text="formatRupiah(getCurrentData().reguler.pades)"></span>
                    </div>
                    <div x-show="hasValue(getCurrentData().reguler.aset) && (selectedJenis === 'semua' || selectedJenis === 'aset')" class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-between">
                        <span class="text-xs font-semibold text-gray-400 mb-1 block tracking-wide uppercase">Aset</span>
                        <span class="text-xl font-black text-teal-700" x-text="formatRupiah(getCurrentData().reguler.aset)"></span>
                    </div>
                    <div x-show="hasValue(getCurrentData().reguler.danasosial) && (selectedJenis === 'semua' || selectedJenis === 'danasosial')" class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-between">
                        <span class="text-xs font-semibold text-gray-400 mb-1 block tracking-wide uppercase">Dana Sosial</span>
                        <span class="text-xl font-black text-teal-700" x-text="formatRupiah(getCurrentData().reguler.danasosial)"></span>
                    </div>
                </div>
                <p class="text-xs text-gray-400 mb-2 italic ml-1">Akumulasi dari laporan dokumen BUMDesa</p>
            </div>

            <!-- Ketahanan Pangan -->
            <div x-show="hasGroup('ketapang') && (selectedKategori === 'semua' || selectedKategori === 'ketapang')" class="mb-6">
                <h3 class="text-md font-bold text-gray-900 mb-4">Ketahanan Pangan</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-2">
                    <div x-show="hasValue(getCurrentData().ketapang.omset) && (selectedJenis === 'semua' || selectedJenis === 'omset')" class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-between">
                        <span class="text-xs font-semibold text-gray-400 mb-1 block tracking-wide uppercase">Omset</span>
                        <span class="text-xl font-black text-teal-700" x-text="formatRupiah(getCurrentData().ketapang.omset)"></span>
                    </div>
                    <div x-show="hasValue(getCurrentData().ketapang.laba) && (selectedJenis === 'semua' || selectedJenis === 'laba')" class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-between">
                        <span class="text-xs font-semibold text-gray-400 mb-1 block tracking-wide uppercase">Laba</span>
                        <span class="text-xl font-black text-teal-700" x-text="formatRupiah(getCurrentData().ketapang.laba)"></span>
                    </div>
                    <div x-show="hasValue(getCurrentData().ketapang.pades) && (selectedJenis === 'semua' || selectedJenis === 'pades')" class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-between">
                        <span class="text-xs font-semibold text-gray-400 mb-1 block tracking-wide uppercase">PADes</span>
                        <span class="text-xl font-black text-teal-700" x-text="formatRupiah(getCurrentData().ketapang.pades)"></span>
                    </div>
                    <div x-show="hasValue(getCurrentData().ketapang.aset) && (selectedJenis === 'semua' || selectedJenis === 'aset')" class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-between">
                        <span class="text-xs font-semibold text-gray-400 mb-1 block tracking-wide uppercase">Aset</span>
                        <span class="text-xl font-black text-teal-700" x-text="formatRupiah(getCurrentData().ketapang.aset)"></span>
                    </div>
                    <div x-show="hasValue(getCurrentData().ketapang.danasosial) && (selectedJenis === 'semua' || selectedJenis === 'danasosial')" class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-between">
                        <span class="text-xs font-semibold text-gray-400 mb-1 block tracking-wide uppercase">Dana Sosial</span>
                        <span class="text-xl font-black text-teal-700" x-text="formatRupiah(getCurrentData().ketapang.danasosial)"></span>
                    </div>
                </div>
                <p class="text-xs text-gray-400 mb-2 italic ml-1">Akumulasi dari laporan dokumen BUMDesa</p>
            </div>

            <!-- Kegiatan Dana Bergulir Masyarakat (DBM) -->
            <div x-show="hasGroup('dbm') && (selectedKategori === 'semua' || selectedKategori === 'dbm')" class="mb-6">
                <h3 class="text-md font-bold text-gray-900 mb-4">Kegiatan Dana Bergulir Masyarakat (DBM)</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-2">
                    <div x-show="hasValue(getCurrentData().dbm.omset) && (selectedJenis === 'semua' || selectedJenis === 'omset')" class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-between">
                        <span class="text-xs font-semibold text-gray-400 mb-1 block tracking-wide uppercase">Omset</span>
                        <span class="text-xl font-black text-teal-700" x-text="formatRupiah(getCurrentData().dbm.omset)"></span>
                    </div>
                    <div x-show="hasValue(getCurrentData().dbm.laba) && (selectedJenis === 'semua' || selectedJenis === 'laba')" class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-between">
                        <span class="text-xs font-semibold text-gray-400 mb-1 block tracking-wide uppercase">Laba</span>
                        <span class="text-xl font-black text-teal-700" x-text="formatRupiah(getCurrentData().dbm.laba)"></span>
                    </div>
                    <div x-show="hasValue(getCurrentData().dbm.pades) && (selectedJenis === 'semua' || selectedJenis === 'pades')" class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-between">
                        <span class="text-xs font-semibold text-gray-400 mb-1 block tracking-wide uppercase">PADes</span>
                        <span class="text-xl font-black text-teal-700" x-text="formatRupiah(getCurrentData().dbm.pades)"></span>
                    </div>
                    <div x-show="hasValue(getCurrentData().dbm.aset) && (selectedJenis === 'semua' || selectedJenis === 'aset')" class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-between">
                        <span class="text-xs font-semibold text-gray-400 mb-1 block tracking-wide uppercase">Aset</span>
                        <span class="text-xl font-black text-teal-700" x-text="formatRupiah(getCurrentData().dbm.aset)"></span>
                    </div>
                    <div x-show="hasValue(getCurrentData().dbm.danasosial) && (selectedJenis === 'semua' || selectedJenis === 'danasosial')" class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-between">
                        <span class="text-xs font-semibold text-gray-400 mb-1 block tracking-wide uppercase">Dana Sosial</span>
                        <span class="text-xl font-black text-teal-700" x-text="formatRupiah(getCurrentData().dbm.danasosial)"></span>
                    </div>
                </div>
                <p class="text-xs text-gray-400 mb-12 italic ml-1">Akumulasi dari laporan dokumen BUMDesa</p>
            </div>
        </section>

        <!-- 3. Monitoring BUMDesa -->
        <section class="pb-24">
            <h2 class="text-xl font-extrabold text-gray-900 mb-6">Monitoring BUMDesa</h2>

            <h3 class="text-md font-bold text-gray-900 mb-4">1. Sudah Mengirim Laporan ke Dinas</h3>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-10 w-full lg:w-3/4">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-teal-700 text-white border-b border-teal-800">
                                <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider text-center w-16">No</th>
                                <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider text-center">Kecamatan</th>
                                <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider text-center">Jumlah BUMDesa</th>
                                <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider text-center">Sudah</th>
                                <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider text-center">Belum</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($monitoring as $index => $mon)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-3 text-sm text-gray-600 text-center">{{ $index + 1 }}</td>
                                <td class="px-6 py-3 text-sm text-gray-800 font-medium text-center">{{ $mon->kecamatan }}</td>
                                <td class="px-6 py-3 text-sm text-gray-900 text-center font-semibold">{{ number_format($mon->total) }}</td>
                                <td class="px-6 py-3 text-sm text-gray-900 text-center font-semibold">{{ number_format($mon->sudah_mengirim) }}</td>
                                <td class="px-6 py-3 text-sm text-gray-900 text-center font-semibold">{{ number_format($mon->belum_mengirim) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-6 text-center text-sm text-gray-400 italic">Data pengiriman laporan belum tersedia.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <h3 class="text-md font-bold text-gray-900 mb-4">2. Dalam Pantauan Khusus</h3>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 w-full lg:w-2/3 mb-10 flex items-center justify-between">
                <span class="text-sm font-semibold text-gray-800">Jumlah BUMDesa:</span>
                <span class="text-lg font-bold text-gray-900">{{ number_format($dalam_pantauan_khusus) }}</span>
            </div>

            <h3 class="text-md font-bold text-gray-900 mb-4">Mitra Kerjasama</h3>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8 w-full lg:w-3/4">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-teal-700 text-white border-b border-teal-800">
                                <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider text-center">Mitra</th>
                                <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider text-center text-right">Jumlah BUMDesa</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($mitra_kerjasama as $mitra)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-sm text-gray-800 font-medium text-center">{{ $mitra->mitra }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900 text-center font-semibold text-right">{{ number_format($mitra->total) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="px-6 py-6 text-center text-sm text-gray-400 italic">Belum ada data mitra kerjasama.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('infografisData', () => ({
            rawData: @json($perkembangan),
            selectedTahun: null,
            selectedKategori: 'semua',
            selectedJenis: 'semua',

            init() {
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
                return this.rawData.map(v => String(v.thn));
            },

            getCurrentData() {
                let yearData = this.rawData.find(d => String(d.thn) === String(this.selectedTahun));
                const empty = { omset: null, laba: null, pades: null, aset: null, danasosial: null };
                const emptyKlas = { Maju: 0, Berkembang: 0, Pemula: 0, Perintis: 0 };
                if (!yearData) {
                    return { reguler: { ...empty }, ketapang: { ...empty }, dbm: { ...empty }, klasifikasi: { ...emptyKlas } };
                }
                return {
                    reguler:  { 
                        omset: yearData.reguler?.omset, 
                        laba:  yearData.reguler?.laba, 
                        pades: yearData.reguler?.pades, 
                        aset:  yearData.reguler?.aset,
                        danasosial: yearData.reguler?.danasosial
                    },
                    ketapang: { 
                        omset: yearData.ketapang?.omset, 
                        laba:  yearData.ketapang?.laba, 
                        pades: yearData.ketapang?.pades, 
                        aset:  yearData.ketapang?.aset,
                        danasosial: yearData.ketapang?.danasosial
                    },
                    dbm: { 
                        omset: yearData.dbm?.omset, 
                        laba:  yearData.dbm?.laba, 
                        pades: yearData.dbm?.pades, 
                        aset:  yearData.dbm?.aset,
                        danasosial: yearData.dbm?.danasosial
                    },
                    klasifikasi: yearData.klasifikasi || { ...emptyKlas }
                };
            },

            hasValue(value) {
                if (value === null || value === undefined || value === '') return false;
                const num = parseFloat(value);
                if (isNaN(num) || num === 0) return false;
                return true;
            },

            hasGroup(group) {
                const d = this.getCurrentData();
                return this.hasValue(d[group].omset) || this.hasValue(d[group].laba) || this.hasValue(d[group].aset) || this.hasValue(d[group].danasosial);
            },

            formatRupiah(value) {
                if (value === null || value === undefined) return '-';
                const num = parseFloat(value);
                if (isNaN(num) || num === 0) return '-';
                if (num >= 1000000000) return 'Rp ' + (num / 1000000000).toLocaleString('id-ID', {minimumFractionDigits: 1, maximumFractionDigits: 1}) + ' M';
                if (num >= 1000000) return 'Rp ' + (num / 1000000).toLocaleString('id-ID', {minimumFractionDigits: 1, maximumFractionDigits: 1}) + ' Jt';
                return 'Rp ' + num.toLocaleString('id-ID');
            }
        }))
    });
</script>
@endpush
