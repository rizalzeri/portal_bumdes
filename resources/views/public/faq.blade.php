@extends('layouts.public')
@section('title', 'FAQ')

@section('content')
<div class="bg-white pt-12 text-center pb-24 text-left">
    <!-- Section: FAQ -->
    <section class="py-20 bg-white" id="faq">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-left">
            <div class="text-center mb-16">
                <span class="text-accent font-bold tracking-wider uppercase text-sm">Pertanyaan</span>
                <h2 class="mt-2 text-3xl font-extrabold text-gray-900 sm:text-4xl">FAQ (Frequently Asked Questions)</h2>
                <div class="mt-4 w-24 h-1 bg-accent mx-auto rounded-full"></div>
            </div>

            <div class="space-y-4" x-data="{ active: null }">
                @php
                    $faqs = [
                        ['q' => 'Apa itu PortalBUMDes?', 'a' => 'PortalBUMDes adalah platform digital yang membantu BUMDesa memiliki website resmi, serta memudahkan pengelolaan data dan informasi secara terintegrasi.'],
                        ['q' => 'Apakah BUMDesa harus bisa coding?', 'a' => 'Tidak. PortalBUMDes dirancang agar mudah digunakan tanpa kemampuan coding, sehingga pengurus BUMDesa dapat langsung mengelola website dan data secara mandiri.'],
                        ['q' => 'Apakah layanan PortalBUMDes berbayar?', 'a' => 'Untuk pembuatan dan pengelolaan website BUMDesa, layanan ini disediakan secara gratis. Beberapa paket atau layanan tambahan dapat bersifat opsional.'],
                        ['q' => 'Apa manfaat PortalBUMDes bagi pemerintah kabupaten?', 'a' => 'PortalBUMDes membantu monitoring perkembangan BUMDesa, identifikasi data secara cepat, dan analisa kinerja BUMDesa berbasis data.'],
                        ['q' => 'Apa itu BumdesPro?', 'a' => 'BumdesPro adalah pengembang PortalBUMDes yang juga menyediakan aplikasi tambahan: Pengelolaan & Penyusunan laporan keuangan (bumdespro.my.id) dan Pembuatan SPJ digital.'],
                        ['q' => 'Bagaimana cara mendaftar atau mendapatkan akun?', 'a' => 'Akun biasanya diberikan melalui admin kabupaten atau pihak terkait. Anda juga dapat menghubungi kontak yang tersedia untuk informasi lebih lanjut.'],
                        ['q' => 'Apakah bisa mengunggah dokumen laporan?', 'a' => 'Ya. BUMDesa dapat mengunggah atau mengirimkan dokumen laporan melalui link (misalnya Google Drive) yang kemudian dapat dipantau dari admin.'],
                        ['q' => 'Apakah tersedia panduan pengguna?', 'a' => 'Ya. Kami menyediakan berbagai materi, template, dan tutorial yang dapat diakses melalui kanal edukasi digital.'],
                        ['q' => 'Bagaimana jika mengalami kendala?', 'a' => 'Silakan hubungi kami melalui WhatsApp, email, atau kanal YouTube. Tim kami siap membantu memberikan solusi.'],
                    ];
                @endphp

                @foreach ($faqs as $i => $faq)
                    <div class="border-2 border-gray-100 rounded-2xl overflow-hidden hover:border-blue-100 transition-colors">
                        <button 
                            @click="active === {{ $i }} ? active = null : active = {{ $i }}"
                            class="w-full px-6 py-5 text-left flex justify-between items-center bg-white hover:bg-gray-50 transition-colors"
                        >
                            <span class="font-bold text-gray-900 flex gap-4 pr-4">
                                <span class="text-accent">{{ $i + 1 }}.</span>
                                {{ $faq['q'] }}
                            </span>
                            <span class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-primary shrink-0 transition-transform duration-300 transform" :class="{'rotate-180': active === {{ $i }}}">
                                <i class="fa-solid fa-chevron-down text-sm"></i>
                            </span>
                        </button>
                        <div x-show="active === {{ $i }}" x-collapse>
                            <div class="px-6 pb-6 pt-2 text-gray-600 leading-relaxed border-t border-gray-50 pl-14">
                                {{ $faq['a'] }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</div>
@endsection
