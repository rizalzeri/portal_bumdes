@extends('layouts.public')
@section('title', 'Kontak')

@section('content')
<div class="bg-gray-50">
    <!-- Section: Kontak -->
    <section class="py-20 bg-primary relative overflow-hidden text-white h-[80vh] flex items-center" id="kontak">
        <!-- Decoration -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden opacity-10 pointer-events-none">
            <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full bg-white"></div>
            <div class="absolute top-1/2 right-10 w-64 h-64 rounded-full bg-accent"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div>
                    <span class="text-accent font-bold tracking-wider uppercase text-sm">Hubungi Kami</span>
                    <h2 class="mt-2 text-3xl font-extrabold text-white sm:text-5xl mb-6">Butuh Bantuan atau Ingin Bekerja Sama?</h2>
                    <p class="text-blue-100 text-lg mb-10 leading-relaxed">
                        Kami terbuka untuk komunikasi, kerja sama, serta dukungan dalam pengembangan BUMDesa. Tim kami siap membantu dan merespons setiap pertanyaan, masukan, maupun kebutuhan terkait layanan PortalBUMDes.
                    </p>
                    
                    <div class="space-y-6">
                        <a href="https://wa.me/6282247758730" target="_blank" class="flex items-center gap-6 group w-fit">
                            <div class="w-16 h-16 rounded-2xl bg-white/10 flex items-center justify-center text-accent text-3xl group-hover:bg-accent group-hover:text-primary transition-all">
                                <i class="fa-brands fa-whatsapp"></i>
                            </div>
                            <div class="text-left">
                                <p class="text-blue-200 text-sm mb-1 uppercase tracking-wider font-semibold">WhatsApp</p>
                                <p class="text-2xl font-bold group-hover:text-accent transition-colors">0822-4775-8730</p>
                            </div>
                        </a>
                        
                        <a href="mailto:dany.dwin@gmail.com" class="flex items-center gap-6 group w-fit">
                            <div class="w-16 h-16 rounded-2xl bg-white/10 flex items-center justify-center text-accent text-2xl group-hover:bg-accent group-hover:text-primary transition-all">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                            <div class="text-left">
                                <p class="text-blue-200 text-sm mb-1 uppercase tracking-wider font-semibold">Email</p>
                                <p class="text-xl font-bold group-hover:text-accent transition-colors">dany.dwin@gmail.com</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
