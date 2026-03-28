@extends('layouts.admin')
@section('title', 'Daftarkan Kecamatan')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Daftarkan Kecamatan</h2>
        <p class="text-gray-500 text-sm mt-1">Kelola data master kecamatan untuk Kabupaten Anda.</p>
    </div>
    <button onclick="document.getElementById('modal-add').classList.remove('hidden')" class="bg-primary hover:bg-primary-900 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors shadow-sm">
        <i class="fa-solid fa-plus mr-2"></i> Tambah Kecamatan
    </button>
</div>

<div class="bg-white rounded-xl shadow-sm border p-6">
    <div class="table-responsive w-full overflow-x-auto">
        <table class="datatable w-full whitespace-nowrap text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th class="px-6 py-3">Nama Kecamatan</th>
                    <th class="px-6 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kecamatans as $item)
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="px-6 py-4 font-bold text-gray-900">{{ $item->name }}</td>
                    <td class="px-6 py-4 text-right">
                        <form action="{{ route('adminkab.kecamatan.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus kecamatan ini?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-2 rounded-md transition-colors tooltip" title="Hapus">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Add Modal -->
<div id="modal-add" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center p-4">
    <div class="relative w-full max-w-lg shadow-2xl rounded-2xl bg-white border overflow-hidden">
        <div class="flex justify-between items-center p-5 border-b bg-gray-50">
            <h3 class="text-xl font-bold text-gray-900">Daftarkan Kecamatan Baru</h3>
            <button onclick="document.getElementById('modal-add').classList.add('hidden')" class="text-gray-400 hover:text-gray-900 text-2xl font-bold leading-none">&times;</button>
        </div>
        <form action="{{ route('adminkab.kecamatan.store') }}" method="POST" class="p-6 space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Kecamatan <span class="text-red-500">*</span></label>
                <input type="text" name="name" required placeholder="Contoh: Depok" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-primary focus:border-primary bg-white shadow-sm">
            </div>
            
            <div class="pt-4 border-t flex justify-end gap-3 mt-6">
                <button type="button" onclick="document.getElementById('modal-add').classList.add('hidden')" class="px-5 py-2.5 border rounded-lg text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-colors">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary-900 shadow-sm transition-colors">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
