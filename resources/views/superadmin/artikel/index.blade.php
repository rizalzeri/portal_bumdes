@extends('layouts.admin')
@section('title', 'Manajemen Artikel Global')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Artikel, Berita & Opini</h2>
            <p class="text-gray-500 text-sm mt-1">Publikasi artikel dan berita resmi yang tampil di halaman portal.</p>
        </div>
        <button onclick="document.getElementById('modal-add').classList.remove('hidden')"
            class="bg-primary hover:bg-primary-900 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors shadow-sm">
            <i class="fa-solid fa-pen-nib mr-2"></i> Tulis Artikel
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm border p-6">
        <div class="table-responsive w-full overflow-x-auto">
            <table class="datatable w-full whitespace-nowrap text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th class="px-6 py-3">Gambar</th>
                        <th class="px-6 py-3">Sumber / BUMDes</th>
                        <th class="px-6 py-3">Judul & Kategori</th>
                        <th class="px-6 py-3">Tanggal Dibuat</th>
                        <th class="px-6 py-3 text-center">Unggulan (Home)</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($artikels as $art)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4">
                                @if ($art->image)
                                    <img src="{{ asset('storage/' . $art->image) }}"
                                        class="w-16 h-12 object-cover rounded shadow-sm border">
                                @else
                                    <div class="w-16 h-12 bg-gray-100 flex items-center justify-center rounded border"><i
                                            class="fa-solid fa-image text-gray-400"></i></div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if ($art->bumdes_id)
                                    <div class="font-bold text-gray-900">{{ $art->bumdes->name }}</div>
                                    <div class="text-[10px] text-gray-500">BUMDesa</div>
                                @else
                                    <div class="font-bold text-blue-600">Portal Pusat</div>
                                    <div class="text-[10px] text-gray-500">Global</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-normal">
                                <div
                                    class="bg-blue-100 text-blue-800 text-[10px] font-bold px-2 py-0.5 rounded uppercase inline-block mb-1">
                                    {{ $art->category }}</div>
                                <div class="font-bold text-gray-900 leading-tight">{{ $art->title }}</div>
                            </td>
                            <td class="px-6 py-4">{{ $art->created_at->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 text-center">
                                <form action="{{ route('superadmin.artikel.toggle_featured', $art->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="transition-all p-1.5 rounded-full {{ $art->is_featured ? 'bg-yellow-100 text-yellow-600 border border-yellow-200' : 'bg-gray-50 text-gray-300 border border-gray-100' }}"
                                        title="{{ $art->is_featured ? 'Hapus dari Unggulan' : 'Set Jadi Unggulan' }}">
                                        <i class="fa-solid fa-star text-sm"></i>
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button
                                    onclick="editArtikel({{ $art->id }}, '{{ addslashes($art->title) }}', '{{ addslashes($art->category) }}', '{{ addslashes($art->content) }}')"
                                    class="text-accent hover:text-yellow-600 bg-yellow-50 hover:bg-yellow-100 p-2 rounded-md transition-colors mr-1">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <form action="{{ route('superadmin.artikel.destroy', $art->id) }}" method="POST"
                                    class="inline-block" onsubmit="return confirm('Hapus artikel ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-2 rounded-md transition-colors"><i
                                            class="fa-solid fa-trash"></i></button>
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
        <div class="relative top-10 mx-auto p-5 border w-full max-w-3xl shadow-lg rounded-xl bg-white mb-20">
            <div class="flex justify-between items-center mb-4 border-b pb-2">
                <h3 class="text-xl font-bold text-gray-900">Tulis Artikel Baru</h3>
                <button onclick="document.getElementById('modal-add').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-900 text-2xl font-bold">&times;</button>
            </div>
            <form action="{{ route('superadmin.artikel.store') }}" method="POST" enctype="multipart/form-data"
                class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700">Judul Artikel / Berita</label>
                    <input type="text" name="title" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kategori</label>
                    <select name="category" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
                        <option value="Berita">Berita</option>
                        <option value="Cerita Sukses">Cerita Sukses</option>
                        <option value="Opini Pakar">Opini Pakar</option>
                        <option value="Regulasi">Regulasi</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Cover Gambar (Opsional)</label>
                    <input type="file" name="image" accept="image/*"
                        class="mt-1 block w-full border text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary-900 p-2 rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Isi Konten</label>
                    <textarea name="content" rows="10" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2"></textarea>
                </div>
                <div class="pt-4 border-t flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('modal-add').classList.add('hidden')"
                        class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-900">Terbitkan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="modal-edit" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full">
        <div class="relative top-10 mx-auto p-5 border w-full max-w-3xl shadow-lg rounded-xl bg-white mb-20">
            <div class="flex justify-between items-center mb-4 border-b pb-2">
                <h3 class="text-xl font-bold text-gray-900">Ubah Artikel</h3>
                <button onclick="document.getElementById('modal-edit').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-900 text-2xl font-bold">&times;</button>
            </div>
            <form id="form-edit" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium text-gray-700">Judul Artikel</label>
                    <input type="text" name="title" id="edit-title" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kategori</label>
                    <select name="category" id="edit-category" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
                        <option value="Berita">Berita</option>
                        <option value="Cerita Sukses">Cerita Sukses</option>
                        <option value="Opini Pakar">Opini Pakar</option>
                        <option value="Regulasi">Regulasi</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Ganti Gambar (Kosongkan jika tak ingin
                        ganti)</label>
                    <input type="file" name="image" accept="image/*"
                        class="mt-1 block w-full border text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white p-2 rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Isi Konten</label>
                    <textarea name="content" id="edit-content" rows="10" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2"></textarea>
                </div>
                <div class="pt-4 border-t flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('modal-edit').classList.add('hidden')"
                        class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-900">Perbarui</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function editArtikel(id, title, category, content) {
            document.getElementById('form-edit').action = `/superadmin/artikel/${id}`;
            document.getElementById('edit-title').value = title;
            document.getElementById('edit-category').value = category;
            document.getElementById('edit-content').value = content;
            document.getElementById('modal-edit').classList.remove('hidden');
        }
    </script>
@endsection
