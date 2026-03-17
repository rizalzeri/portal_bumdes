@extends('layouts.admin')
@section('title', 'Pengumuman dari BUMDesa')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Pengumuman dari BUMDesa</h2>
            <p class="text-gray-500 text-sm mt-1">Sampaikan informasi penting terkait layanan atau kegiatan operasional
                BUMDesa Anda.
            </p>
        </div>
        <button onclick="document.getElementById('modal-add').classList.remove('hidden')"
            class="bg-primary hover:bg-primary-900 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors shadow-sm">
            <i class="fa-solid fa-bullhorn mr-2"></i> Buat Pengumuman Baru
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm border p-6">
        <div class="table-responsive w-full overflow-x-auto">
            <table class="datatable w-full whitespace-nowrap text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th class="px-6 py-3">Tanggal Buat</th>
                        <th class="px-6 py-3" style="min-width: 300px">Judul Pengumuman</th>
                        <th class="px-6 py-3">Tipe</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pengumumans as $p)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4">{{ $p->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 whitespace-normal">
                                <div class="font-bold text-gray-900 text-base leading-tight">{{ $p->title }}</div>
                                <div class="text-xs text-gray-500 mt-1 line-clamp-1">
                                    {{ Str::limit(strip_tags($p->content), 100) }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-2 py-1 text-[10px] font-bold rounded-full bg-blue-100 text-blue-700 uppercase border border-blue-200">
                                    {{ $p->type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <form
                                    action="{{ route('user.pengumuman.destroy', ['slug' => $bumdes->slug, 'pengumuman' => $p->id]) }}"
                                    method="POST" class="inline-block" onsubmit="return confirm('Hapus pengumuman ini?');">
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
        <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-xl bg-white mb-20">
            <div class="flex justify-between items-center mb-4 border-b pb-2">
                <h3 class="text-xl font-bold text-gray-900">Buat Pengumuman Baru</h3>
                <button onclick="document.getElementById('modal-add').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-900 text-2xl font-bold">&times;</button>
            </div>
            <form action="{{ route('user.pengumuman.store', $bumdes->slug) }}" method="POST" class="space-y-4"
                onsubmit="this.querySelector('[type=submit]').disabled=true; this.querySelector('[type=submit]').innerHTML='<i class=\'fa-solid fa-spinner fa-spin mr-2\'></i> Memproses...';">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700">Judul Pengumuman <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="title" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2 font-bold">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Isi Pengumuman <span
                            class="text-red-500">*</span></label>
                    <textarea name="content" rows="6" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2"></textarea>
                    <p class="text-xs text-gray-500 mt-1">Sampaikan pengumuman dengan ringkas dan jelas.</p>
                </div>

                <div class="pt-4 border-t flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('modal-add').classList.add('hidden')"
                        class="px-6 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Batal</button>
                    <button type="submit"
                        class="px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-900">
                        <i class="fa-solid fa-paper-plane mr-2"></i> Terbitkan Pengumuman
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
