@extends('layouts.admin')
@section('title', 'Data Finansial BUMDesa')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Rekapitulasi Data Finansial BUMDesa</h2>
    <p class="text-gray-500 text-sm mt-1">Pantau pelaporan keuangan, pendapatan, laba bersih, dan aset seluruh BUMDesa di kabupaten Anda.</p>
</div>

<div class="bg-white rounded-xl shadow-sm border p-6">
    <div class="table-responsive w-full overflow-x-auto">
        <table class="datatable w-full whitespace-nowrap text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th class="px-6 py-3">Nama BUMDesa</th>
                    <th class="px-6 py-3 text-right">Total Pendapatan (Tahunan)</th>
                    <th class="px-6 py-3 text-right">Total Laba Bersih</th>
                    <th class="px-6 py-3 text-right">Total Aset</th>
                    <th class="px-6 py-3 text-center">Status Pelaporan</th>
                    <th class="px-6 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bumdesList as $b)
                @php
                    // Aggregate for display
                    $totalPendapatan = $b->laporanKeuangan->sum('pendapatan');
                    $totalLaba = $b->laporanKeuangan->sum('laba_bersih');
                    // Total aset can be taken from the latest report
                    $latestAset = $b->laporanKeuangan->first() ? $b->laporanKeuangan->first()->aset : 0;
                    $countReport = $b->laporanKeuangan->count();
                @endphp
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="font-bold text-gray-900">{{ $b->name }}</div>
                        <div class="text-xs text-gray-500 mt-1">{{ $b->desa }}</div>
                    </td>
                    <td class="px-6 py-4 text-right font-medium text-blue-600">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-right font-bold text-green-600">Rp {{ number_format($totalLaba, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-right text-purple-600">Rp {{ number_format($latestAset, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-center">
                        @if($countReport > 0)
                            <span class="bg-green-100 text-green-800 text-[10px] font-bold px-2 py-0.5 rounded border border-green-200">{{ $countReport }} Laporan</span>
                        @else
                            <span class="bg-red-100 text-red-800 text-[10px] font-bold px-2 py-0.5 rounded border border-red-200">Belum Lapor</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        @if($countReport > 0)
                        <a href="{{ route('adminkab.finansial.show', $b->id) }}" class="text-white hover:text-white bg-primary hover:bg-primary-900 px-3 py-1.5 rounded-md text-xs font-semibold transition-colors inline-block tooltip" title="Lihat Detail BUMDes">
                            <i class="fa-solid fa-chart-line mr-1"></i> Detail
                        </a>
                        @else
                        <button disabled class="text-white bg-gray-300 px-3 py-1.5 cursor-not-allowed rounded-md text-xs font-semibold inline-block">
                            <i class="fa-solid fa-chart-line mr-1"></i> Detail
                        </button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
