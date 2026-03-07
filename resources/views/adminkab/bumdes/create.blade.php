@extends('layouts.admin')
@section('title', 'Daftarkan BUMDesa Baru')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-2 mb-2 text-sm text-gray-500">
        <a href="{{ route('adminkab.bumdes.index') }}" class="hover:text-primary transition"><i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke Daftar</a>
    </div>
    <h2 class="text-2xl font-bold text-gray-800">Daftarkan BUMDesa Baru</h2>
    <p class="text-gray-500 text-sm mt-1">Buat profil BUMDesa dan akun pengurus untuk dapat login ke sistem.</p>
</div>

<div class="bg-white rounded-xl shadow-sm border p-6 max-w-3xl">
    <form action="{{ route('adminkab.bumdes.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <div class="border-b pb-4">
            <h3 class="font-bold text-gray-900 border-l-4 border-primary pl-3 mb-4"><i class="fa-solid fa-building mr-2 text-primary"></i> Data Profil Dasar BUMDesa</h3>
            <div class="space-y-4 px-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama BUMDesa <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: BUMDesa Tunas Jaya" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
                    @error('name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Kecamatan <span class="text-red-500">*</span></label>
                        <input type="text" name="kecamatan" value="{{ old('kecamatan') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
                        @error('kecamatan') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Desa <span class="text-red-500">*</span></label>
                        <input type="text" name="desa" value="{{ old('desa') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
                        @error('desa') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
        </div>

        <div>
            <h3 class="font-bold text-gray-900 border-l-4 border-accent pl-3 mb-4"><i class="fa-solid fa-user-shield mr-2 text-accent"></i> Akun Pengurus / Login Akses</h3>
            <p class="text-xs text-gray-500 mb-4 px-3">Informasi ini akan digunakan oleh pengurus BUMDesa untuk masuk ke dalam portal.</p>
            <div class="space-y-4 px-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email Utama BUMDes <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="Contoh: admin@bumdestunasjaya.com" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
                    @error('email') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Password <span class="text-red-500">*</span></label>
                        <input type="password" name="password" required minlength="8" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
                        @error('password') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Konfirmasi Password <span class="text-red-500">*</span></label>
                        <input type="password" name="password_confirmation" required minlength="8" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-4 flex justify-end gap-3 border-t">
            <a href="{{ route('adminkab.bumdes.index') }}" class="px-5 py-2.5 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Batal</a>
            <button type="submit" class="px-5 py-2.5 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-900"><i class="fa-solid fa-save mr-2"></i> Simpan Pendaftaran</button>
        </div>
    </form>
</div>
@endsection
