@extends('layouts.admin')
@section('title', 'Langganan BUMDesa')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Manajemen Langganan BUMDesa</h2>
        <p class="text-gray-500 text-sm mt-1">Pantau dan buat tagihan langganan paket untuk BUMDesa di wilayah Anda.</p>
    </div>
    <button onclick="document.getElementById('modal-add').classList.remove('hidden')" class="bg-primary hover:bg-primary-900 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors shadow-sm">
        <i class="fa-solid fa-file-invoice-dollar mr-2"></i> Buat Tagihan
    </button>
</div>

<div class="bg-white rounded-xl shadow-sm border p-6">
    <div class="table-responsive w-full overflow-x-auto">
        <table class="datatable w-full whitespace-nowrap text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th class="px-6 py-3">Nama BUMDesa</th>
                    <th class="px-6 py-3">Paket Langganan</th>
                    <th class="px-6 py-3">Masa Berlaku</th>
                    <th class="px-6 py-3">Status Langganan</th>
                    <th class="px-6 py-3 text-right">Aksi Pembayaran</th>
                </tr>
            </thead>
            <tbody>
                @foreach($langganans as $lang)
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="px-6 py-4 font-bold text-gray-900">
                        @if($lang->bumdes)
                            <i class="fa-solid fa-house-flag text-blue-500 mr-2"></i> {{ $lang->bumdes->name }}
                        @else
                            <i class="fa-solid fa-map text-green-500 mr-2"></i> Langganan Kabupaten
                        @endif
                    </td>
                    <td class="px-6 py-4 font-bold text-accent">{{ $lang->package_name }}</td>
                    <td class="px-6 py-4">
                        <div class="text-xs">{{ \Carbon\Carbon::parse($lang->start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($lang->end_date)->format('d/m/Y') }}</div>
                        @if($lang->status === 'active')
                            <div class="text-[10px] {{ \Carbon\Carbon::parse($lang->end_date)->isPast() ? 'text-red-500 font-bold' : 'text-green-500' }}">Sisa {{ \Carbon\Carbon::parse($lang->end_date)->diffInDays(now()) }} hari</div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($lang->status === 'active')
                            <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded border border-green-200">Aktif Terbayar</span>
                        @elseif($lang->status === 'pending')
                            <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded border border-yellow-200">Menunggu Pembayaran</span>
                        @else
                            <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded border border-red-200">Tidak Aktif / Expired</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        @if($lang->status === 'pending')
                        <button class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1.5 rounded transition font-bold tooltip" title="Pembayaran langsung dilakukan melalui panel BUMDes ybs, atau Super Admin">
                            <i class="fa-solid fa-clock mr-1 text-yellow-500"></i> Menunggu...
                        </button>
                        @else
                        <span class="text-xs text-green-600 font-bold"><i class="fa-solid fa-check mr-1"></i> Lunas</span>
                        @endif
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
            <h3 class="text-xl font-bold text-gray-900">Buat Tagihan Langganan BUMDes</h3>
            <button onclick="document.getElementById('modal-add').classList.add('hidden')" class="text-gray-400 hover:text-gray-900 text-2xl font-bold">&times;</button>
        </div>
        <form action="{{ route('adminkab.langganan.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700">Pilih BUMDesa</label>
                <select name="bumdes_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
                    <option value="">-- Pilih BUMDesa --</option>
                    @foreach($bumdesList as $b)
                        <option value="{{ $b->id }}">{{ $b->name }} - {{ $b->desa }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Paket Akses</label>
                <select name="package_name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
                    <option value="Paket Premium BUMDes">Paket Premium BUMDes (Seluruh Fitur)</option>
                    <option value="Paket Dasar (Gratis)">Paket Dasar (Terbatas)</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Durasi Perpanjangan (Tahun)</label>
                <input type="number" name="duration" value="1" min="1" max="5" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm border p-2">
            </div>
            
            <div class="pt-4 border-t flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modal-add').classList.add('hidden')" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Batal</button>
                <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-900">Buat Tagihan</button>
            </div>
        </form>
    </div>
</div>
@endsection
