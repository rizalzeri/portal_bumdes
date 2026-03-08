@extends('layouts.app')
@section('title', 'Kelola Admin Kabupaten')

@section('content')
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Daftar Admin Kabupaten</h2>
                <p class="text-sm text-gray-500 mt-1">Kelola akun Admin Kabupaten untuk sistem BUMDesa.</p>
            </div>
            <a href="{{ route('superadmin.adminkab.create') }}"
                class="inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-800 transition">
                <i class="fa-solid fa-plus mr-2"></i> Tambah Admin Baru
            </a>
        </div>

        @if (session('success'))
            <div class="p-4 bg-green-50 text-green-700 border-l-4 border-green-500">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto p-0">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-4">No</th>
                        <th scope="col" class="px-6 py-4">Provinsi</th>
                        <th scope="col" class="px-6 py-4">Kabupaten</th>
                        <th scope="col" class="px-6 py-4">Username (Login)</th>
                        <th scope="col" class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($admins as $index => $admin)
                        <tr class="bg-white border-b hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $admin->kabupaten->province->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 font-medium text-primary">{{ $admin->kabupaten->name ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <span
                                    class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-xs font-mono border border-gray-200">{{ $admin->username ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4 text-center flex justify-center gap-2">
                                <form action="{{ route('superadmin.adminkab.destroy', $admin->id) }}" method="POST"
                                    class="inline-block"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus admin ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-red-500 hover:bg-red-50 p-2 rounded-lg transition-colors border border-transparent hover:border-red-200"
                                        title="Hapus">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 justify-center text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <i class="fa-solid fa-inbox text-4xl text-gray-300"></i>
                                    <p>Belum ada data Admin Kabupaten.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
