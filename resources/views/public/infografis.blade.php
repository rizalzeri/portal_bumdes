@extends('layouts.public')
@section('title', 'Katalog Infografis Kabupaten')

@section('content')
    <div class="bg-primary pt-12 pb-24 text-center">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-extrabold text-white tracking-tight sm:text-5xl uppercase italic">Infografis Kabupaten
            </h1>
            <p class="mt-4 max-w-2xl text-xl text-blue-200 mx-auto">Pantau perkembangan dan capaian kinerja BUMDesa di setiap
                kabupaten.</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-16 relative z-10 mb-20">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            @forelse($kabupatens as $kb)
                <div
                    class="bg-white rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition-all group flex flex-col border border-gray-100">
                    <div class="h-32 bg-gradient-to-br from-primary to-blue-800 p-6 flex flex-col justify-end relative">
                        <div
                            class="absolute top-4 right-4 w-12 h-12 bg-white/10 rounded-full flex items-center justify-center backdrop-blur-sm group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-chart-line text-white/50 text-xl"></i>
                        </div>
                        <span
                            class="text-[10px] font-bold text-blue-300 uppercase tracking-widest mb-1">{{ $kb->province->name ?? 'Provinsi' }}</span>
                        <h3 class="text-xl font-black text-white leading-tight uppercase">{{ $kb->name }}</h3>
                    </div>

                    <div class="p-6 flex-grow flex flex-col justify-between">
                        <div class="space-y-4">
                            <div
                                class="flex items-center justify-between p-3 bg-gray-50 rounded-2xl border border-gray-100 italic">
                                <span class="text-xs font-bold text-gray-500 uppercase tracking-tighter">BUMDes
                                    Terverifikasi</span>
                                <span class="text-lg font-black text-primary">{{ number_format($kb->bumdes_count) }}</span>
                            </div>
                        </div>

                        <div class="mt-8">
                            <a href="{{ route('public.infografis.kabupaten', $kb->id) }}"
                                class="w-full flex items-center justify-center px-6 py-4 bg-primary text-white text-sm font-black uppercase tracking-widest rounded-2xl hover:bg-primary-900 transition-colors shadow-lg shadow-primary/20">
                                Lihat Infografis <i
                                    class="fa-solid fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center">
                    <div
                        class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                        <i class="fa-solid fa-map-location-dot text-3xl"></i>
                    </div>
                    <h4 class="text-lg font-bold text-gray-500">Data Kabupaten Belum Tersedia</h4>
                    <p class="text-gray-400">Silakan hubungi administrator pusat untuk integrasi data kabupaten.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
