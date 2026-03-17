@extends('layouts.admin')
@section('title', 'Standar Transparansi')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Transparansi BUMDesa</h2>
            <p class="text-gray-500 text-sm mt-1">Kelola data transparansi publik BUMDesa seperti status pelaporan ke Dinas PMD Kabupaten, Musdes dan Audit Internal.</p>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="bg-white rounded-xl shadow-sm border p-6">
        <form action="{{ route('user.transparansi.store', ['slug' => $bumdes->slug ?? 'default']) }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Musdes Terakhir</label>
                    <input type="date" name="musdes_terakhir" value="{{ old('musdes_terakhir', $bumdes->musdes_terakhir) }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Audit Internal Terakhir</label>
                    <input type="date" name="audit_internal_terakhir" value="{{ old('audit_internal_terakhir', $bumdes->audit_internal_terakhir) }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Status Laporan Ke Dinas</label>
                    <select name="laporan_dinas_status" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
                        <option value="belum" {{ $bumdes->laporan_dinas_status == 'belum' ? 'selected' : '' }}>Belum Dikirim</option>
                        <option value="sudah" {{ $bumdes->laporan_dinas_status == 'sudah' ? 'selected' : '' }}>Sudah Dikirim</option>
                    </select>
                </div>
                <div class="md:col-span-2 lg:col-span-3 mt-4">
                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Link GDrive (Lampiran Laporan Dinas)</label>
                    <input type="url" name="laporan_dinas_link" value="{{ old('laporan_dinas_link', $bumdes->laporan_dinas_link) }}" placeholder="https://drive.google.com/..." class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
                    <p class="text-[10px] text-gray-500 mt-1">Isi dengan tautan/link folder Google Drive atau tautan eksternal lainnya jika Anda sudah mengirimkan laporan.</p>
                </div>
            </div>

            <div class="pt-6 mt-8 border-t flex justify-end">
                <button type="submit" class="bg-primary hover:bg-primary-900 text-white px-8 py-3 rounded-lg font-bold shadow-lg transition-colors flex items-center text-sm">
                    <i class="fa-solid fa-cloud-arrow-up mr-2 text-lg"></i> Simpan Transparansi
                </button>
            </div>
        </form>
    </div>
@endsection
