@extends('layouts.admin')
@section('title', 'Struktur Personalia')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Personalia BUMDesa</h2>
        <p class="text-gray-500 text-sm mt-1">Daftarkan Kepala Desa (Penasihat), Direkturutama, Sekretaris, Bendahara, dan Pengawas BUMDesa.</p>
    </div>
    <button onclick="document.getElementById('modal-add').classList.remove('hidden')" class="bg-primary hover:bg-primary-900 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors shadow-sm">
        <i class="fa-solid fa-user-plus mr-2"></i> Tambah Pengurus
    </button>
</div>

<!-- Tampilan Struktur ala Card (lebih rapi untuk organigram list) -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    @forelse($pengurus as $p)
        <div class="bg-white rounded-xl shadow-sm border p-5 flex flex-col items-center text-center hover:shadow-md transition">
            <div class="relative mb-4">
                @if($p->photo)
                    <img src="{{ asset('storage/'.$p->photo) }}" class="w-24 h-24 rounded-full object-cover border-4 border-gray-100 shadow-sm">
                @else
                    <div class="w-24 h-24 rounded-full bg-gray-100 flex items-center justify-center border-4 border-white shadow-sm text-gray-400 text-3xl">
                        <i class="fa-solid fa-user-tie"></i>
                    </div>
                @endif
                <!-- Badge Jabatan Kecil -->
                <div class="absolute -bottom-2 -translate-x-1/2 left-1/2 bg-accent text-white text-[10px] uppercase font-bold px-2 py-0.5 rounded-full whitespace-nowrap shadow-sm border border-white">
                    {{ Str::limit($p->role, 15) }}
                </div>
            </div>
            
            <h3 class="font-bold text-gray-900 mt-2 line-clamp-1" title="{{ $p->name }}">{{ $p->name }}</h3>
            <p class="text-xs text-primary font-bold uppercase mb-2">{{ $p->role }}</p>
            
            @if($p->phone)
            <div class="text-xs text-gray-500 mb-3 bg-gray-50 px-2 py-1 rounded-md"><i class="fa-solid fa-phone mr-1"></i> {{ $p->phone }}</div>
            @endif
            
            <div class="mt-auto w-full pt-3 border-t flex justify-center gap-2">
                <button onclick="editPengurus({{ $p->id }}, '{{ addslashes($p->name) }}', '{{ addslashes($p->role) }}', '{{ addslashes($p->phone ?? '') }}')" class="text-xs text-white bg-yellow-500 hover:bg-yellow-600 px-3 py-1.5 rounded-md transition-colors font-medium">
                    <i class="fa-solid fa-pen mr-1"></i> Edit
                </button>
                <form action="{{ route('user.personalia.destroy', $p->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus profil pengurus ini?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-xs text-white bg-red-500 hover:bg-red-600 px-3 py-1.5 rounded-md transition-colors font-medium">
                        <i class="fa-solid fa-trash mr-1"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="col-span-full border-2 border-dashed border-gray-300 rounded-xl p-12 text-center text-gray-400">
            <i class="fa-solid fa-users text-5xl mb-4 text-gray-300"></i>
            <p class="text-lg">Struktur Organisasi (Personalia) BUMDesa belum diisi.</p>
        </div>
    @endforelse
</div>

<!-- Add Modal -->
<div id="modal-add" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-xl bg-white mb-20">
        <div class="flex justify-between items-center mb-4 border-b pb-2">
            <h3 class="text-xl font-bold text-gray-900">Tambah Anggota Pengurus</h3>
            <button onclick="document.getElementById('modal-add').classList.add('hidden')" class="text-gray-400 hover:text-gray-900 text-2xl font-bold">&times;</button>
        </div>
        <form action="{{ route('user.personalia.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700">Nama Lengkap & Gelar</label>
                <input type="text" name="name" required placeholder="Cth: Budi Santoso, S.E." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Jabatan / Peran</label>
                <input type="text" name="role" required placeholder="Cth: Direktur Utama / Penasihat (Kades)" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Foto Profil (Rekomendasi rasio 1:1 / Kotak)</label>
                <input type="file" name="photo" accept="image/*" class="mt-1 block w-full border text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white border-gray-300 rounded-md p-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Nomor Kontak (Opsional)</label>
                <input type="text" name="phone" placeholder="08..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
            </div>
            
            <div class="pt-4 border-t flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modal-add').classList.add('hidden')" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Batal</button>
                <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-900">Simpan Anggota</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="modal-edit" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-xl bg-white mb-20">
        <div class="flex justify-between items-center mb-4 border-b pb-2">
            <h3 class="text-xl font-bold text-gray-900">Ubah Data Pengurus</h3>
            <button onclick="document.getElementById('modal-edit').classList.add('hidden')" class="text-gray-400 hover:text-gray-900 text-2xl font-bold">&times;</button>
        </div>
        <form id="form-edit" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-700">Nama Lengkap & Gelar</label>
                <input type="text" name="name" id="edit-name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Jabatan / Peran</label>
                <input type="text" name="role" id="edit-role" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Ganti Foto (Kosongkan bila tidak diganti)</label>
                <input type="file" name="photo" accept="image/*" class="mt-1 block w-full border text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white border-gray-300 rounded-md p-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Nomor Kontak (Opsional)</label>
                <input type="text" name="phone" id="edit-phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
            </div>
            
            <div class="pt-4 border-t flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modal-edit').classList.add('hidden')" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Batal</button>
                <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-900">Perbarui</button>
            </div>
        </form>
    </div>
</div>

<script>
    function editPengurus(id, name, role, phone) {
        document.getElementById('form-edit').action = `/user/personalia/${id}`;
        document.getElementById('edit-name').value = name;
        document.getElementById('edit-role').value = role;
        document.getElementById('edit-phone').value = phone;
        document.getElementById('modal-edit').classList.remove('hidden');
    }
</script>
@endsection
