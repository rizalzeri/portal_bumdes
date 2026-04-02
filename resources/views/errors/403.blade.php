@extends('layouts.public')
@section('title', 'Akses Terbatas')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center px-4 py-16">
    <div class="text-center max-w-lg mx-auto">
        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6 border-4 border-gray-200">
            <i class="fa-solid fa-lock text-gray-400 text-4xl"></i>
        </div>
        <h1 class="text-5xl font-black text-gray-200 mb-2 tracking-tight">403</h1>
        <h2 class="text-xl font-bold text-gray-800 mb-3">Akses Terbatas</h2>
        <p class="text-gray-500 mb-8 leading-relaxed">
            {{ $exception->getMessage() ?: 'Perkembangan BUMDesa tidak tersedia (Status Admin Kabupaten Offline).' }}
        </p>
        <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('home') }}"
            class="inline-flex items-center gap-2 bg-primary text-white px-6 py-3 rounded-xl font-bold hover:bg-primary/90 transition shadow-md">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>
@endsection
