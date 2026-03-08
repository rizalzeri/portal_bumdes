@extends('layouts.admin')
@section('title', 'Materi & Template')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Manajemen Materi Edukasi & Template</h2>
            <p class="text-gray-500 text-sm mt-1">Unggah file dokumen PDF, Word, atau Excel untuk dapat diunduh publik dan
                BUMDesa.</p>
        </div>
        <button onclick="document.getElementById('modal-add').classList.remove('hidden')"
            class="bg-primary hover:bg-primary-900 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors shadow-sm">
            <i class="fa-solid fa-cloud-arrow-up mr-2"></i> Unggah Dokumen Baru
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm border p-6">
        <div class="table-responsive w-full overflow-x-auto">
            <table class="datatable w-full whitespace-nowrap text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th class="px-6 py-3">Sumber</th>
                        <th class="px-6 py-3">Nama Dokumen</th>
                        <th class="px-6 py-3">Kategori</th>
                        <th class="px-6 py-3 text-center">Unggulan (Home)</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($materis as $mat)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4">
                                @if ($mat->bumdes_id)
                                    <div class="font-bold text-gray-900 leading-tight">{{ $mat->bumdes->name }}</div>
                                    <div class="text-[10px] text-gray-500">BUMDesa</div>
                                @else
                                    <div class="font-bold text-blue-600">Portal Pusat</div>
                                    <div class="text-[10px] text-gray-500">Global</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-bold text-gray-900 whitespace-normal leading-tight">
                                <i class="fa-solid fa-file-lines text-red-500 mr-2"></i> {{ $mat->title }}
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="bg-blue-100 text-blue-800 text-[10px] font-bold px-2 py-0.5 rounded border border-blue-200 capitalize">
                                    {{ $mat->type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <form action="{{ route('superadmin.materi.toggle_featured', $mat->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="transition-all p-1.5 rounded-full {{ $mat->is_featured ? 'bg-yellow-100 text-yellow-600 border border-yellow-200' : 'bg-gray-50 text-gray-300 border border-gray-100' }}"
                                        title="{{ $mat->is_featured ? 'Hapus dari Unggulan' : 'Set Jadi Unggulan' }}">
                                        <i class="fa-solid fa-star text-sm"></i>
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ asset('storage/' . $mat->file_url) }}" target="_blank"
                                    class="text-green-600 hover:text-green-800 bg-green-50 hover:bg-green-100 p-2 rounded-md transition-colors mr-1 inline-block"
                                    title="Download">
                                    <i class="fa-solid fa-download"></i>
                                </a>
                                <form action="{{ route('superadmin.materi.destroy', $mat->id) }}" method="POST"
                                    class="inline-block" onsubmit="return confirm('Hapus dokumen ini dari server?');">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-2 rounded-md transition-colors"
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
        <div class="relative top-10 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-xl bg-white">
            <div class="flex justify-between items-center mb-4 border-b pb-2">
                <h3 class="text-xl font-bold text-gray-900">Unggah Dokumen Baru</h3>
                <button onclick="document.getElementById('modal-add').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-900 text-2xl font-bold">&times;</button>
            </div>
            <form action="{{ route('superadmin.materi.store') }}" method="POST" enctype="multipart/form-data"
                class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700">Judul Dokumen</label>
                    <input type="text" name="title" required placeholder="Cth: Panduan Pendaftaran BUMDesa"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Jenis Dokumen</label>
                    <select name="type" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
                        <option value="materi">Materi Edukasi</option>
                        <option value="template">Template Surat/Laporan</option>
                        <option value="panduan">Buku Panduan / SOP</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">File Dokumen</label>
                    <input type="file" name="file_url" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip" required
                        class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm p-2 bg-gray-50 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary-900 cursor-pointer">
                    <p class="text-xs text-gray-500 mt-1">Format didukung: PDF, Word, Excel, PPT, ZIP. Maks: 50MB.</p>
                </div>

                <div class="pt-4 border-t flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('modal-add').classList.add('hidden')"
                        class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-900">Upload
                        Data</button>
                </div>
            </form>
        </div>
    </div>
@endsection
