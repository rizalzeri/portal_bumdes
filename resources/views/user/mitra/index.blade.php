@extends('layouts.admin')
@section('title', 'Mitra Kerjasama')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Mitra Kerjasama</h2>
            <p class="text-gray-500 text-sm mt-1">Daftar mitra kerjasama strategis BUMDesa {{ $bumdes->name }}.</p>
        </div>
        @premium('mitra', 'create')
        <button onclick="document.getElementById('modalTambah').classList.remove('hidden')"
            class="bg-primary text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-primary-700 transition flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Tambah Mitra
        </button>
        @endpremium
    </div>

    @if (session('success'))
        <div class="bg-green-100 text-green-700 border border-green-300 rounded-lg px-4 py-3 mb-4 text-sm">
            {{ session('success') }}</div>
    @endif

    @if ($mitras->isEmpty())
        <div class="bg-white rounded-xl shadow-sm border p-16 flex flex-col items-center justify-center text-center">
            <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mb-4">
                <i class="fa-solid fa-handshake text-3xl text-blue-400"></i>
            </div>
            <h3 class="font-bold text-gray-800 text-lg mb-1">Belum Ada Mitra</h3>
            <p class="text-gray-500 text-sm mb-4">Tambahkan mitra kerjasama strategis BUMDesa Anda.</p>
            @premium('mitra', 'create')
            <button onclick="document.getElementById('modalTambah').classList.remove('hidden')"
                class="bg-primary text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-primary-700">
                <i class="fa-solid fa-plus mr-1"></i> Tambah Mitra Pertama
            </button>
            @endpremium
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($mitras as $m)
                <div class="bg-white rounded-xl border shadow-sm p-5 flex gap-4 items-start hover:shadow-md transition relative">
                    <div class="absolute top-3 right-3 flex space-x-2">
                        @if($m->is_active)
                            <span class="bg-green-100 text-green-800 text-[10px] font-bold px-2 py-0.5 rounded-full border border-green-200">Aktif</span>
                        @else
                            <span class="bg-red-100 text-red-800 text-[10px] font-bold px-2 py-0.5 rounded-full border border-red-200">Tidak Aktif</span>
                        @endif
                    </div>
                
                    @if ($m->logo)
                        <img src="{{ asset('storage/' . $m->logo) }}"
                            class="w-14 h-14 object-contain border rounded-lg bg-gray-50 p-1 shrink-0"
                            alt="{{ $m->name }}">
                    @else
                        <div
                            class="w-14 h-14 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400 shrink-0">
                            <i class="fa-solid fa-building text-xl"></i>
                        </div>
                    @endif
                    <div class="flex-1 min-w-0 pr-12">
                        <h3 class="font-bold text-gray-800">{{ $m->name }}</h3>
                        <p class="text-xs text-gray-400 font-semibold mb-1">
                            Kategori: {{ $m->mitraOption->name ?? $m->name }}
                        </p>
                        @if ($m->description)
                            <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $m->description }}</p>
                        @endif
                        <div class="flex gap-2 mt-3">
                            @premium('mitra', 'update')
                            <button
                                onclick="openEditModal({{ $m->id }}, {{ $m->mitra_option_id ?? 'null' }}, '{{ addslashes($m->description) }}', {{ $m->is_active ? 1 : 0 }})"
                                class="text-xs text-blue-600 font-semibold hover:underline">
                                <i class="fa-solid fa-pen mr-1"></i>Edit
                            </button>
                            @endpremium

                            @premium('mitra', 'delete')
                            <form action="{{ route('user.mitra.destroy', ['slug' => auth()->user()->username, 'mitra' => $m->id]) }}" method="POST"
                                onsubmit="return confirm('Hapus mitra ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs text-red-500 font-semibold hover:underline">
                                    <i class="fa-solid fa-trash mr-1"></i>Hapus
                                </button>
                            </form>
                            @endpremium
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Modal Tambah -->
    <div id="modalTambah" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg">
            <div class="flex justify-between items-center px-6 py-4 border-b">
                <h3 class="font-bold text-lg text-gray-800">Tambah Mitra Kerjasama</h3>
                <button onclick="document.getElementById('modalTambah').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-600 text-xl">&times;</button>
            </div>
            <form action="{{ route('user.mitra.store', ['slug' => auth()->user()->username]) }}" method="POST" enctype="multipart/form-data"
                class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori Mitra <span
                            class="text-red-500">*</span></label>
                    <select name="mitra_option_id" required class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-primary focus:border-primary">
                        <option value="">-- Pilih Jenis/Kategori Mitra --</option>
                        @foreach($options as $opt)
                            <option value="{{ $opt->id }}">{{ $opt->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Kemitraan <span
                            class="text-red-500">*</span></label>
                    <select name="is_active" required class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-primary focus:border-primary">
                        <option value="1">Aktif</option>
                        <option value="0">Tidak Aktif</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi / Keterangan Partner</label>
                    <textarea name="description" rows="3" class="w-full border rounded-lg px-3 py-2 text-sm" placeholder="Contoh: Bekerja sama dengan PT Mandiri dalam pengelolaan produk"></textarea>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="document.getElementById('modalTambah').classList.add('hidden')"
                        class="px-4 py-2 rounded-lg text-sm border text-gray-600 hover:bg-gray-50">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit -->
    <div id="modalEdit" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg">
            <div class="flex justify-between items-center px-6 py-4 border-b">
                <h3 class="font-bold text-lg text-gray-800">Edit Mitra Kerjasama</h3>
                <button onclick="document.getElementById('modalEdit').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-600 text-xl">&times;</button>
            </div>
            <form id="editForm" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori Mitra <span
                            class="text-red-500">*</span></label>
                    <select name="mitra_option_id" id="editKategori" required class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-primary focus:border-primary">
                        <option value="">-- Pilih Jenis/Kategori Mitra --</option>
                        @foreach($options as $opt)
                            <option value="{{ $opt->id }}">{{ $opt->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Kemitraan <span
                            class="text-red-500">*</span></label>
                    <select name="is_active" id="editStatus" required class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-primary focus:border-primary">
                        <option value="1">Aktif</option>
                        <option value="0">Tidak Aktif</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi / Keterangan Partner</label>
                    <textarea name="description" id="editDesc" rows="3" class="w-full border rounded-lg px-3 py-2 text-sm"></textarea>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="document.getElementById('modalEdit').classList.add('hidden')"
                        class="px-4 py-2 rounded-lg text-sm border text-gray-600">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-semibold">Update</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            function openEditModal(id, option_id, desc, is_active) {
                if(option_id) document.getElementById('editKategori').value = option_id;
                document.getElementById('editStatus').value = is_active;
                document.getElementById('editDesc').value = desc;
                const slug = '{{ auth()->user()->username }}';
                document.getElementById('editForm').action = '/' + slug + '/mitra/' + id;
                document.getElementById('modalEdit').classList.remove('hidden');
            }
        </script>
    @endpush
@endsection
