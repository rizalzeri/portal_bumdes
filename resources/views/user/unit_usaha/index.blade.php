@extends('layouts.admin')
@section('title', 'Unit Usaha BUMDesa')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Manajemen Unit Usaha</h2>
        <p class="text-gray-500 text-sm mt-1">Daftarkan dan kelola seluruh unit usaha yang dijalankan oleh BUMDesa Anda.</p>
    </div>
    <button onclick="document.getElementById('modal-add').classList.remove('hidden')" class="bg-primary hover:bg-primary-900 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors shadow-sm">
        <i class="fa-solid fa-store mr-2"></i> Tambah Unit Usaha
    </button>
</div>

<div class="bg-white rounded-xl shadow-sm border p-6">
    <div class="table-responsive w-full overflow-x-auto">
        <table class="datatable w-full whitespace-nowrap text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th class="px-6 py-3">Kategori Sektor</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($units as $u)
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <span class="bg-blue-100 text-blue-800 text-[10px] font-bold px-4 py-1.5 rounded-full border border-blue-200 uppercase">{{ $u->sektor }}</span>
                    </td>
                    <td class="px-6 py-4">
                        @if($u->status === 'active')
                            <span class="text-green-600 font-bold"><i class="fa-solid fa-circle-check"></i> Aktif Beroperasi</span>
                        @else
                            <span class="text-gray-400 font-bold"><i class="fa-solid fa-circle-xmark"></i> Tidak Aktif</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <button onclick="editUnit({{ $u->toJson() }})" class="text-accent hover:text-yellow-600 bg-yellow-50 hover:bg-yellow-100 p-2 rounded-md transition-colors mr-1 tooltip" title="Edit Unit Usaha">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <form action="{{ route('user.unit_usaha.destroy', $u->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus unit usaha ini?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-2 rounded-md transition-colors tooltip" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Add Modal -->
<div id="modal-add" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-xl bg-white mb-20">
        <div class="flex justify-between items-center mb-4 border-b pb-2">
            <h3 class="text-xl font-bold text-gray-900">Tambah Unit Usaha</h3>
            <button onclick="document.getElementById('modal-add').classList.add('hidden')" class="text-gray-400 hover:text-gray-900 text-2xl font-bold">&times;</button>
        </div>
        <form action="{{ route('user.unit_usaha.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700">Pilih Kategori Sektor</label>
                <select name="sektor" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
                    <option value="">-- Pilih Sektor --</option>
                    @foreach($kategoriOptions as $opt)
                        <option value="{{ $opt->name }}">{{ $opt->name }}</option>
                    @endforeach
                    <option value="Lainnya">Lainnya...</option>
                </select>
                <input type="hidden" name="name" value="Unit Usaha">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Status Operasional</label>
                <select name="status" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
                    <option value="active">Aktif</option>
                    <option value="inactive">Tidak Aktif</option>
                </select>
            </div>
            
            <div class="pt-4 border-t flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modal-add').classList.add('hidden')" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Batal</button>
                <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-900">Simpan Unit</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="modal-edit" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-xl bg-white mb-20">
        <div class="flex justify-between items-center mb-4 border-b pb-2">
            <h3 class="text-xl font-bold text-gray-900">Ubah Data Unit Usaha</h3>
            <button onclick="document.getElementById('modal-edit').classList.add('hidden')" class="text-gray-400 hover:text-gray-900 text-2xl font-bold">&times;</button>
        </div>
        <form id="form-edit" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <input type="hidden" name="name" id="edit-name">
            <div>
                <label class="block text-sm font-medium text-gray-700">Kategori Sektor</label>
                <select name="sektor" id="edit-sektor" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
                    <option value="">-- Pilih Sektor --</option>
                    @foreach($kategoriOptions as $opt)
                        <option value="{{ $opt->name }}">{{ $opt->name }}</option>
                    @endforeach
                    <option value="Lainnya">Lainnya...</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Status Operasional</label>
                <select name="status" id="edit-status" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
                    <option value="active">Aktif</option>
                    <option value="inactive">Tidak Aktif</option>
                </select>
            </div>
            
            <div class="pt-4 border-t flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modal-edit').classList.add('hidden')" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Batal</button>
                <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-900">Perbarui</button>
            </div>
        </form>
    </div>
</div>

<script>
    function editUnit(unit) {
        document.getElementById('form-edit').action = `/user/unit-usaha/${unit.id}`;
        document.getElementById('edit-name').value = unit.name;
        // Check if unit.sektor exists in options, else set to Lainnya
        const sektorSelect = document.getElementById('edit-sektor');
        let optionExists = false;
        for (let i = 0; i < sektorSelect.options.length; i++) {
            if (sektorSelect.options[i].value === unit.sektor) {
                optionExists = true;
                break;
            }
        }
        if (!optionExists && unit.sektor) {
            // Add it dynamically if it was customized or kept as "Lainnya" but we want to show its text
            const newOption = new Option(unit.sektor, unit.sektor);
            sektorSelect.add(newOption);
        }
        sektorSelect.value = unit.sektor;
        
        document.getElementById('edit-tahun').value = unit.tahun_berdiri || '';
        document.getElementById('edit-status').value = unit.status;
        document.getElementById('edit-desc').value = unit.deskripsi || '';
        document.getElementById('modal-edit').classList.remove('hidden');
    }
</script>
@endsection
