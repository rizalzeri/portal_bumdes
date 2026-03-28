@extends('layouts.admin')
@section('title', 'Monitoring BUMDesa')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Monitoring Pelaporan BUMDesa</h2>
        <p class="text-gray-500 text-sm mt-1">Pantau status pengiriman laporan dan perhatian khusus BUMDesa.</p>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
    <h3 class="font-bold text-lg mb-4 text-gray-800 border-b pb-2"><i class="fa-solid fa-list-check text-primary mr-2"></i> Kategori Monitoring</h3>
    
    <div class="flex flex-wrap gap-2">
        <!-- 1, 2, 3 -->
        <a href="{{ route('adminkab.monitoring.index', ['tab' => 'sudah_mengirim']) }}" class="px-5 py-2.5 text-sm font-semibold rounded-lg border {{ $tab == 'sudah_mengirim' ? 'bg-primary text-white border-primary shadow-sm' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-gray-100' }}">
            <i class="fa-solid fa-check-circle mr-1 text-green-400"></i> Sudah Mengirim Dokumen Laporan
        </a>
        <a href="{{ route('adminkab.monitoring.index', ['tab' => 'belum_mengirim']) }}" class="px-5 py-2.5 text-sm font-semibold rounded-lg border {{ $tab == 'belum_mengirim' ? 'bg-primary text-white border-primary shadow-sm' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-gray-100' }}">
            <i class="fa-solid fa-clock mr-1 text-orange-400"></i> Belum Mengirim Dokumen Laporan
        </a>
        <a href="{{ route('adminkab.monitoring.index', ['tab' => 'perhatian_khusus']) }}" class="px-5 py-2.5 text-sm font-semibold rounded-lg border {{ $tab == 'perhatian_khusus' ? 'bg-primary text-white border-primary shadow-sm' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-gray-100' }}">
            <i class="fa-solid fa-triangle-exclamation mr-1 text-red-400"></i> Dalam Perhatian Khusus
        </a>
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
                    <th class="px-6 py-3 border-r">Lokasi</th>
                    <th class="px-6 py-3 border-r">Kontak Pengurus</th>
                    <th class="px-6 py-3 border-r">Status Pelaporan</th>
                    <th class="px-6 py-3 text-center">Tindakan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bumdes as $b)
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="px-6 py-4 border-r">
                        <div class="font-bold text-gray-900">{{ $b->name }}</div>
                        <div class="text-xs text-gray-500">No. Sertifikat: {{ $b->nomor_sertifikat ?? 'Tidak Ada' }}</div>
                    </td>
                    <td class="px-6 py-4 border-r">
                        <div class="font-medium text-gray-700">{{ $b->desa }}</div>
                        <div class="text-xs text-gray-500">{{ $b->kecamatan }}</div>
                    </td>
                    <td class="px-6 py-4 border-r text-xs">
                        <div>Email: <span class="font-medium text-blue-600">{{ $b->user->email ?? '-' }}</span></div>
                        <div>No. Telp: <span class="font-medium text-gray-600">{{ $b->phone ?? '-' }}</span></div>
                    </td>
                    <td class="px-6 py-4 border-r">
                        @if ($b->laporanKeuangan()->count() > 0)
                            <span class="text-green-600 font-bold bg-green-50 px-2 py-1 rounded"><i class="fa-solid fa-check mr-1"></i> Ada Laporan ({{ $b->laporanKeuangan()->count() }})</span>
                        @else
                            <span class="text-red-500 font-bold bg-red-50 px-2 py-1 rounded"><i class="fa-solid fa-times mr-1"></i> Belum Kirim Laporan</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <a href="mailto:{{ $b->user->email ?? '' }}" class="bg-blue-100 hover:bg-blue-200 text-blue-700 p-2 rounded-md tooltip text-xs font-semibold {{ !$b->user ? 'opacity-50 cursor-not-allowed' : '' }}" title="Kirim Surat Email Pengingat">
                            <i class="fa-regular fa-envelope mr-1"></i> Ingatkan
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                        <i class="fa-solid fa-clipboard-check text-4xl text-green-300 mb-2 block"></i>
                        Tidak ada data BUMDesa dalam kategori monitoring ini. Semua baik-baik saja!
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
