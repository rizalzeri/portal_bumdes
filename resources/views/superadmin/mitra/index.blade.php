@extends('layouts.admin')
@section('title', 'Mitra Kerjasama')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Mitra Kerjasama Portal BUMDesa</h2>
            <p class="text-gray-500 text-sm mt-1">Kelola daftar instansi dan perusahaan yang menjalin kemitraan strategis.
            </p>
        </div>
        <button onclick="document.getElementById('modal-add').classList.remove('hidden')"
            class="bg-primary hover:bg-primary-900 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors shadow-sm">
            <i class="fa-solid fa-plus mr-2"></i> Tambah Mitra
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm border p-6">
        <div class="table-responsive w-full overflow-x-auto">
            <table class="datatable w-full whitespace-nowrap text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th class="px-6 py-3">Logo</th>
                        <th class="px-6 py-3">Sumber</th>
                        <th class="px-6 py-3">Nama Instansi</th>
                        <th class="px-6 py-3">Deskripsi</th>
                        <th class="px-6 py-3 text-center">Unggulan (Home)</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($mitras as $mitra)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4">
                                @if ($mitra->logo)
                                    <img src="{{ asset('storage/' . $mitra->logo) }}"
                                        class="w-16 h-12 object-contain bg-white rounded border p-1 border-gray-200">
                                @else
                                    <div class="w-16 h-12 bg-gray-100 flex items-center justify-center rounded border"><i
                                            class="fa-solid fa-building text-gray-400"></i></div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if ($mitra->bumdes_id)
                                    <div class="font-bold text-gray-900 leading-tight">{{ $mitra->bumdes->name }}</div>
                                    <div class="text-[10px] text-gray-500">BUMDesa</div>
                                @else
                                    <div class="font-bold text-blue-600 font-bold uppercase text-xs">Portal Pusat</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-bold text-gray-900 whitespace-normal leading-tight">
                                {{ $mitra->name }}</td>
                            <td class="px-6 py-4 whitespace-normal">
                                @if ($mitra->website)
                                    <a href="{{ $mitra->website }}" target="_blank"
                                        class="text-[10px] inline-block bg-blue-50 text-blue-600 px-1.5 py-0.5 rounded hover:bg-blue-100 mb-1"><i
                                            class="fa-solid fa-globe mr-1"></i> Website</a>
                                @endif
                                <p class="text-[11px] text-gray-500 line-clamp-1 italic">{{ $mitra->description ?? '-' }}
                                </p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <form action="{{ route('superadmin.mitra.toggle_featured', $mitra->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="transition-all p-1.5 rounded-full {{ $mitra->is_featured ? 'bg-yellow-100 text-yellow-600 border border-yellow-200' : 'bg-gray-50 text-gray-300 border border-gray-100' }}"
                                        title="{{ $mitra->is_featured ? 'Hapus dari Unggulan' : 'Set Jadi Unggulan' }}">
                                        <i class="fa-solid fa-star text-sm"></i>
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button
                                    onclick="editMitra({{ $mitra->id }}, '{{ addslashes($mitra->name) }}', '{{ addslashes($mitra->description ?? '') }}', '{{ addslashes($mitra->website ?? '') }}')"
                                    class="text-accent hover:text-yellow-600 bg-yellow-50 hover:bg-yellow-100 p-2 rounded-md transition-colors mr-1">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <form action="{{ route('superadmin.mitra.destroy', $mitra->id) }}" method="POST"
                                    class="inline-block" onsubmit="return confirm('Hapus mitra ini?');">
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
        <div class="relative top-20 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-xl bg-white mb-20">
            <div class="flex justify-between items-center mb-4 border-b pb-2">
                <h3 class="text-xl font-bold text-gray-900">Tambah Mitra Baru</h3>
                <button onclick="document.getElementById('modal-add').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-900 text-2xl font-bold">&times;</button>
            </div>
            <form action="{{ route('superadmin.mitra.store') }}" method="POST" enctype="multipart/form-data"
                class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Instansi / Perusahaan</label>
                    <input type="text" name="name" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Logo (Direkomendasikan transparan / PNG)</label>
                    <input type="file" name="logo" accept="image/*"
                        class="mt-1 block w-full border text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white border-gray-300 rounded-md p-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">URL Website (Diawali https://)</label>
                    <input type="url" name="website" placeholder="https://..."
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Deskripsi Singkat</label>
                    <textarea name="description" rows="3"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2"></textarea>
                </div>

                <div class="pt-4 border-t flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('modal-add').classList.add('hidden')"
                        class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-900">Simpan
                        Mitra</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="modal-edit" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-xl bg-white mb-20">
            <div class="flex justify-between items-center mb-4 border-b pb-2">
                <h3 class="text-xl font-bold text-gray-900">Ubah Data Mitra</h3>
                <button onclick="document.getElementById('modal-edit').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-900 text-2xl font-bold">&times;</button>
            </div>
            <form id="form-edit" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Instansi / Perusahaan</label>
                    <input type="text" name="name" id="edit-name" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Ganti Logo (Kosongkan bila tidak
                        diganti)</label>
                    <input type="file" name="logo" accept="image/*"
                        class="mt-1 block w-full border text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white border-gray-300 rounded-md p-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">URL Website</label>
                    <input type="url" name="website" id="edit-website"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Deskripsi Singkat</label>
                    <textarea name="description" id="edit-desc" rows="3"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2"></textarea>
                </div>

                <div class="pt-4 border-t flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('modal-edit').classList.add('hidden')"
                        class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-900">Perbarui
                        Mitra</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function editMitra(id, name, desc, website) {
            document.getElementById('form-edit').action = `/superadmin/mitra/${id}`;
            document.getElementById('edit-name').value = name;
            document.getElementById('edit-desc').value = desc;
            document.getElementById('edit-website').value = website;
            document.getElementById('modal-edit').classList.remove('hidden');
        }
    </script>
@endsection
