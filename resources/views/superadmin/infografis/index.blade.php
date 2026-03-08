@extends('layouts.admin')
@section('title', 'Manajemen Infografis')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Manajemen Infografis Kabupaten</h2>
        <p class="text-gray-500 text-sm mt-1">Pilih maksimal 4 Kabupaten yang akan ditampilkan sebagai unggulan di halaman
            Beranda.</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b">
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Unggulan</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Kabupaten</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Provinsi</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Slug</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($kabupatens as $kb)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <form action="{{ route('superadmin.infografis.toggle_featured', $kb->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="transition-all p-2 rounded-lg {{ $kb->is_featured ? 'bg-yellow-100 text-yellow-600' : 'text-gray-300 hover:text-yellow-400' }}"
                                        title="{{ $kb->is_featured ? 'Hapus dari Unggulan' : 'Jadikan Unggulan' }}">
                                        <i class="fa-solid fa-star {{ $kb->is_featured ? '' : 'fa-regular' }} text-lg"></i>
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-4 font-bold text-gray-900">{{ $kb->name }}</td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-2 py-1 bg-blue-50 text-blue-600 text-[10px] font-bold rounded uppercase">{{ $kb->province->name }}</span>
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-400">{{ $kb->slug }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('public.infografis.kabupaten', $kb->id) }}" target="_blank"
                                    class="text-primary hover:text-primary-900 text-sm font-semibold flex items-center gap-1">
                                    <i class="fa-solid fa-eye"></i> Lihat Preview
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-400 italic">Data kabupaten belum
                                tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
