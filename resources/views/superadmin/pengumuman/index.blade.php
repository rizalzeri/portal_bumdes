@extends('layouts.admin')
@section('title', 'Manajemen Pengumuman Global')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Pengumuman Portal</h2>
            <p class="text-gray-500 text-sm mt-1">Buat pengumuman yang akan tampil di halaman depan untuk seluruh pengunjung
                dan BUMDesa.</p>
        </div>
        <button onclick="document.getElementById('modal-add').classList.remove('hidden')"
            class="bg-primary hover:bg-primary-900 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors shadow-sm">
            <i class="fa-solid fa-bullhorn mr-2"></i> Buat Pengumuman Biasa
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm border p-6">
        <div class="table-responsive w-full overflow-x-auto">
            <table class="datatable w-full whitespace-nowrap text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th class="px-6 py-3">Sumber</th>
                        <th class="px-6 py-3">Tanggal</th>
                        <th class="px-6 py-3">Judul Pengumuman</th>
                        <th class="px-6 py-3" style="min-width: 250px">Preview</th>
                        <th class="px-6 py-3 text-center">Unggulan (Home)</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pengumumans as $p)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4">
                                @if ($p->bumdes_id)
                                    <div class="font-bold text-gray-900 leading-tight">{{ $p->bumdes->name }}</div>
                                    <div class="text-[10px] text-gray-500">BUMDesa</div>
                                @elseif($p->type === 'kabupaten' && $p->kabupaten)
                                    <div class="font-bold text-amber-600">Portal {{ $p->kabupaten->name }}</div>
                                    <div class="text-[10px] text-gray-500">Kabupaten</div>
                                @else
                                    <div class="font-bold text-blue-600">Portal Pusat</div>
                                    <div class="text-[10px] text-gray-500">Global</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs">{{ $p->created_at->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 font-bold text-gray-900 whitespace-normal leading-tight">
                                {{ $p->title }}</td>
                            <td class="px-6 py-4 whitespace-normal text-xs text-gray-500 leading-tight">
                                {{ Str::limit(strip_tags($p->content), 80) }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <form action="{{ route('superadmin.pengumuman.toggle_featured', $p->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="transition-all p-1.5 rounded-full {{ $p->is_featured ? 'bg-yellow-100 text-yellow-600 border border-yellow-200' : 'bg-gray-50 text-gray-300 border border-gray-100' }}"
                                        title="{{ $p->is_featured ? 'Hapus dari Unggulan' : 'Set Jadi Unggulan' }}">
                                        <i class="fa-solid fa-star text-sm"></i>
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button
                                    onclick="editPengumuman({{ $p->id }}, '{{ addslashes($p->title) }}', '{{ addslashes($p->content) }}')"
                                    class="text-accent hover:text-yellow-600 bg-yellow-50 hover:bg-yellow-100 p-2 rounded-md transition-colors mr-1">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <form action="{{ route('superadmin.pengumuman.destroy', $p->id) }}" method="POST"
                                    class="inline-block"
                                    onsubmit="return confirm('Hapus pengumuman ini secara permanen?');">
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
        <div class="relative top-10 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-xl bg-white">
            <div class="flex justify-between items-center mb-4 border-b pb-2">
                <h3 class="text-xl font-bold text-gray-900">Buat Pengumuman Baru</h3>
                <button onclick="document.getElementById('modal-add').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-900 text-2xl font-bold">&times;</button>
            </div>
            <form action="{{ route('superadmin.pengumuman.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700">Judul Pengumuman</label>
                    <input type="text" name="title" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Isi Pengumuman</label>
                    <textarea name="content" rows="6" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2"></textarea>
                    <p class="text-xs text-gray-500 mt-1">Anda bisa memasukkan teks panjang untuk pengumuman.</p>
                </div>
                <div class="pt-4 border-t flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('modal-add').classList.add('hidden')"
                        class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-900">Publikasikan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="modal-edit" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full">
        <div class="relative top-10 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-xl bg-white">
            <div class="flex justify-between items-center mb-4 border-b pb-2">
                <h3 class="text-xl font-bold text-gray-900">Edit Pengumuman</h3>
                <button onclick="document.getElementById('modal-edit').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-900 text-2xl font-bold">&times;</button>
            </div>
            <form id="form-edit" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium text-gray-700">Judul Pengumuman</label>
                    <input type="text" name="title" id="edit-title" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Isi Pengumuman</label>
                    <textarea name="content" id="edit-content" rows="6" required
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
        function editPengumuman(id, title, content) {
            document.getElementById('form-edit').action = `/superadmin/pengumuman/${id}`;
            document.getElementById('edit-title').value = title;
            document.getElementById('edit-content').value = content;
            document.getElementById('modal-edit').classList.remove('hidden');
        }
    </script>
@endsection
