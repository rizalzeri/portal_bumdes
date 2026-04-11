@extends('layouts.public')
@section('title', 'Layanan & Produk')

@section('content')
<div class="bg-gray-50 pt-12 pb-24 text-center">
    <!-- Section: Layanan & Produk -->
    <section class="py-20 bg-gray-50" id="layanan-produk">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-accent font-bold tracking-wider uppercase text-sm">Solusi Digital</span>
                <h2 class="mt-2 text-3xl font-extrabold text-gray-900 sm:text-4xl">Layanan & Produk</h2>
                <div class="mt-4 w-24 h-1 bg-accent mx-auto rounded-full"></div>
                <p class="mt-4 text-xl text-gray-500 max-w-3xl mx-auto">Kami menyediakan berbagai layanan dan produk untuk mendukung digitalisasi dan administrasi BUMDesa.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Layanan 1 -->
                <div class="bg-white p-8 rounded-3xl shadow-sm hover:shadow-xl transition-shadow border border-gray-100 group">
                    <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-2xl mb-6 group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all text-left">
                        <i class="fa-solid fa-globe"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3 text-left">Aplikasi Pengelolaan Website (<a href="https://portalbumdes.com" target="_blank" class="text-blue-600 hover:underline">portalbumdes.com</a>) </h3>
                    <p class="text-gray-500 mb-4 text-left">Platform ini memungkinkan BUMDesa memiliki website resmi secara gratis tanpa perlu kemampuan 
coding. Website dapat digunakan sebagai sarana publikasi profil, unit usaha, kegiatan, serta 
transparansi informasi kepada masyarakat. </p>
                </div>

                <!-- Layanan 2 -->
                <div class="bg-white p-8 rounded-3xl shadow-sm hover:shadow-xl transition-shadow border border-gray-100 group">
                    <div class="w-14 h-14 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center text-2xl mb-6 group-hover:scale-110 group-hover:bg-green-600 group-hover:text-white transition-all text-left">
                        <i class="fa-solid fa-calculator"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3 text-left">Aplikasi Keuangan BUMDesa (<a href="https://bumdespro.my.id" target="_blank" class="text-green-600 hover:underline">bumdespro.my.id</a>)</h3>
                    <p class="text-gray-500 mb-4 text-left">Aplikasi ini dirancang untuk membantu pengurus dalam mengelola keuangan secara tertib dan 
sistematis, mulai dari pencatatan transaksi, laporan keuangan, hingga rekapitulasi data yang siap 
digunakan untuk pelaporan.</p>
                </div>

                <!-- Layanan 3 -->
                <div class="bg-white p-8 rounded-3xl shadow-sm hover:shadow-xl transition-shadow border border-gray-100 group">
                    <div class="w-14 h-14 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center text-2xl mb-6 group-hover:scale-110 group-hover:bg-purple-600 group-hover:text-white transition-all text-left">
                        <i class="fa-solid fa-file-invoice"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3 text-left">Aplikasi Dokumen & SPJ (<a href="https://bumdespro2.my.id" target="_blank" class="text-purple-600 hover:underline">bumdespro.my.id</a>)</h3>
                    <p class="text-gray-500 mb-4 text-left">Mendukung pengelolaan administrasi secara digital, aplikasi ini memudahkan dalam penyusunan 
dokumen dan SPJ (Surat Pertanggungjawaban) secara cepat, rapi, dan sesuai kebutuhan 
pelaporan. </p>
                </div>

                <!-- Layanan 4 -->
                <div class="bg-white p-8 rounded-3xl shadow-sm hover:shadow-xl transition-shadow border border-gray-100 group">
                    <div class="w-14 h-14 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center text-2xl mb-6 group-hover:scale-110 group-hover:bg-amber-600 group-hover:text-white transition-all text-left">
                        <i class="fa-solid fa-box-open"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3 text-left">Produk Digital (<a href="https://lynk.id/danyndeso" target="_blank" class="text-amber-600 hover:underline">lynk.id/danyndeso</a>)</h3>
                    <p class="text-gray-500 mb-4 text-left">Menyediakan berbagai template, dokumen, dan kebutuhan administrasi BUMDesa yang siap 
pakai, sehingga membantu pengurus dalam mempercepat pekerjaan tanpa harus membuat dari awal. </p>
                </div>

                <!-- Layanan 5 -->
                <div class="bg-white p-8 rounded-3xl shadow-sm hover:shadow-xl transition-shadow border border-gray-100 group">
                    <div class="w-14 h-14 bg-red-50 text-red-600 rounded-2xl flex items-center justify-center text-2xl mb-6 group-hover:scale-110 group-hover:bg-red-600 group-hover:text-white transition-all text-left">
                        <i class="fa-brands fa-youtube"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3 text-left">Edukasi Digital (<a href="https://www.youtube.com/@danyndeso" target="_blank" class="text-red-600 hover:underline">www.youtube.com/@danyndeso</a>)</h3>
                    <p class="text-gray-500 mb-4 text-left">Melalui kanal edukasi digital, kami menyediakan materi pembelajaran, tutorial, dan informasi 
praktis seputar pengelolaan BUMDesa untuk meningkatkan kapasitas dan pemahaman pengurus.</p>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
