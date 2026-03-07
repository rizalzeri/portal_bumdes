@extends('layouts.admin')
@section('title', 'Galeri Global')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Galeri Kegiatan</h2>
        <p class="text-gray-500 text-sm mt-1">Kelola foto dan video dokumentasi kegiatan BUMDesa tingkat .</p>
    </div>
    <button onclick="document.getElementById('modal-add').classList.remove('hidden')" class="bg-primary hover:bg-primary-900 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors shadow-sm">
        <i class="fa-solid fa-camera mr-2"></i> Tambah Media
    </button>
</div>

<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
    @forelse($galeris as $gal)
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden group">
        <div class="h-48 bg-gray-100 flex items-center justify-center relative overflow-hidden">
            @if($gal->type === 'photo' && $gal->image_url)
                <img src="{{ asset('storage/'.$gal->image_url) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
            @elseif($gal->type === 'video')
                <div class="w-full h-full relative">
                    <img src="https://img.youtube.com/vi/{{ \Illuminate\Support\Str::afterLast($gal->video_url, 'v=') }}/0.jpg" class="w-full h-full object-cover opacity-80" onerror="this.src='https://via.placeholder.com/400x300?text=Video'">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <i class="fa-solid fa-play text-4xl text-white opacity-80 drop-shadow-md"></i>
                    </div>
                </div>
            @endif
            
            <div class="absolute top-2 right-2 bg-black bg-opacity-70 text-white text-[10px] uppercase px-2 py-1 rounded font-bold">
                {{ $gal->type === 'photo' ? 'FOTO' : 'VIDEO' }}
            </div>
        </div>
        <div class="p-4 border-t flex flex-col justify-between">
            <h4 class="font-bold text-sm text-gray-900 line-clamp-2 mb-2" title="{{ $gal->title }}">{{ $gal->title }}</h4>
            <div class="flex justify-between items-center mt-2">
                <span class="text-[10px] text-gray-400">{{ $gal->created_at->format('d M Y') }}</span>
                <form action="{{ route('superadmin.galeri.destroy', $gal->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus media ini?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-1.5 rounded-md transition-colors tooltip"><i class="fa-solid fa-trash text-xs"></i></button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full border-2 border-dashed border-gray-300 rounded-xl p-12 text-center text-gray-400">
        <i class="fa-solid fa-images text-5xl mb-4 text-gray-300"></i>
        <p class="text-lg">Belum ada foto atau video di galeri.</p>
    </div>
    @endforelse
</div>

<!-- Add Modal -->
<div id="modal-add" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-xl bg-white">
        <div class="flex justify-between items-center mb-4 border-b pb-2">
            <h3 class="text-xl font-bold text-gray-900">Tambah Media Galeri</h3>
            <button onclick="document.getElementById('modal-add').classList.add('hidden')" class="text-gray-400 hover:text-gray-900 text-2xl font-bold">&times;</button>
        </div>
        <form action="{{ route('superadmin.galeri.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700">Judul Media</label>
                <input type="text" name="title" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Jenis Media</label>
                <select name="type" id="media-type" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2" onchange="toggleMediaType()">
                    <option value="photo">Foto (Upload)</option>
                    <option value="video">Video (URL Youtube)</option>
                </select>
            </div>
            
            <div id="div-photo">
                <label class="block text-sm font-medium text-gray-700">Pilih Foto</label>
                <input type="file" name="image_url" accept="image/*" class="mt-1 block w-full border text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white p-2 rounded-md">
                <p class="text-xs text-gray-500 mt-1">Format: JPG/PNG, Max: 5MB.</p>
            </div>

            <div id="div-video" class="hidden">
                <label class="block text-sm font-medium text-gray-700">Link Video Youtube</label>
                <input type="url" name="video_url" placeholder="https://youtube.com/watch?v=..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
            </div>
            
            <div class="pt-4 border-t flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modal-add').classList.add('hidden')" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Batal</button>
                <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-900">Upload</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleMediaType() {
        if(document.getElementById('media-type').value === 'video'){
            document.getElementById('div-photo').classList.add('hidden');
            document.getElementById('div-video').classList.remove('hidden');
        } else {
            document.getElementById('div-photo').classList.remove('hidden');
            document.getElementById('div-video').classList.add('hidden');
        }
    }
</script>
@endsection
