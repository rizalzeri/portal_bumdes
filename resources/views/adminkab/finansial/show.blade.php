@extends('layouts.admin')
@section('title', 'Detail Finansial BUMDesa')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <div class="flex items-center gap-2 mb-2 text-sm text-gray-500">
                <a href="{{ route('adminkab.keuangan.index') }}" class="hover:text-primary transition"><i
                        class="fa-solid fa-arrow-left mr-1"></i> Rekapitulasi</a>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">Detail Laporan Keuangan: {{ $bumde->name }}</h2>
            <p class="text-gray-500 text-sm mt-1">Desa {{ $bumde->desa }}, Kecamatan {{ $bumde->kecamatan }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border p-6">
        <div class="table-responsive w-full overflow-x-auto">
            <table class="datatable w-full whitespace-nowrap text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th class="px-6 py-3">Bulan / Tahun</th>
                        <th class="px-6 py-3 text-right">Pendapatan</th>
                        <th class="px-6 py-3 text-right">Pengeluaran</th>
                        <th class="px-6 py-3 text-right">Laba Bersih</th>
                        <th class="px-6 py-3 text-right">Total Aset</th>
                        <th class="px-6 py-3 text-right">Lampiran File</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($laporans as $lap)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td
                                class="px-6 py-4 font-bold text-gray-900 border-l-4 {{ ($lap->laba_rugi ?? 0) >= 0 ? 'border-green-500' : 'border-red-500' }}">
                                {{ config('app.months')[$lap->bulan] ??
                                    match ($lap->bulan) {
                                        1 => 'Januari',
                                        2 => 'Februari',
                                        3 => 'Maret',
                                        4 => 'April',
                                        5 => 'Mei',
                                        6 => 'Juni',
                                        7 => 'Juli',
                                        8 => 'Agustus',
                                        9 => 'September',
                                        10 => 'Oktober',
                                        11 => 'November',
                                        12 => 'Desember',
                                        default => $lap->bulan,
                                    } }}
                                {{ $lap->tahun }}
                            </td>
                            <td class="px-6 py-4 text-right text-blue-600 font-medium">Rp
                                {{ number_format($lap->pendapatan, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right text-red-500 font-medium">Rp
                                {{ number_format($lap->pengeluaran, 0, ',', '.') }}</td>
                            <td
                                class="px-6 py-4 text-right font-bold {{ ($lap->laba_rugi ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                Rp {{ number_format($lap->laba_rugi ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-right text-purple-600">Rp
                                {{ number_format($lap->total_aset, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right">
                                @if ($lap->file_url)
                                    <a href="{{ asset('storage/' . $lap->file_url) }}" target="_blank"
                                        class="text-xs bg-gray-100 border text-gray-700 px-3 py-1.5 rounded hover:bg-gray-200 transition font-medium"><i
                                            class="fa-solid fa-download mr-1"></i> Unduh Laporan</a>
                                @else
                                    <span class="text-xs text-gray-400 italic">Tidak ada file</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
