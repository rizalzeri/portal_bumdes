@extends('layouts.admin')
@section('title', 'Galeri BUMDesa')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Galeri Kegiatan</h2>
        <p class="text-gray-500 text-sm mt-1">Dokumentasi foto kegiatan BUMDesa Anda.</p>
    </div>
    <button onclick="document.getElementById('modalTambah').classList.remove('hidden')"
        class="bg-primary text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-primary-700 transition flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> Upload Foto
    </button>
</div>

@if(session('success'))
<div class="bg-green-100 text-green-700 border border-green-300 rounded-lg px-4 py-3 mb-4 text-sm">{{ session('success') }}</div>
@endif

<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
    @forelse($galeris as $g)
    <div class="bg-white rounded-xl border shadow-sm overflow-hidden group">
        <div class="relative overflow-hidden">
            <img src="{{ Storage::url($g->image) }}" alt="{{ $g->title }}" class="w-full h-40 object-cover group-hover:scale-105 transition duration-300">
        </div>
        <div class="p-3">
            <p class="font-semibold text-gray-800 text-sm truncate">{{ $g->title }}</p>
            @if($g->event_date)
            <p class="text-xs text-gray-400 mt-0.5"><i class="fa-solid fa-calendar mr-1"></i>{{ \Carbon\Carbon::parse($g->event_date)->format('d M Y') }}</p>
            @endif
            <form action="{{ route('user.galeri.destroy', $g) }}" method="POST" onsubmit="return confirm('Hapus foto ini?')" class="mt-2">
                @csrf @method('DELETE')
                <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-semibold">
                    <i class="fa-solid fa-trash mr-1"></i>Hapus
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="col-span-4 text-center py-16 text-gray-400">
        <i class="fa-solid fa-images text-5xl mb-3"></i>
        <p>Belum ada foto galeri. Upload foto kegiatan BUMDesa Anda!</p>
    </div>
    @endforelse
</div>

<!-- Modal Tambah -->
<div id="modalTambah" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg">
        <div class="flex justify-between items-center px-6 py-4 border-b">
            <h3 class="font-bold text-lg text-gray-800">Upload Foto Galeri</h3>
            <button onclick="document.getElementById('modalTambah').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 text-xl">&times;</button>
        </div>
        <form action="{{ route('user.galeri.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Judul Foto <span class="text-red-500">*</span></label>
                <input type="text" name="title" required class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-primary focus:border-primary">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">File Foto <span class="text-red-500">*</span></label>
                <input type="file" name="image" accept="image/*" required class="w-full text-sm border rounded-lg p-2">
                <p class="text-xs text-gray-400 mt-1">Maks. 4MB. Format: JPG, PNG, WEBP.</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kegiatan</label>
                <input type="date" name="event_date" class="w-full border rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                <textarea name="description" rows="2" class="w-full border rounded-lg px-3 py-2 text-sm"></textarea>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="document.getElementById('modalTambah').classList.add('hidden')" class="px-4 py-2 rounded-lg text-sm border text-gray-600 hover:bg-gray-50">Batal</button>
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary-700">Upload</button>
            </div>
        </form>
    </div>
</div>
@endsection
