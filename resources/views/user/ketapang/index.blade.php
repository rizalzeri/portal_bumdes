@extends('layouts.admin')
@section('title', 'Produk Ketahanan Pangan')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Produk Ketahanan Pangan</h2>
        <p class="text-gray-500 text-sm mt-1">Kelola data produk ketahanan pangan BUMDesa Anda.</p>
    </div>
    <button onclick="document.getElementById('modalTambah').classList.remove('hidden')"
        class="bg-primary text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-primary-700 transition flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> Tambah Produk
    </button>
</div>

@if(session('success'))
<div class="bg-green-100 text-green-700 border border-green-300 rounded-lg px-4 py-3 mb-4 text-sm">{{ session('success') }}</div>
@endif

<div class="bg-white rounded-xl shadow-sm border overflow-hidden">
    <table class="w-full text-sm text-left" id="tableKetapang">
        <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
            <tr>
                <th class="px-4 py-3">Nama Produk</th>
                <th class="px-4 py-3">Kategori</th>
                <th class="px-4 py-3">Harga</th>
                <th class="px-4 py-3">Foto</th>
                <th class="px-4 py-3 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($produks as $p)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-4 py-3 font-semibold text-gray-800">{{ $p->name }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $p->produkKetapangOption->name ?? '-' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $p->price ? 'Rp ' . number_format($p->price, 0, ',', '.') : '-' }}</td>
                <td class="px-4 py-3">
                    @if($p->image)
                        <img src="{{ Storage::url($p->image) }}" class="w-12 h-12 object-cover rounded-lg border" alt="foto">
                    @else
                        <span class="text-gray-400 text-xs">Tidak ada</span>
                    @endif
                </td>
                <td class="px-4 py-3 text-center">
                    <form action="{{ route('user.ketapang.destroy', $p) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-semibold px-2 py-1 rounded hover:bg-red-50 transition">
                            <i class="fa-solid fa-trash mr-1"></i>Hapus
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-4 py-10 text-center text-gray-400">Belum ada produk ketahanan pangan.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Modal Tambah -->
<div id="modalTambah" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg">
        <div class="flex justify-between items-center px-6 py-4 border-b">
            <h3 class="font-bold text-lg text-gray-800">Tambah Produk Ketahanan Pangan</h3>
            <button onclick="document.getElementById('modalTambah').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 text-xl">&times;</button>
        </div>
        <form action="{{ route('user.ketapang.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Produk <span class="text-red-500">*</span></label>
                <input type="text" name="name" required class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-primary focus:border-primary">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                <select name="produk_ketapang_option_id" required class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-primary focus:border-primary">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($optionKategori as $opt)
                        <option value="{{ $opt->id }}">{{ $opt->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Harga Estimasi</label>
                <input type="number" name="price" min="0" class="w-full border rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea name="description" rows="2" class="w-full border rounded-lg px-3 py-2 text-sm"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Foto Produk</label>
                <input type="file" name="image" accept="image/*" class="w-full text-sm border rounded-lg p-2">
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="document.getElementById('modalTambah').classList.add('hidden')" class="px-4 py-2 rounded-lg text-sm border text-gray-600 hover:bg-gray-50">Batal</button>
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary-700">Simpan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>$(document).ready(function() { $('#tableKetapang').DataTable({ language: { url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json' }}); });</script>
@endpush
@endsection
