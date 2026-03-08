@extends('layouts.admin')
@section('title', 'Artikel & Berita BUMDesa')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Artikel & Berita</h2>
            <p class="text-gray-500 text-sm mt-1">Publikasikan kegiatan, berita, dan prestasi BUMDesa Anda ke khalayak luas.
            </p>
        </div>
        <button onclick="document.getElementById('modal-add').classList.remove('hidden')"
            class="bg-primary hover:bg-primary-900 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors shadow-sm">
            <i class="fa-solid fa-pen-nib mr-2"></i> Tulis Artikel Baru
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm border p-6">
        <div class="table-responsive w-full overflow-x-auto">
            <table class="datatable w-full whitespace-nowrap text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th class="px-6 py-3">Tgl Terbit</th>
                        <th class="px-6 py-3">Cover</th>
                        <th class="px-6 py-3" style="min-width: 250px">Judul Artikel</th>
                        <th class="px-6 py-3">Dilihat</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($artikels as $a)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4">{{ $a->created_at->format('d/m/Y') }}</td>
                            <td class="px-6 py-4">
                                @if ($a->image)
                                    <img src="{{ asset('storage/' . $a->image) }}"
                                        class="w-16 h-10 object-cover rounded border shadow-sm">
                                @else
                                    <div
                                        class="w-16 h-10 bg-gray-100 flex items-center justify-center rounded border text-gray-400 text-xs">
                                        <i class="fa-solid fa-image"></i></div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-normal">
                                <div class="font-bold text-gray-900 text-base leading-tight">{{ $a->title }}</div>
                                <div
                                    class="text-[10px] text-white bg-blue-500 px-2 py-0.5 rounded-full inline-block mt-1 uppercase font-bold">
                                    {{ $a->category }}</div>
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-gray-600">{{ $a->views }}x</td>
                            <td class="px-6 py-4 text-right">
                                <form action="{{ route('user.artikel.destroy', $a->id) }}" method="POST"
                                    class="inline-block" onsubmit="return confirm('Hapus artikel ini permanen?');">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-2 rounded-md transition-colors tooltip"
                                        title="Hapus"><i class="fa-solid fa-trash"></i></button>
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
        <div class="relative top-10 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-xl bg-white mb-20">
            <div class="flex justify-between items-center mb-4 border-b pb-2">
                <h3 class="text-xl font-bold text-gray-900">Tulis Artikel Baru</h3>
                <button onclick="document.getElementById('modal-add').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-900 text-2xl font-bold">&times;</button>
            </div>
            <form action="{{ route('user.artikel.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4"
                onsubmit="this.querySelector('[type=submit]').disabled=true; this.querySelector('[type=submit]').innerHTML='<i class=\'fa-solid fa-spinner fa-spin mr-2\'></i> Menerbitkan...';">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Left Details -->
                    <div class="md:col-span-1 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kategori Artikel</label>
                            <select name="category" class="mt-1 block w-full border text-sm border-gray-300 rounded-md p-2">
                                <option value="artikel">Artikel & Berita Desa</option>
                                <option value="opini">Opini & Pendapat</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Cover Artikel (Wajib Image)</label>
                            <input type="file" name="image" accept="image/*"
                                class="mt-1 block w-full border text-sm text-gray-500 file:mr-2 file:py-1 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-gray-200 file:text-gray-700 border-gray-300 rounded-md p-2 bg-gray-50">
                        </div>
                    </div>
                    <!-- Right Content -->
                    <div class="md:col-span-2 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Judul Artikel <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="title" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2 text-lg font-bold">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Isi / Konten <span
                                    class="text-red-500">*</span></label>
                            <textarea name="content" rows="10" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2"></textarea>
                            <p class="text-xs text-gray-500 mt-1">Gunakan paragraf yang jelas dan rapi agar mudah dibaca
                                masyarakat. Anda bisa menggunakan HTML tag sederhana (<b>, <i>, <br>).</p>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t flex justify-end gap-2 mt-6">
                    <button type="button" onclick="document.getElementById('modal-add').classList.add('hidden')"
                        class="px-6 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Batal</button>
                    <button type="submit"
                        class="px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-900"><i
                            class="fa-solid fa-paper-plane mr-2"></i> Terbitkan Artikel</button>
                </div>
            </form>
        </div>
    </div>
@endsection
