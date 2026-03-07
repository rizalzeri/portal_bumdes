@extends('layouts.admin')
@section('title', 'Profil BUMDesa')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Profil BUMDesa</h2>
        <p class="text-gray-500 text-sm mt-1">Lengkapi data informasi publik yang akan ditampilkan di halaman detail Portal BUMDesa.</p>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border p-6">
    <form action="{{ route('user.profil.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')
        
        <!-- Logo & Informasi Utama -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="md:col-span-1 space-y-4">
                <h3 class="font-bold text-gray-900 border-b pb-2 mb-4">Logo BUMDesa</h3>
                <div class="flex flex-col items-center justify-center p-6 border-2 border-dashed border-gray-300 rounded-xl bg-gray-50 text-center">
                    @if($bumdes->logo)
                        <img src="{{ asset('storage/'.$bumdes->logo) }}" class="w-32 h-32 object-contain bg-white rounded shadow-sm border p-1 mb-4">
                    @else
                        <div class="w-32 h-32 bg-gray-200 rounded-full flex items-center justify-center mb-4 text-gray-400">
                            <i class="fa-solid fa-building text-4xl"></i>
                        </div>
                    @endif
                    <input type="file" name="logo" accept="image/*" class="w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-primary file:text-white hover:file:bg-primary-900 mx-auto">
                    <p class="text-[10px] text-gray-500 mt-2">Format: JPG, PNG. Rekomendasi 500x500 px. Maks 2MB.</p>
                </div>
            </div>

            <div class="md:col-span-2 space-y-4">
                <h3 class="font-bold text-gray-900 border-b pb-2 mb-4">Identitas Resmi</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Nama BUMDesa (Readonly)</label>
                        <input type="text" value="{{ $bumdes->name }}" disabled class="bg-gray-100 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm border p-2 text-gray-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Klasifikasi Sistem</label>
                        <span class="inline-block px-3 py-1.5 rounded-md font-bold text-sm bg-blue-50 text-blue-700 border border-blue-200 w-full">{{ $bumdes->klasifikasi ?? 'Belum Dinilai' }}</span>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Desa / Kelurahan</label>
                        <input type="text" value="{{ $bumdes->desa }}" disabled class="bg-gray-100 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm border p-2 text-gray-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Kecamatan</label>
                        <input type="text" value="{{ $bumdes->kecamatan }}" disabled class="bg-gray-100 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm border p-2 text-gray-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Status Badan Hukum <i class="fa-solid fa-circle-check text-green-500 ml-1"></i></label>
                        <input type="text" name="badan_hukum" value="{{ old('badan_hukum', $bumdes->badan_hukum) }}" placeholder="Contoh: Terdaftar Kemenkumham" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Nomor Sertifikat / SK</label>
                        <input type="text" name="nomor_sertifikat" value="{{ old('nomor_sertifikat', $bumdes->nomor_sertifikat) }}" placeholder="Nomor registrasi resmi" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2 uppercase font-mono text-sm">
                    </div>
                </div>
            </div>
        </div>

        <!-- Deskripsi BUMDes -->
        <div>
            <h3 class="font-bold text-gray-900 border-b pb-2 mb-4 mt-6">Informasi & Sejarah</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Deskripsi Singkat (Tentang BUMDes)</label>
                    <textarea name="about" rows="3" placeholder="Ceritakan secara singkat mengenai BUMDes Anda..." class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">{{ old('about', $bumdes->about) }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Visi dan Misi</label>
                    <textarea name="visi_misi" rows="4" placeholder="Tuliskan Visi dan Misi BUMDesa..." class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">{{ old('visi_misi', $bumdes->visi_misi) }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Sejarah Pendirian</label>
                    <textarea name="sejarah" rows="3" placeholder="Bagaimana BUMDesa ini didirikan..." class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">{{ old('sejarah', $bumdes->sejarah) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Kontak dan Media Sosial -->
        <div>
            <h3 class="font-bold text-gray-900 border-b pb-2 mb-4 mt-6">Kontak & Jaringan Sosial</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1"><i class="fa-solid fa-phone text-gray-400 mr-1"></i> Telp/WhatsApp</label>
                    <input type="text" name="phone" value="{{ old('phone', $bumdes->phone) }}" placeholder="08..." class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1"><i class="fa-solid fa-envelope text-gray-400 mr-1"></i> Email Publik</label>
                    <input type="email" name="email" value="{{ old('email', $bumdes->email) }}" placeholder="email@bumdes.com" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1"><i class="fa-solid fa-globe text-gray-400 mr-1"></i> Website Resmi</label>
                    <input type="url" name="website" value="{{ old('website', $bumdes->website) }}" placeholder="https://..." class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1"><i class="fa-brands fa-instagram text-gray-400 mr-1"></i> Akun Instagram</label>
                    <input type="text" name="instagram" value="{{ old('instagram', $bumdes->instagram) }}" placeholder="@username" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                 <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1"><i class="fa-brands fa-facebook text-gray-400 mr-1"></i> Tautan Profil Facebook (URL)</label>
                    <input type="url" name="facebook" value="{{ old('facebook', $bumdes->facebook) }}" placeholder="https://facebook.com/..." class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
                </div>
            </div>
        </div>

        <!-- Lokasi Peta (Opsional) -->
        <div>
            <h3 class="font-bold text-gray-900 border-b pb-2 mb-4 mt-6">Peta Digital (Opsional)</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Garis Lintang (Latitude)</label>
                    <input type="number" step="any" name="latitude" value="{{ old('latitude', $bumdes->latitude) }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2 font-mono">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Garis Bujur (Longitude)</label>
                    <input type="number" step="any" name="longitude" value="{{ old('longitude', $bumdes->longitude) }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2 font-mono">
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-2">Dapatkan koordinat letak kantor BUMDesa dari Google Maps dengan melakukan klik kanan pada titik peta lalu pilih angkanya.</p>
        </div>

        <div class="pt-6 mt-8 border-t flex justify-end">
            <button type="submit" class="bg-primary hover:bg-primary-900 text-white px-8 py-3 rounded-lg font-bold shadow-lg transition-colors flex items-center text-sm">
                <i class="fa-solid fa-cloud-arrow-up mr-2 text-lg"></i> Simpan Profil BUMDesa
            </button>
        </div>
    </form>
</div>
@endsection
