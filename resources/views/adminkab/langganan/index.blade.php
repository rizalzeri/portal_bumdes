@extends('layouts.admin')
@section('title', 'Langganan Akun Kabupaten')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Manajemen Langganan Akun Kabupaten</h2>
        <p class="text-gray-500 text-sm mt-1">Kelola dan tingkatkan batas monitoring atau fitur khusus Kabupaten Anda.</p>
    </div>
    @if(!$pending)
    <button onclick="document.getElementById('modal-add').classList.remove('hidden')"
        class="bg-primary hover:bg-primary-900 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors shadow-sm">
        <i class="fa-solid fa-crown mr-2"></i> Langganan Paket Baru
    </button>
    @endif
</div>

{{-- Alert: Ada tagihan pending --}}
@if($pending && $snapToken)
<div class="bg-amber-50 border border-amber-300 rounded-xl p-4 mb-5 flex items-start gap-4">
    <div class="text-amber-500 text-2xl mt-0.5"><i class="fa-solid fa-circle-exclamation"></i></div>
    <div class="flex-1">
        <p class="font-bold text-amber-700">Tagihan Belum Dibayar</p>
        <p class="text-sm text-amber-600 mt-1">Anda memiliki tagihan <strong>{{ $pending->package_name }}</strong> sebesar <strong>Rp {{ number_format($pending->amount, 0, ',', '.') }}</strong> yang belum diselesaikan.</p>
    </div>
    <button id="btn-pay-pending"
        data-token="{{ $snapToken }}"
        onclick="openMidtrans(this)"
        class="flex-shrink-0 bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-lg text-sm font-bold transition-colors flex items-center gap-2 shadow">
        <i class="fa-solid fa-credit-card"></i> Bayar Sekarang
    </button>
</div>
@endif

@if(session('success'))
<div class="bg-green-50 border border-green-200 text-green-700 rounded-xl p-4 mb-5 flex items-center gap-3">
    <i class="fa-solid fa-circle-check text-green-500"></i>
    {{ session('success') }}
</div>
@endif

@if(session('info'))
<div class="bg-blue-50 border border-blue-200 text-blue-700 rounded-xl p-4 mb-5 flex items-center gap-3">
    <i class="fa-solid fa-circle-info text-blue-500"></i>
    {{ session('info') }}
</div>
@endif

<div class="bg-white rounded-xl shadow-sm border p-6">
    <div class="table-responsive w-full overflow-x-auto">
        <table class="datatable w-full whitespace-nowrap text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th class="px-6 py-3">Paket Langganan</th>
                    <th class="px-6 py-3">Jumlah Bayar</th>
                    <th class="px-6 py-3">Masa Berlaku</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($langganans as $lang)
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="px-6 py-4 font-bold text-accent">{{ $lang->package_name }}</td>
                    <td class="px-6 py-4 text-green-600 font-bold">Rp {{ number_format($lang->amount, 0, ',', '.') }}</td>
                    <td class="px-6 py-4">
                        <div class="text-xs">{{ \Carbon\Carbon::parse($lang->start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($lang->end_date)->format('d/m/Y') }}</div>
                        @if($lang->status === 'active')
                            <div class="text-[10px] {{ \Carbon\Carbon::parse($lang->end_date)->isPast() ? 'text-red-500 font-bold' : 'text-green-500' }}">
                                Sisa {{ \Carbon\Carbon::parse($lang->end_date)->diffInDays(now()) }} hari
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($lang->status === 'active')
                            <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded border border-green-200">
                                <i class="fa-solid fa-circle-check mr-1"></i>Aktif
                            </span>
                        @elseif($lang->status === 'pending')
                            <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded border border-yellow-200">
                                <i class="fa-solid fa-clock mr-1"></i>Menunggu Pembayaran
                            </span>
                        @else
                            <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded border border-red-200">
                                <i class="fa-solid fa-ban mr-1"></i>Expired
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        @if($lang->status === 'pending' && $snapToken && $lang->id === $pending?->id)
                        <button
                            data-token="{{ $snapToken }}"
                            onclick="openMidtrans(this)"
                            class="text-xs bg-primary hover:bg-primary-900 text-white px-3 py-1.5 rounded transition font-bold flex items-center gap-1.5 ml-auto">
                            <i class="fa-solid fa-credit-card"></i> Bayar
                        </button>
                        @elseif($lang->status === 'pending')
                        <span class="text-xs text-gray-400 italic">Token kadaluarsa</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-10 text-gray-400">
                        <i class="fa-solid fa-crown text-3xl mb-2 block"></i>
                        Belum ada riwayat langganan. Klik <strong>"Langganan Paket Baru"</strong> untuk mulai.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal: Pilih Paket -->
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
                <p class="text-xs text-red-500 mt-1">Operator belum menambahkan paket harga untuk Kabupaten.</p>
                @endif
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-sm text-blue-700 flex items-start gap-2">
                <i class="fa-solid fa-shield-halved mt-0.5 text-blue-500"></i>
                <span>Pembayaran akan diproses secara aman melalui <strong>Midtrans</strong>. Anda akan diarahkan ke halaman pembayaran setelah mengklik <em>Ajukan & Bayar</em>.</span>
            </div>

            <div class="pt-4 border-t flex justify-end gap-3 mt-2">
                <button type="button" onclick="document.getElementById('modal-add').classList.add('hidden')"
                    class="px-5 py-2.5 border rounded-lg text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <button type="submit"
                    class="px-5 py-2.5 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary-900 shadow-sm transition-colors flex items-center gap-2"
                    {{ $pricingConfigs->isEmpty() ? 'disabled' : '' }}>
                    <i class="fa-solid fa-credit-card"></i> Ajukan & Bayar
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Midtrans Snap JS --}}
@if(config('services.midtrans.is_production'))
<script src="https://app.midtrans.com/snap/snap.js"
    data-client-key="{{ config('services.midtrans.client_key') }}"></script>
@else
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ config('services.midtrans.client_key') }}"></script>
@endif

<script>
function openMidtrans(btn) {
    const token = btn.getAttribute('data-token');
    if (!token) {
        alert('Token pembayaran tidak tersedia. Silakan muat ulang halaman.');
        return;
    }
    window.snap.pay(token, {
        onSuccess: function(result) {
            // Redirect ke success callback
            window.location.href = '{{ route("adminkab.langganan.success") }}';
        },
        onPending: function(result) {
            // Tetap di halaman, reload agar status terbaru terlihat
            window.location.reload();
        },
        onError: function(result) {
            alert('Terjadi kesalahan saat pembayaran. Silakan coba lagi.');
        },
        onClose: function() {
            // User menutup popup — status tetap pending
            console.log('Popup ditutup, status tetap pending.');
        }
    });
}

// Auto buka Midtrans jika baru saja membuat tagihan (redirect dari store)
@if(session('open_payment') && $snapToken)
window.addEventListener('load', function() {
    const btn = document.getElementById('btn-pay-pending');
    if (btn) openMidtrans(btn);
});
@endif
</script>
@endsection
