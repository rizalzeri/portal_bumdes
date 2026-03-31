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
        @if ($errors->any())
            <div class="bg-red-50 text-red-600 p-4 rounded-lg mb-4 text-sm font-semibold border border-red-200">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('success'))
            <div class="bg-green-50 text-green-700 p-4 rounded-lg mb-4 text-sm font-semibold border border-green-200">
                <i class="fa-solid fa-circle-check mr-2"></i> {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('user.transparansi.store', ['slug' => $bumdes->slug ?? 'default']) }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Musdes Terakhir</label>
                    <input type="date" name="musdes_terakhir" value="{{ old('musdes_terakhir', $bumdes->musdes_terakhir) }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2 bg-gray-50 focus:bg-white transition-all">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Audit Internal Terakhir</label>
                    <input type="date" name="audit_internal_terakhir" value="{{ old('audit_internal_terakhir', $bumdes->audit_internal_terakhir) }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2 bg-gray-50 focus:bg-white transition-all">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1 leading-tight">Status Laporan Ke Dinas</label>
                    <p class="text-[9px] text-gray-400 lowercase italic mb-1">Upload ke GDrive & masukkan link di bawah</p>
                    <select name="laporan_dinas_status" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2 text-gray-700 bg-gray-50 focus:bg-white transition-all h-9">
                        <option value="belum" {{ $bumdes->laporan_dinas_status == 'belum' ? 'selected' : '' }}>Belum Dikirim</option>
                        <option value="sudah" {{ $bumdes->laporan_dinas_status == 'sudah' ? 'selected' : '' }}>Sudah Dikirim</option>
                    </select>
                </div>
                <div class="md:col-span-2 lg:col-span-3 mt-4">
                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Link GDrive Laporan <i class="fa-brands fa-google-drive text-blue-500 ml-1"></i></label>
                    <input type="url" name="laporan_dinas_link" value="{{ old('laporan_dinas_link', $bumdes->laporan_dinas_link) }}" placeholder="https://drive.google.com/..." class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
                    <p class="text-[10px] text-gray-500 mt-1">Upload berkas laporan ke Google Drive terlebih dahulu, kemudian tempel/paste link-nya di sini.</p>
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
