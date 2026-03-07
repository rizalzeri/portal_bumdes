@extends('layouts.public')
@section('title', $title)

@section('content')
<div class="bg-primary pt-16 pb-32 text-center text-white relative overflow-hidden">
    <div class="absolute inset-0 bg-primary mix-blend-multiply opacity-90"><i class="fa-solid fa-shapes text-white opacity-5 text-9xl absolute -top-10 -left-10 transform -rotate-12"></i></div>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <h1 class="text-4xl font-extrabold tracking-tight sm:text-5xl mb-4">{{ $title }}</h1>
        <p class="text-xl text-blue-200">{{ $desc }}</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-20 relative z-20 mb-20">
    <div class="bg-white rounded-2xl shadow-2xl p-12 text-center border-t-4 border-accent min-h-[50vh] flex flex-col items-center justify-center">
        <div class="w-32 h-32 bg-gray-50 rounded-full flex items-center justify-center mb-8 border-4 border-dashed border-gray-200">
            <i class="fa-solid fa-person-digging text-6xl text-gray-400"></i>
        </div>
        <h2 class="text-3xl font-bold text-gray-900 mb-4">Halaman Sedang Dalam Pengembangan</h2>
        <p class="text-gray-500 text-lg max-w-2xl mx-auto mb-8">Fitur untuk <strong>{{ $title }}</strong> sedang dipersiapkan untuk menyediakan pengalaman terbaik bagi ekosistem BUMDesa secara.</p>
        
        <a href="{{ route('home') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-primary hover:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Beranda
        </a>
    </div>
</div>
@endsection
