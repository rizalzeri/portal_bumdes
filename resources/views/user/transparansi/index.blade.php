@extends('layouts.admin')
@section('title', 'Standar Transparansi')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Standar Transparansi</h2>
        <p class="text-gray-500 text-sm mt-1">Unggah dokumen resmi (AD/ART, Perdes, Bukti Setor PADes) untuk dinilai kepatuhan BUMDesa Anda.</p>
    </div>
    <button onclick="document.getElementById('modal-add').classList.remove('hidden')" class="bg-primary hover:bg-primary-900 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors shadow-sm">
        <i class="fa-solid fa-cloud-arrow-up mr-2"></i> Unggah Dokumen Baru
    </button>
</div>

<!-- Laporan List -->
<div class="bg-white rounded-xl shadow-sm border p-6">
    <div class="table-responsive w-full overflow-x-auto">
        <table class="datatable w-full whitespace-nowrap text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th class="px-6 py-3">Tahun Dokumen</th>
                    <th class="px-6 py-3">Tipe / Jenis Dokumen</th>
                    <th class="px-6 py-3 text-center">Berkas (PDF)</th>
                    <th class="px-6 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dokumen as $dok)
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="px-6 py-4 font-bold text-gray-900">{{ $dok->tahun }}</td>
                    <td class="px-6 py-4 font-medium">{{ $dok->tipe_dokumen }}</td>
                    <td class="px-6 py-4 text-center">
                        @if($dok->file_dokumen)
                            <a href="{{ asset('storage/'.$dok->file_dokumen) }}" target="_blank" class="text-xs bg-gray-100 border text-gray-700 px-3 py-1.5 rounded hover:bg-gray-200 transition font-bold tooltip" title="Unduh / Lihat Dokumen">
                                <i class="fa-solid fa-file-pdf text-red-500 mr-1"></i> Lihat Dokumen
                            </a>
                        @else
                            <span class="text-xs text-gray-400 italic">Tidak ada lampiran</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <form action="{{ route('user.transparansi.destroy', $dok->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus dokumen transparansi ini?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-2 rounded-md transition-colors" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Add Modal -->
<div id="modal-add" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-xl bg-white mb-20">
        <div class="flex justify-between items-center mb-4 border-b pb-2">
            <h3 class="text-xl font-bold text-gray-900">Unggah Dokumen Transparansi</h3>
            <button onclick="document.getElementById('modal-add').classList.add('hidden')" class="text-gray-400 hover:text-gray-900 text-2xl font-bold">&times;</button>
        </div>
        <form action="{{ route('user.transparansi.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700">Jenis Dokumen</label>
                <select name="tipe_dokumen" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
                    <option value="">-- Pilih Jenis --</option>
                    <option value="Peraturan Desa (Perdes)">Peraturan Desa (Perdes)</option>
                    <option value="AD/ART (Anggaran Dasar/Rumah Tangga)">AD/ART</option>
                    <option value="LPJ Tahunan (Laporan Pertanggungjawaban)">LPJ Tahunan</option>
                    <option value="Bukti Setor PADes (Pendapatan Asli Desa)">Bukti Setor PADes</option>
                    <option value="Lainnya">Lainnya...</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Tahun Penerbitan / Berlakunya Dokumen</label>
                <input type="number" name="tahun" value="{{ date('Y') }}" required min="2000" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
            </div>
            
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700">File Dokumen (Hanya PDF)</label>
                <input type="file" name="file_dokumen" accept=".pdf" required class="mt-1 block w-full border text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-gray-200 file:text-gray-700 border-gray-300 rounded-md p-2 bg-gray-50">
                <p class="text-xs text-gray-500 mt-1">Dokumen wajib bermaterai dan ditandatangani. Maksimal 10MB.</p>
            </div>
            
            <div class="pt-4 border-t flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modal-add').classList.add('hidden')" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Batal</button>
                <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-900">Unggah Dokumen</button>
            </div>
        </form>
    </div>
</div>
@endsection
