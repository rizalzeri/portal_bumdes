@extends('layouts.admin')
@section('title', 'Langganan Akun Kabupaten')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Manajemen Langganan Akun Kabupaten</h2>
        <p class="text-gray-500 text-sm mt-1">Kelola dan tingkatkan batas monitoring atau fitur khusus Kabupaten Anda.</p>
    </div>
    <button onclick="document.getElementById('modal-add').classList.remove('hidden')"
        class="bg-primary hover:bg-primary-900 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors shadow-sm">
        <i class="fa-solid fa-clock-rotate-left mr-2"></i> Perpanjang Masa Aktif
    </button>
</div>

{{-- ── Active Subscription Banner ──────────────────── --}}
@if($active && !$active->is_expired)
    @php
        $daysLeft  = $active->days_remaining;
        $endDate   = \Carbon\Carbon::parse($active->end_date);
        $isWarning = $daysLeft <= 7;
    @endphp
    <div class="mb-6 rounded-2xl p-1 {{ $isWarning ? 'bg-gradient-to-r from-orange-400 to-red-500' : 'bg-gradient-to-r from-emerald-500 to-teal-600' }}">
        <div class="bg-white rounded-xl px-6 py-4 flex flex-col sm:flex-row sm:items-center gap-4">
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-1">
                    <span class="inline-flex items-center gap-1.5 {{ $isWarning ? 'bg-orange-100 text-orange-700' : 'bg-emerald-100 text-emerald-700' }} text-xs font-bold px-3 py-1 rounded-full">
                        <i class="fa-solid fa-circle-check text-xs"></i> PREMIUM AKTIF
                    </span>
                    @if($isWarning)
                        <span class="text-xs font-semibold text-red-600 animate-pulse"><i class="fa-solid fa-triangle-exclamation mr-1"></i>Segera Perpanjang!</span>
                    @endif
                </div>
                <p class="font-bold text-gray-900 text-lg">{{ $active->package_name }}</p>
                <p class="text-sm text-gray-500">Berlaku hingga <strong>{{ $endDate->format('d F Y') }}</strong></p>
            </div>
            <div class="text-center shrink-0 min-w-[140px]">
                <div id="kab-premium-countdown" data-expiry="{{ $endDate->toIso8601String() }}"
                    class="text-2xl font-black {{ $isWarning ? 'text-red-500' : 'text-emerald-600' }} tracking-tighter">
                    {{ $daysLeft }} Hari
                </div>
                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Sisa Waktu Premium</div>
                <script>
                    function updateKabCountdown() {
                        const el = document.getElementById('kab-premium-countdown');
                        if (!el) return;
                        const expiry = new Date(el.dataset.expiry).getTime();
                        const now    = new Date().getTime();
                        const diff   = expiry - now;
                        if (diff <= 0) { el.innerHTML = "EXPIRED"; return; }
                        const days    = Math.floor(diff / (1000 * 60 * 60 * 24));
                        const hours   = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                        const seconds = Math.floor((diff % (1000 * 60)) / 1000);
                        el.innerHTML  = `${days}h ${hours}j ${minutes}m ${seconds}s`;
                    }
                    setInterval(updateKabCountdown, 1000);
                    updateKabCountdown();
                </script>
                @php
                    $totalDays = \Carbon\Carbon::parse($active->start_date)->diffInDays($endDate) ?: 1;
                    $pct       = max(0, min(100, round($daysLeft / $totalDays * 100)));
                @endphp
                <div class="w-32 h-2 bg-gray-200 rounded-full mt-2 mx-auto overflow-hidden">
                    <div class="{{ $isWarning ? 'bg-red-500' : 'bg-emerald-500' }} h-full rounded-full transition-all" style="width: {{ $pct }}%"></div>
                </div>
            </div>
        </div>
    </div>
@endif

{{-- Alert: Ada tagihan pending --}}
@if($pending && $snapToken)
<div class="bg-amber-50 border border-amber-300 rounded-xl p-4 mb-5 flex flex-col sm:flex-row items-start sm:items-center gap-4">
    <div class="text-amber-500 text-2xl mt-0.5 hidden sm:block"><i class="fa-solid fa-circle-exclamation"></i></div>
    <div class="flex-1">
        <p class="font-bold text-amber-700">Tagihan Belum Dibayar</p>
        <p class="text-sm text-amber-600 mt-1">Anda memiliki tagihan <strong>{{ $pending->package_name }}</strong> sebesar <strong>Rp {{ number_format($pending->amount, 0, ',', '.') }}</strong> yang belum diselesaikan.</p>
    </div>
    <div class="flex flex-wrap gap-2 w-full sm:w-auto mt-2 sm:mt-0">
        <button id="btn-pay-pending"
            data-token="{{ $snapToken }}"
            onclick="openMidtrans(this)"
            class="flex-1 sm:flex-none justify-center bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-lg text-sm font-bold transition-colors flex items-center gap-2 shadow">
            <i class="fa-solid fa-credit-card"></i> Bayar
        </button>
        <form action="{{ route('adminkab.langganan.destroy', $pending->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')" class="flex-1 sm:flex-none flex">
            @csrf @method('DELETE')
            <button type="submit" class="w-full justify-center bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-lg text-sm font-bold transition-colors flex items-center gap-2 shadow-sm">
                Batalkan
            </button>
        </form>
    </div>
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
                @foreach($langganans as $lang)
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
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal: Pilih Paket -->
<div id="modal-add" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center p-4">
    <div class="relative w-full max-w-lg shadow-2xl rounded-2xl bg-white border overflow-hidden">
        <div class="flex justify-between items-center p-5 border-b bg-gray-50">
            <h3 class="text-xl font-bold text-gray-900"><i class="fa-solid fa-clock-rotate-left text-accent mr-2"></i> Perpanjang Masa Aktif Premium</h3>
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
