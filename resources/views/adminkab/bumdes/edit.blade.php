@extends('layouts.admin')
@section('title', 'Ubah Data BUMDesa')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-2 mb-2 text-sm text-gray-500">
        <a href="{{ route('adminkab.bumdes.index') }}" class="hover:text-primary transition"><i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke Daftar</a>
    </div>
    <h2 class="text-2xl font-bold text-gray-800">Ubah Data / Validasi: {{ $bumde->name }}</h2>
    <p class="text-gray-500 text-sm mt-1">Perbarui informasi profil dan klasifikasi BUMDesa.</p>
</div>

<div class="bg-white rounded-xl shadow-sm border p-6 max-w-3xl">
    <form action="{{ route('adminkab.bumdes.update', $bumde->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div class="border-b pb-4">
            <h3 class="font-bold text-gray-900 border-l-4 border-primary pl-3 mb-4"><i class="fa-solid fa-building mr-2 text-primary"></i> Data Profil Dasar BUMDesa</h3>
            <div class="space-y-4 px-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama BUMDesa <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $bumde->name) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
                    @error('name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Kecamatan <span class="text-red-500">*</span></label>
                        <select name="kecamatan" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
                            <option value="">-- Pilih Kecamatan --</option>
                            @foreach($kecamatans as $kec)
                                <option value="{{ $kec->name }}" {{ old('kecamatan', $bumde->kecamatan) == $kec->name ? 'selected' : '' }}>{{ $kec->name }}</option>
                            @endforeach
                        </select>
                        @if($kecamatans->isEmpty())
                        <div class="text-xs text-red-500 mt-1">Belum ada data kecamatan! Silakan <a href="{{ route('adminkab.kecamatan.index') }}" class="underline font-bold">Daftarkan Kecamatan</a> terlebih dahulu.</div>
                        @endif
                        @error('kecamatan') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Desa <span class="text-red-500">*</span></label>
                        <input type="text" name="desa" value="{{ old('desa', $bumde->desa) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
                    </div>
                </div>
            </div>
        </div>

        <div>
            <h3 class="font-bold text-gray-900 border-l-4 border-accent pl-3 mb-4"><i class="fa-solid fa-stamp mr-2 text-accent"></i> Validasi Sistem</h3>
            <div class="space-y-4 px-3">
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status Sistem BUMDesa <span class="text-red-500">*</span></label>
                        <select name="status" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2 @if($bumde->status === 'inactive') bg-red-50 border-red-300 @endif">
                            <option value="active" {{ old('status', $bumde->status) == 'active' ? 'selected' : '' }}>Aktif (Dibolehkan Akses Portal)</option>
                            <option value="inactive" {{ old('status', $bumde->status) == 'inactive' ? 'selected' : '' }}>Inaktif (Dibekukan Sementara)</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-4 flex justify-end gap-3 border-t">
            <a href="{{ route('adminkab.bumdes.index') }}" class="px-5 py-2.5 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Batal</a>
            <button type="submit" class="px-5 py-2.5 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-900"><i class="fa-solid fa-save mr-2"></i> Perbarui Data</button>
        </div>
    </form>
</div>
@endsection
