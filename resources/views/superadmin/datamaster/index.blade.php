@extends('layouts.admin')
@section('title', 'Manajemen Data Master')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Manajemen Data Master</h2>
        <p class="text-gray-500 text-sm mt-1">Kelola opsi kategori untuk Unit Usaha, Produk, dan Ketahanan Pangan BUMDesa.</p>
    </div>
    <button onclick="document.getElementById('modal-add').classList.remove('hidden')" class="bg-primary hover:bg-primary-900 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors shadow-sm">
        <i class="fa-solid fa-plus mr-2"></i> Tambah Kategori
    </button>
</div>

<!-- Tabs mapping via Alpine -->
<div x-data="{ activeTab: 'unit_usaha' }">
    <div class="flex border-b border-gray-200 mb-6 space-x-4 overflow-x-auto pb-1">
        <button @click="activeTab = 'unit_usaha'" :class="{'border-accent text-primary font-bold border-b-2': activeTab === 'unit_usaha', 'text-gray-500 hover:text-gray-700': activeTab !== 'unit_usaha'}" class="whitespace-nowrap pb-3 px-2 text-sm md:text-base font-medium transition-colors">
            Kategori Unit Usaha
        </button>
        <button @click="activeTab = 'produk'" :class="{'border-accent text-primary font-bold border-b-2': activeTab === 'produk', 'text-gray-500 hover:text-gray-700': activeTab !== 'produk'}" class="whitespace-nowrap pb-3 px-2 text-sm md:text-base font-medium transition-colors">
            Kategori Produk BUMDes
        </button>
        <button @click="activeTab = 'ketapang'" :class="{'border-accent text-primary font-bold border-b-2': activeTab === 'ketapang', 'text-gray-500 hover:text-gray-700': activeTab !== 'ketapang'}" class="whitespace-nowrap pb-3 px-2 text-sm md:text-base font-medium transition-colors">
            Kategori Ketahanan Pangan
        </button>
        <button @click="activeTab = 'mitra'" :class="{'border-accent text-primary font-bold border-b-2': activeTab === 'mitra', 'text-gray-500 hover:text-gray-700': activeTab !== 'mitra'}" class="whitespace-nowrap pb-3 px-2 text-sm md:text-base font-medium transition-colors">
            Kategori Mitra Kerjasama
        </button>
    </div>

    <!-- Unit Usaha Tab -->
    <div x-show="activeTab === 'unit_usaha'" class="bg-white rounded-xl shadow-sm border p-6">
        <div class="table-responsive w-full overflow-x-auto">
            <table class="datatable w-full whitespace-nowrap text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr><th class="px-6 py-3">Ikon & Nama Kategori</th><th class="px-6 py-3 text-right">Aksi</th></tr>
                </thead>
                <tbody>
                    @foreach($unitUsahaOptions as $opt)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-900 group flex items-center">
                            @if($opt->image)
                                <img src="{{ asset('storage/'.$opt->image) }}" class="w-8 h-8 rounded object-cover mr-3 border" alt="img">
                            @else
                                <div class="w-8 h-8 rounded bg-gray-100 flex items-center justify-center mr-3 border text-gray-400"><i class="fa-solid fa-image"></i></div>
                            @endif
                            <span class="data-editable underline-offset-4 decoration-dashed decoration-gray-300 hover:underline cursor-text focus:outline-none focus:bg-yellow-50 focus:ring-1 focus:ring-primary px-1 rounded transition" data-id="{{ $opt->id }}" data-type="unit_usaha" contenteditable="true" title="Klik ganda untuk mengedit langsung">{{ $opt->name }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button onclick="editData('unit_usaha', {{ $opt->id }}, '{{ addslashes($opt->name) }}')" class="text-accent hover:text-yellow-600 bg-yellow-50 hover:bg-yellow-100 p-2 rounded-md transition-colors mr-1"><i class="fa-solid fa-pen"></i></button>
                            <form action="{{ route('superadmin.datamaster.destroy', $opt->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin hapus opsi ini?');">
                                @csrf @method('DELETE') <input type="hidden" name="type" value="unit_usaha">
                                <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-2 rounded-md transition-colors"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Produk BUMDes Tab -->
    <div x-show="activeTab === 'produk'" style="display: none;" class="bg-white rounded-xl shadow-sm border p-6">
        <div class="table-responsive w-full overflow-x-auto">
            <table class="datatable w-full whitespace-nowrap text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr><th class="px-6 py-3">Ikon & Nama Kategori</th><th class="px-6 py-3 text-right">Aksi</th></tr>
                </thead>
                <tbody>
                    @foreach($produkOptions as $opt)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-900 group flex items-center">
                            @if($opt->image)
                                <img src="{{ asset('storage/'.$opt->image) }}" class="w-8 h-8 rounded object-cover mr-3 border" alt="img">
                            @else
                                <div class="w-8 h-8 rounded bg-gray-100 flex items-center justify-center mr-3 border text-gray-400"><i class="fa-solid fa-image"></i></div>
                            @endif
                            <span class="data-editable underline-offset-4 decoration-dashed decoration-gray-300 hover:underline cursor-text focus:outline-none focus:bg-yellow-50 focus:ring-1 focus:ring-primary px-1 rounded transition" data-id="{{ $opt->id }}" data-type="produk" contenteditable="true" title="Klik ganda untuk mengedit langsung">{{ $opt->name }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button onclick="editData('produk', {{ $opt->id }}, '{{ addslashes($opt->name) }}')" class="text-accent hover:text-yellow-600 bg-yellow-50 hover:bg-yellow-100 p-2 rounded-md transition-colors mr-1"><i class="fa-solid fa-pen"></i></button>
                            <form action="{{ route('superadmin.datamaster.destroy', $opt->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin hapus opsi ini?');">
                                @csrf @method('DELETE') <input type="hidden" name="type" value="produk">
                                <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-2 rounded-md transition-colors"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Ketapang Tab -->
    <div x-show="activeTab === 'ketapang'" style="display: none;" class="bg-white rounded-xl shadow-sm border p-6">
        <div class="table-responsive w-full overflow-x-auto">
            <table class="datatable w-full whitespace-nowrap text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr><th class="px-6 py-3">Ikon & Nama Kategori</th><th class="px-6 py-3 text-right">Aksi</th></tr>
                </thead>
                <tbody>
                    @foreach($ketapangOptions as $opt)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-900 group flex items-center">
                            @if($opt->image)
                                <img src="{{ asset('storage/'.$opt->image) }}" class="w-8 h-8 rounded object-cover mr-3 border" alt="img">
                            @else
                                <div class="w-8 h-8 rounded bg-gray-100 flex items-center justify-center mr-3 border text-gray-400"><i class="fa-solid fa-image"></i></div>
                            @endif
                            <span class="data-editable underline-offset-4 decoration-dashed decoration-gray-300 hover:underline cursor-text focus:outline-none focus:bg-yellow-50 focus:ring-1 focus:ring-primary px-1 rounded transition" data-id="{{ $opt->id }}" data-type="ketapang" contenteditable="true" title="Klik ganda untuk mengedit langsung">{{ $opt->name }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button onclick="editData('ketapang', {{ $opt->id }}, '{{ addslashes($opt->name) }}')" class="text-accent hover:text-yellow-600 bg-yellow-50 hover:bg-yellow-100 p-2 rounded-md transition-colors mr-1"><i class="fa-solid fa-pen"></i></button>
                            <form action="{{ route('superadmin.datamaster.destroy', $opt->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin hapus opsi ini?');">
                                @csrf @method('DELETE') <input type="hidden" name="type" value="ketapang">
                                <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-2 rounded-md transition-colors"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mitra Tab -->
    <div x-show="activeTab === 'mitra'" style="display: none;" class="bg-white rounded-xl shadow-sm border p-6">
        <div class="table-responsive w-full overflow-x-auto">
            <table class="datatable w-full whitespace-nowrap text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr><th class="px-6 py-3">Ikon & Nama Kategori</th><th class="px-6 py-3 text-right">Aksi</th></tr>
                </thead>
                <tbody>
                    @foreach($mitraOptions as $opt)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-900 group flex items-center">
                            @if($opt->image)
                                <img src="{{ asset('storage/'.$opt->image) }}" class="w-8 h-8 rounded object-cover mr-3 border" alt="img">
                            @else
                                <div class="w-8 h-8 rounded bg-gray-100 flex items-center justify-center mr-3 border text-gray-400"><i class="fa-solid fa-image"></i></div>
                            @endif
                            <span class="data-editable underline-offset-4 decoration-dashed decoration-gray-300 hover:underline cursor-text focus:outline-none focus:bg-yellow-50 focus:ring-1 focus:ring-primary px-1 rounded transition" data-id="{{ $opt->id }}" data-type="mitra" contenteditable="true" title="Klik ganda untuk mengedit langsung">{{ $opt->name }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button onclick="editData('mitra', {{ $opt->id }}, '{{ addslashes($opt->name) }}')" class="text-accent hover:text-yellow-600 bg-yellow-50 hover:bg-yellow-100 p-2 rounded-md transition-colors mr-1"><i class="fa-solid fa-pen"></i></button>
                            <form action="{{ route('superadmin.datamaster.destroy', $opt->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin hapus opsi ini?');">
                                @csrf @method('DELETE') <input type="hidden" name="type" value="mitra">
                                <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-2 rounded-md transition-colors"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div id="modal-add" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-xl bg-white">
        <div class="flex justify-between items-center mb-4 border-b pb-2">
            <h3 class="text-xl font-bold text-gray-900">Tambah Data Kategori</h3>
            <button onclick="document.getElementById('modal-add').classList.add('hidden')" class="text-gray-400 hover:text-gray-900 text-2xl font-bold">&times;</button>
        </div>
        <form action="{{ route('superadmin.datamaster.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700">Tipe Data Master</label>
                <select name="type" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
                    <option value="unit_usaha">Kategori Unit Usaha</option>
                    <option value="produk">Kategori Produk BUMDes</option>
                    <option value="ketapang">Kategori Produk Ketahanan Pangan</option>
                    <option value="mitra">Kategori Mitra Kerjasama</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                <input type="text" name="name" required placeholder="Cth: Pertanian & Perkebunan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Gambar (Opsional)</label>
                <input type="file" name="image" accept="image/*" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
                <p class="text-xs text-gray-500 mt-1">Upload gambar kategori, max 2MB.</p>
            </div>
            
            <div class="pt-4 border-t flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modal-add').classList.add('hidden')" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Batal</button>
                <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-900">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="modal-edit" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-xl bg-white">
        <div class="flex justify-between items-center mb-4 border-b pb-2">
            <h3 class="text-xl font-bold text-gray-900">Ubah Data Kategori</h3>
            <button onclick="document.getElementById('modal-edit').classList.add('hidden')" class="text-gray-400 hover:text-gray-900 text-2xl font-bold">&times;</button>
        </div>
        <form id="form-edit" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')
            <input type="hidden" name="type" id="edit-type">
            <div>
                <label class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                <input type="text" name="name" id="edit-name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Ubah Gambar (Biarkan kosong jika tidak diubah)</label>
                <input type="file" name="image" accept="image/*" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
            </div>
            
            <div class="pt-4 border-t flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modal-edit').classList.add('hidden')" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Batal</button>
                <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-900">Perbarui</button>
            </div>
        </form>
    </div>
</div>

<script>
    function editData(type, id, name) {
        document.getElementById('form-edit').action = `/superadmin/datamaster/${id}`;
        document.getElementById('edit-type').value = type;
        document.getElementById('edit-name').value = name;
        document.getElementById('modal-edit').classList.remove('hidden');
    }

    // Inline Editing Logic (AJAX Auto-Save)
    document.querySelectorAll('.data-editable').forEach(cell => {
        let originalText = '';
        
        cell.addEventListener('focus', function() {
            originalText = this.innerText.trim();
        });

        cell.addEventListener('blur', function() {
            let newText = this.innerText.trim();
            
            if (newText !== originalText && newText !== '') {
                // Trigger AJAX save
                let id = this.getAttribute('data-id');
                let type = this.getAttribute('data-type');
                
                fetch(`/superadmin/datamaster/inline-update/${id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        type: type,
                        name: newText
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        originalText = newText;
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Data berhasil diperbarui (AJAX)',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    } else {
                        Swal.fire('Gagal', 'Terjadi kesalahan.', 'error');
                        this.innerText = originalText;
                    }
                })
                .catch(err => {
                    console.error(err);
                    this.innerText = originalText;
                });
            } else if (newText === '') {
                this.innerText = originalText; // Revert if empty
            }
        });

        // Enable enter key to blur/save instead of parsing new line
        cell.addEventListener('keydown', function(evt) {
            if (evt.keyCode === 13) {
                evt.preventDefault();
                this.blur();
            }
        });
    });
</script>
@endsection
