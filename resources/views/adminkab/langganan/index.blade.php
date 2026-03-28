@extends('layouts.admin')
@section('title', 'Langganan Akun Kabupaten')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Manajemen Langganan Akun Kabupaten</h2>
        <p class="text-gray-500 text-sm mt-1">Kelola dan tingkatkan batas monitoring atau fitur khusus Kabupaten Anda.</p>
    </div>
    <button onclick="document.getElementById('modal-add').classList.remove('hidden')" class="bg-primary hover:bg-primary-900 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors shadow-sm">
        <i class="fa-solid fa-crown mr-2"></i> Langganan Paket Baru
    </button>
</div>

<div class="bg-white rounded-xl shadow-sm border p-6">
    <div class="table-responsive w-full overflow-x-auto">
        <table class="datatable w-full whitespace-nowrap text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th class="px-6 py-3">Paket Langganan</th>
                    <th class="px-6 py-3">Jumlah Bayar</th>
                    <th class="px-6 py-3">Metode Pengajuan</th>
                    <th class="px-6 py-3">Masa Berlaku</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($langganans as $lang)
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="px-6 py-4 font-bold text-accent">{{ $lang->package_name }}</td>
                    <td class="px-6 py-4 text-green-600 font-bold">Rp {{ number_format($lang->amount, 0, ',', '.') }}</td>
                    <td class="px-6 py-4">{{ Str::upper($lang->payment_method ?? 'TRANSFER') }}</td>
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
                            <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded border border-yellow-200">Menunggu Verifikasi</span>
                        @else
                            <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded border border-red-200">Tidak Aktif / Expired</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        @if($lang->status === 'pending')
                        <button class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1.5 rounded transition font-bold tooltip" title="Tunggu Super Admin untuk mengkonfirmasi pembayaran.">
                            <i class="fa-solid fa-clock mr-1 text-yellow-500"></i> Menunggu...
                        </button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Add Modal -->
<div id="modal-add" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center p-4">
    <div class="relative w-full max-w-lg shadow-2xl rounded-2xl bg-white border overflow-hidden">
        <div class="flex justify-between items-center p-5 border-b bg-gray-50">
            <h3 class="text-xl font-bold text-gray-900"><i class="fa-solid fa-crown text-accent mr-2"></i> Langganan Paket Admin Kabupaten</h3>
            <button onclick="document.getElementById('modal-add').classList.add('hidden')" class="text-gray-400 hover:text-gray-900 text-2xl font-bold leading-none">&times;</button>
        </div>
        <form action="{{ route('adminkab.langganan.store') }}" method="POST" class="p-6 space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Pilih Paket <span class="text-red-500">*</span></label>
                <select name="pricing_config_id" required class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-primary focus:border-primary bg-white shadow-sm">
                    <option value="">-- Pilih Paket Kabupaten --</option>
                    @foreach($pricingConfigs as $cfg)
                        <option value="{{ $cfg->id }}">
                            {{ $cfg->name }} - Rp {{ number_format($cfg->total_price, 0, ',', '.') }} ({{ $cfg->months }} Bulan)
                        </option>
                    @endforeach
                </select>
                @if($pricingConfigs->isEmpty())
                <p class="text-xs text-red-500 mt-1">Super Admin belum menambahkan paket harga untuk Kabupaten.</p>
                @endif
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Metode Pembayaran</label>
                <select name="payment_method" required class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-primary focus:border-primary bg-white shadow-sm">
                    <option value="bank_transfer">Transfer Bank Manual</option>
                    <option value="qris">QRIS (Otomatis)</option>
                    <option value="virtual_account">Virtual Account</option>
                </select>
                <p class="text-xs text-gray-500 mt-2"><i class="fa-solid fa-circle-info mr-1 text-primary"></i>Setelah memilih dan menyimpan, tagihan (invoice) akan diterbitkan dan status Anda akan pending sampai konfirmasi berhasil.</p>
            </div>
            
            <div class="pt-4 border-t flex justify-end gap-3 mt-6">
                <button type="button" onclick="document.getElementById('modal-add').classList.add('hidden')" class="px-5 py-2.5 border rounded-lg text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-colors">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary-900 shadow-sm transition-colors flex items-center gap-2" {{ $pricingConfigs->isEmpty() ? 'disabled' : '' }}>
                    <i class="fa-solid fa-paper-plane"></i> Ajukan Langganan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
