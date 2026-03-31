@extends('layouts.public')
@section('title', 'Tentang Kami')

@section('content')
<div class="bg-gray-50 pt-12 pb-24 text-center">
    <!-- Section: Tentang Kami -->
    <section class="py-20 bg-white relative overflow-hidden" id="tentang-kami">
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-64 h-64 rounded-full bg-blue-50 opacity-50"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16">
                <span class="text-accent font-bold tracking-wider uppercase text-sm">Portal BUMDesa</span>
                <h2 class="mt-2 text-3xl font-extrabold text-gray-900 sm:text-4xl">Tentang Kami</h2>
                <div class="mt-4 w-24 h-1 bg-accent mx-auto rounded-full"></div>
            </div>
            
            <div class="max-w-4xl mx-auto">
                <div class="space-y-6 text-gray-600 leading-relaxed text-lg text-center">
                    <p><strong class="text-primary">PortalBUMDes</strong> merupakan platform digital yang dirancang untuk mendukung pengelolaan dan pengembangan Badan Usaha Milik Desa (BUMDesa) secara modern, transparan, dan terintegrasi.</p>
                    
                    <p>Platform ini hadir sebagai solusi praktis bagi BUMDesa untuk memiliki website resmi secara gratis, tanpa memerlukan kemampuan teknis atau keahlian coding. Dengan demikian, setiap BUMDesa dapat dengan mudah menyampaikan informasi kepada masyarakat, meningkatkan transparansi, serta memperkuat kepercayaan publik.</p>
                    
                    <p>Selain sebagai sarana informasi publik, <strong class="text-primary">PortalBUMDes</strong> juga berfungsi sebagai alat bantu bagi pemerintah kabupaten dalam melakukan identifikasi, pemantauan, dan analisa data BUMDesa secara lebih efektif dan terstruktur. Data yang terintegrasi memungkinkan pengambilan kebijakan yang lebih tepat sasaran dalam pengembangan ekonomi desa.</p>
                    
                    <p><strong class="text-primary">PortalBUMDes</strong> dikembangkan oleh <strong class="text-accent">BumdesPro Official</strong>, sebuah inisiatif yang berfokus pada digitalisasi pengelolaan BUMDesa. Selain platform ini, BumdesPro juga menyediakan aplikasi pendukung melalui <strong class="text-accent">bumdespro.my.id</strong>, yang dirancang untuk mempermudah pengurus BUMDesa dalam:</p>
                    
                    <ul class="text-left max-w-md mx-auto space-y-2 bg-blue-50 p-6 rounded-2xl border-l-4 border-accent">
                        <li class="flex items-center gap-3">
                            <i class="fa-solid fa-check text-accent"></i>
                            <span>Menyusun laporan keuangan</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fa-solid fa-check text-accent"></i>
                            <span>Membuat SPJ (Surat Pertanggungjawaban) secara digital</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fa-solid fa-check text-accent"></i>
                            <span>Mengelola administrasi usaha desa secara lebih efisien</span>
                        </li>
                    </ul>

                    <p>Tidak hanya itu, BumdesPro juga menyediakan berbagai materi, panduan, serta template yang dibutuhkan oleh pengurus BUMDesa dalam menjalankan operasional dan tata kelola yang baik.</p>
                    
                    <p>Ke depan, <strong class="text-primary">PortalBUMDes</strong> akan terus berinovasi dan berkembang untuk memberikan layanan terbaik. Oleh karena itu, kritik dan saran dari pengguna sangat kami harapkan sebagai bagian dari proses penyempurnaan platform ini.</p>

                    <div class="p-8 bg-primary rounded-3xl mt-10 shadow-xl transform transition hover:scale-[1.02]">
                        <p class="text-white italic font-semibold text-xl leading-relaxed">"Kami juga mengajak seluruh pihak untuk mendukung dan meramaikan PortalBUMDes, sebagai upaya bersama dalam memajukan BUMDesa dan memperkuat ekonomi desa di seluruh Indonesia."</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
