@extends('layouts.admin')
@section('title', 'Langganan & Pembayaran')

@section('content')
{{-- ── Status Header ──────────────────────────────── --}}
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Langganan & Pembayaran</h2>
    <p class="text-gray-500 text-sm mt-1">Pilih paket premium BUMDesa Anda dan selesaikan pembayaran melalui Midtrans.</p>
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
                <div id="premium-countdown" data-expiry="{{ $endDate->toIso8601String() }}" class="text-2xl font-black {{ $isWarning ? 'text-red-500' : 'text-emerald-600' }} tracking-tighter">
                    {{ $daysLeft }} Hari
                </div>
                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Sisa Waktu Premium</div>


                <script>
                    function updateCountdown() {
                        const el = document.getElementById('premium-countdown');
                        if (!el) return;
                        
                        const expiry = new Date(el.dataset.expiry).getTime();
                        const now = new Date().getTime();
                        const diff = expiry - now;
                        
                        if (diff <= 0) {
                            el.innerHTML = "EXPIRED";
                            return;
                        }
                        
                        const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                        const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                        const seconds = Math.floor((diff % (1000 * 60)) / 1000);
                        
                        el.innerHTML = `${days}h ${hours}j ${minutes}m ${seconds}s`;
                    }
                    setInterval(updateCountdown, 1000);
                    updateCountdown();
                </script>
                {{-- Countdown bar --}}
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

{{-- ── Pending Payment Banner ──────────────────────── --}}
@if($pending)
    <div id="pending-banner" class="mb-6 bg-amber-50 border border-amber-200 rounded-xl px-5 py-4 flex items-start gap-3">
        <i class="fa-solid fa-clock-rotate-left text-amber-500 text-xl mt-0.5"></i>
        <div class="flex-1">
            <p class="font-bold text-amber-800">Tagihan Menunggu Pembayaran</p>
            <p class="text-sm text-amber-700 mt-0.5">
                <strong>{{ $pending->package_name }}</strong> —
                <strong>Rp {{ number_format($pending->amount, 0, ',', '.') }}</strong>
            </p>
        </div>
        <div class="flex gap-2 shrink-0">
            @if($snapToken)
                <button id="pay-pending-btn" class="bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-credit-card"></i> Bayar Sekarang
                </button>
            @endif
            <form action="{{ route('user.langganan.destroy', [auth()->user()->username, $pending->id]) }}" method="POST" onsubmit="return confirm('Batalkan pesanan ini?')">
                @csrf @method('DELETE')
                <button type="submit" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-600 text-sm font-semibold px-4 py-2 rounded-lg transition-colors">
                    Batalkan
                </button>
            </form>
        </div>
    </div>
@endif

{{-- ── Plan Cards ──────────────────────────────────── --}}
<div class="mb-8">
    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
        <i class="fa-solid fa-rocket text-primary"></i>
        {{ $active && !$active->is_expired ? 'Perpanjang Langganan' : 'Pilih Paket Premium' }}
    </h3>

    @if($plans->isEmpty())
        <div class="bg-gray-50 border border-dashed border-gray-300 rounded-xl p-8 text-center text-gray-400">
            <i class="fa-solid fa-box-open text-4xl mb-3"></i>
            <p>Belum ada paket harga yang tersedia. Hubungi admin.</p>
        </div>
    @else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach($plans as $plan)
            @php
                $isPopular     = $plan->months === 6;
                $totalPrice    = $plan->total_price;
                $isBestValue   = $plan->months === 12;
            @endphp
            <div class="relative bg-white rounded-2xl border-2 {{ $isPopular ? 'border-primary shadow-lg shadow-primary/10' : 'border-gray-100' }} p-5 flex flex-col transition hover:shadow-md hover:-translate-y-0.5">
                @if($isPopular)
                    <div class="absolute -top-3 left-1/2 -translate-x-1/2">
                        <span class="bg-primary text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider shadow whitespace-nowrap">Paling Populer</span>
                    </div>
                @elseif($isBestValue)
                    <div class="absolute -top-3 left-1/2 -translate-x-1/2">
                        <span class="bg-emerald-600 text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider shadow whitespace-nowrap">Nilai Terbaik</span>
                    </div>
                @endif

                <div class="flex-1">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">{{ $plan->months }} Bulan</p>
                    <h4 class="text-lg font-black text-gray-900 mb-2">{{ $plan->name }}</h4>
                    @if($plan->description)
                        <p class="text-xs text-gray-500 mb-4">{{ $plan->description }}</p>
                    @endif

                    <div class="mb-4">
                        <span class="text-3xl font-black text-primary">Rp {{ number_format($plan->base_price_per_month, 0, ',', '.') }}</span>
                        <span class="text-gray-400 text-sm">/bln</span>
                        @if($plan->months > 1)
                            <div class="text-xs text-gray-500 mt-1">
                                Total: <strong class="text-gray-800">Rp {{ number_format($totalPrice, 0, ',', '.') }}</strong>
                            </div>
                        @endif
                    </div>

                    <ul class="text-xs text-gray-600 space-y-1.5 mb-5">
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-500"></i> Akses semua fitur premium</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-500"></i> Upload foto galeri unlimited</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-500"></i> Laporan keuangan lengkap</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-500"></i> Prioritas dukungan teknis</li>
                    </ul>
                </div>

                <form action="{{ route('user.langganan.store', auth()->user()->username) }}" method="POST">
                    @csrf
                    <input type="hidden" name="pricing_config_id" value="{{ $plan->id }}">
                    <button type="submit"
                        class="w-full {{ $isPopular ? 'bg-primary text-white hover:bg-primary/90' : 'bg-gray-900 text-white hover:bg-gray-800' }} py-2.5 rounded-xl text-sm font-bold transition-colors">
                        Pilih Paket
                    </button>
                </form>
            </div>
        @endforeach
    </div>
    @endif
</div>

{{-- ── Riwayat Pembayaran ──────────────────────────── --}}
<div class="bg-white rounded-xl shadow-sm border p-6">
    <h3 class="font-bold text-gray-800 mb-4 border-b pb-2 flex items-center gap-2">
        <i class="fa-solid fa-clock-rotate-left text-primary"></i> Riwayat Langganan & Pembayaran
    </h3>
    <div class="overflow-x-auto">
        <table id="riwayat-table" class="w-full whitespace-nowrap text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th class="px-4 py-3">Tanggal</th>
                    <th class="px-4 py-3">Paket</th>
                    <th class="px-4 py-3">Durasi</th>
                    <th class="px-4 py-3 text-right">Nominal</th>
                    <th class="px-4 py-3 text-center">Masa Berlaku</th>
                    <th class="px-4 py-3 text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($riwayat as $r)
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="px-4 py-3 text-xs text-gray-500">{{ $r->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-4 py-3 font-semibold text-gray-900">{{ $r->package_name }}</td>
                    <td class="px-4 py-3">{{ $r->duration_months ?? '-' }} bulan</td>
                    <td class="px-4 py-3 text-right font-medium text-gray-800">
                        {{ $r->amount > 0 ? 'Rp ' . number_format($r->amount, 0, ',', '.') : 'Gratis' }}
                    </td>
                    <td class="px-4 py-3 text-center text-xs">
                        @if($r->end_date)
                            s/d {{ \Carbon\Carbon::parse($r->end_date)->format('d M Y') }}
                            @if($r->status === 'active' && !$r->is_expired)
                                <span class="block font-bold {{ $r->days_remaining <= 7 ? 'text-red-500' : 'text-emerald-600' }}">
                                    ({{ $r->days_remaining }} hari lagi)
                                </span>
                            @endif
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center">
                        @if($r->status === 'active' && !$r->is_expired)
                            <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded-full border border-green-200">Aktif</span>
                        @elseif($r->status === 'pending')
                            <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded-full border border-yellow-200">Menunggu</span>
                        @elseif($r->status === 'active' && $r->is_expired)
                            <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded-full border border-red-200">Kadaluarsa</span>
                        @else
                            <span class="bg-gray-100 text-gray-700 text-xs font-semibold px-2.5 py-0.5 rounded-full border border-gray-200">{{ ucfirst($r->status) }}</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- ── Midtrans Snap JS ────────────────────────────── --}}
@if($snapToken)
<script>
    // Use production or sandbox URL based on config
    const isProduction = {{ config('services.midtrans.is_production', false) ? 'true' : 'false' }};
    const snapUrl = isProduction
        ? 'https://app.midtrans.com/snap/snap.js'
        : 'https://app.sandbox.midtrans.com/snap/snap.js';

    const clientKey = '{{ config('services.midtrans.client_key') }}';

    // Dynamically load Snap script
    const script = document.createElement('script');
    script.src = snapUrl;
    script.setAttribute('data-client-key', clientKey);
    document.head.appendChild(script);

    const successUrl = '{{ route('user.langganan.success', auth()->user()->username) }}';
    const snapToken  = '{{ $snapToken }}';

    function openSnap() {
        snap.pay(snapToken, {
            onSuccess: function(result) {
                window.location.href = successUrl;
            },
            onPending: function(result) {
                Swal.fire({
                    icon: 'info',
                    title: 'Pembayaran Tertunda',
                    text: 'Pembayaran Anda masih diproses. Kami akan mengaktifkan paket segera setelah pembayaran dikonfirmasi.',
                    confirmButtonColor: '#1e3a5f',
                });
            },
            onError: function(result) {
                Swal.fire({ icon: 'error', title: 'Pembayaran Gagal', text: 'Silakan coba lagi.', confirmButtonColor: '#1e3a5f' });
            },
            onClose: function() {
                // Do nothing, let user close
            }
        });
    }

    // Auto-open if redirected after store
    @if(session('open_payment'))
    script.onload = function() { openSnap(); };
    @endif

    // Pay pending button
    const payBtn = document.getElementById('pay-pending-btn');
    if (payBtn) {
        payBtn.addEventListener('click', function() { openSnap(); });
    }
</script>
@endif

@push('scripts')
<script>
    // Initialize DataTable for riwayat without auto-initializing via class
    $(document).ready(function() {
        $('#riwayat-table').DataTable({
            order: [[0, 'desc']],
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ entri",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                paginate: { first: "Pertama", last: "Terakhir", next: "Lanjut", previous: "Kembali" },
                emptyTable: "Belum ada riwayat langganan."
            }
        });
    });
</script>
@endpush
@endsection
