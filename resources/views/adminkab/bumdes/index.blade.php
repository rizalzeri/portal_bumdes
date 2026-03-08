@extends('layouts.admin')
@section('title', 'Manajemen BUMDesa')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Manajemen BUMDesa</h2>
            <p class="text-gray-500 text-sm mt-1">Daftar BUMDesa yang terdaftar di wilayah Kabupaten Anda.</p>
        </div>
        <a href="{{ route('adminkab.bumdes.create') }}"
            class="bg-primary hover:bg-primary-900 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors shadow-sm">
            <i class="fa-solid fa-plus mr-2"></i> Daftarkan BUMDesa Baru
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border p-6">
        <div class="table-responsive w-full overflow-x-auto">
            <table class="datatable w-full whitespace-nowrap text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th class="px-6 py-3">Nama BUMDesa</th>
                        <th class="px-6 py-3">Kecamatan / Desa</th>
                        <th class="px-6 py-3">User & Kontak</th>
                        <th class="px-6 py-3">Klasifikasi</th>
                        <th class="px-6 py-3">Status Sistem</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bumdes as $b)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900 text-base">{{ $b->name }}</div>
                                <div class="text-[10px] text-gray-400 font-mono mt-1">Sertifikat:
                                    {{ $b->nomor_sertifikat ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-800">{{ $b->kecamatan }}</div>
                                <div class="text-xs text-gray-500">{{ $b->desa }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-normal">
                                <div class="font-medium text-primary">{{ $b->user->email ?? 'Tanpa Akun' }}</div>
                                <div class="text-xs text-gray-500 mt-1"><i class="fa-solid fa-phone mr-1"></i>
                                    {{ $b->phone ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if ($b->klasifikasi == 'Dasar')
                                    <span
                                        class="bg-orange-100 text-orange-800 text-xs font-semibold px-2.5 py-0.5 rounded border border-orange-200 uppercase">Dasar</span>
                                @elseif($b->klasifikasi == 'Berkembang')
                                    <span
                                        class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded border border-blue-200 uppercase">Berkembang</span>
                                @elseif($b->klasifikasi == 'Maju')
                                    <span
                                        class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded border border-green-200 uppercase">Maju</span>
                                @else
                                    <span class="text-gray-400 text-xs italic">Belum dinilai</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if ($b->is_active || $b->status === 'active')
                                    <span class="text-green-600 font-bold"><i class="fa-solid fa-check-circle mr-1"></i>
                                        Aktif</span>
                                @else
                                    <span class="text-gray-400 font-bold"><i class="fa-solid fa-clock mr-1"></i> Menunggu /
                                        Inaktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <!-- Toggle Button -->
                                <form action="{{ route('adminkab.bumdes.toggle_status', $b->id) }}" method="POST"
                                    class="inline-block"
                                    onsubmit="return confirm('Anda yakin ingin mengubah status BUMDesa ini?');">
                                    @csrf
                                    <button type="submit"
                                        class="p-2 rounded-md transition-colors mr-1 inline-block tooltip {{ $b->is_active ? 'text-orange-500 hover:text-orange-700 bg-orange-50 hover:bg-orange-100' : 'text-green-500 hover:text-green-700 bg-green-50 hover:bg-green-100' }}"
                                        title="{{ $b->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                        <i class="fa-solid {{ $b->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                                    </button>
                                </form>
                                <a href="{{ route('adminkab.bumdes.edit', $b->id) }}"
                                    class="text-accent hover:text-yellow-600 bg-yellow-50 hover:bg-yellow-100 p-2 rounded-md transition-colors mr-1 inline-block tooltip"
                                    title="Edit Profil/Status">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <form action="{{ route('adminkab.bumdes.destroy', $b->id) }}" method="POST"
                                    class="inline-block"
                                    onsubmit="return confirm('Peringatan: Menghapus BUMDes akan menghapus SELURUH data terkait termasuk User Account. Yakin?');">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-2 rounded-md transition-colors tooltip"
                                        title="Hapus Permanen">
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
@endsection
