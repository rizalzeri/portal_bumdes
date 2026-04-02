@extends('layouts.admin')
@section('title', 'Langganan BUMDesa')

@section('content')

{{-- ── Status Card ──────────────────────────────────── --}}
@if($active && !$active->is_expired)
{{-- ======== PREMIUM STATUS ======== --}}
@php
    $daysLeft  = $active->days_remaining;
    $endDate   = \Carbon\Carbon::parse($active->end_date);
    $isWarning = $daysLeft <= 7;
    $totalDays = \Carbon\Carbon::parse($active->start_date)->diffInDays($endDate) ?: 1;
    $pct       = max(0, min(100, round($daysLeft / $totalDays * 100)));
@endphp

<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Status Langganan</h2>
    <p class="text-gray-500 text-sm mt-1">Informasi paket premium BUMDesa Anda saat ini.</p>
</div>

{{-- Premium Badge Card --}}
<div class="mb-8 rounded-2xl p-1 {{ $isWarning ? 'bg-gradient-to-r from-orange-400 to-red-500' : 'bg-gradient-to-r from-emerald-500 to-teal-600' }} shadow-lg">
    <div class="bg-white rounded-xl px-6 py-6 flex flex-col sm:flex-row sm:items-center gap-6">
        {{-- Left: Status info --}}
        <div class="flex-1">
            <div class="flex items-center gap-3 mb-3">
                <span class="inline-flex items-center gap-2 {{ $isWarning ? 'bg-orange-100 text-orange-700 border-orange-200' : 'bg-emerald-100 text-emerald-700 border-emerald-200' }} text-sm font-bold px-4 py-1.5 rounded-full border">
                    <i class="fa-solid fa-crown text-sm"></i> PREMIUM AKTIF
                </span>
                @if($isWarning)
                    <span class="text-xs font-semibold text-red-600 animate-pulse"><i class="fa-solid fa-triangle-exclamation mr-1"></i>Segera Perpanjang!</span>
                @endif
            </div>
            <h3 class="text-xl font-black text-gray-900 mb-1">{{ $active->package_name }}</h3>
            <p class="text-sm text-gray-500 mb-4">Berlaku hingga <strong class="{{ $isWarning ? 'text-red-600' : 'text-emerald-700' }}">{{ $endDate->translatedFormat('d F Y') }}</strong></p>
            
            {{-- Progress bar --}}
            <div class="mb-1">
                <div class="flex justify-between text-xs text-gray-500 mb-1">
                    <span>Sisa waktu berlangganan</span>
                    <span class="font-bold {{ $isWarning ? 'text-red-500' : 'text-emerald-600' }}">{{ $pct }}%</span>
                </div>
                <div class="w-full h-2.5 bg-gray-200 rounded-full overflow-hidden">
                    <div class="{{ $isWarning ? 'bg-red-500' : 'bg-emerald-500' }} h-full rounded-full transition-all" style="width: {{ $pct }}%"></div>
                </div>
            </div>
        </div>

        {{-- Right: Countdown --}}
        <div class="text-center shrink-0 min-w-[180px] bg-gray-50 rounded-xl p-4 border">
            <div class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Sisa Waktu</div>
            <div id="premium-countdown" data-expiry="{{ $endDate->toIso8601String() }}" class="text-3xl font-black {{ $isWarning ? 'text-red-500' : 'text-emerald-600' }} tracking-tighter font-mono">
                {{ $daysLeft }} Hari
            </div>
            <div class="text-xs text-gray-400 mt-1">{{ $endDate->translatedFormat('d M Y, H:i') }}</div>
        </div>
    </div>
</div>

{{-- Renew Section --}}
<div class="mb-6">
    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
        <i class="fa-solid fa-rotate-right text-primary"></i> Perpanjang Langganan
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
                $isPopular   = $plan->months === 6;
                $isBestValue = $plan->months === 12;
                $totalPrice  = $plan->total_price;
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
                        Perpanjang Paket Ini
                    </button>
                </form>
            </div>
        @endforeach
    </div>
    @endif
</div>

@else
{{-- ======== FREE STATUS ======== --}}
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Status Langganan</h2>
    <p class="text-gray-500 text-sm mt-1">Anda saat ini menggunakan paket gratis. Upgrade ke Premium untuk akses penuh.</p>
</div>

{{-- Free Badge Card --}}
<div class="mb-8 rounded-2xl p-1 bg-gradient-to-r from-gray-300 to-gray-400 shadow">
    <div class="bg-white rounded-xl px-6 py-6 flex flex-col sm:flex-row sm:items-center gap-6">
        <div class="flex-1">
            <div class="flex items-center gap-3 mb-3">
                <span class="inline-flex items-center gap-2 bg-gray-100 text-gray-600 border border-gray-200 text-sm font-bold px-4 py-1.5 rounded-full">
                    <i class="fa-solid fa-lock text-sm"></i> STATUS: GRATIS
                </span>
            </div>
            <h3 class="text-xl font-black text-gray-900 mb-1">Paket Free</h3>
            <p class="text-sm text-gray-500 mb-4">Beberapa fitur terkunci. Upgrade ke Premium untuk membuka semua fitur.</p>
            
            {{-- Locked features list --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-2">
                @php
                    $lockedFeatures = ['Produk Desa', 'Laporan Keuangan Lengkap', 'Galeri Unlimited', 'Kinerja & Capaian', 'Mitra Kerjasama', 'Artikel & Opini', 'Papan Pengumuman', 'Transparansi'];
                    $freeFeatures = ['Profil BUMDesa', 'Unit Usaha', 'Ketahanan Pangan', 'Personil / Struktur'];
                @endphp
                @foreach($freeFeatures as $f)
                    <div class="flex items-center gap-2 text-sm text-emerald-700">
                        <i class="fa-solid fa-check text-emerald-500 text-xs w-4"></i> {{ $f }}
                    </div>
                @endforeach
                @foreach($lockedFeatures as $f)
                    <div class="flex items-center gap-2 text-sm text-gray-400">
                        <i class="fa-solid fa-lock text-gray-300 text-xs w-4"></i> {{ $f }}
                    </div>
                @endforeach
            </div>
        </div>
        <div class="shrink-0 text-center">
            <div class="bg-gray-100 rounded-xl p-6 border inline-block min-w-[150px]">
                <div class="text-4xl font-black text-gray-400 mb-1">FREE</div>
                <div class="text-xs text-gray-400 font-bold uppercase tracking-widest">Paket Aktif</div>
            </div>
        </div>
    </div>
</div>

{{-- Pending Payment Banner --}}
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

{{-- Upgrade Section --}}
<div class="mb-6">
    <h3 class="text-lg font-bold text-gray-800 mb-2 flex items-center gap-2">
        <i class="fa-solid fa-rocket text-primary"></i> Upgrade ke Premium
    </h3>
    <p class="text-sm text-gray-500 mb-4">Pilih durasi berlangganan yang sesuai kebutuhan BUMDesa Anda.</p>

    @if($plans->isEmpty())
        <div class="bg-gray-50 border border-dashed border-gray-300 rounded-xl p-8 text-center text-gray-400">
            <i class="fa-solid fa-box-open text-4xl mb-3"></i>
            <p>Belum ada paket harga yang tersedia. Hubungi admin.</p>
        </div>
    @else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach($plans as $plan)
            @php
                $isPopular   = $plan->months === 6;
                $isBestValue = $plan->months === 12;
                $totalPrice  = $plan->total_price;
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
                        class="w-full {{ $isPopular ? 'bg-primary text-white hover:bg-primary/90' : 'bg-gray-900 text-white hover:bg-gray-800' }} py-2.5 rounded-xl text-sm font-bold transition-colors flex items-center justify-center gap-2">
                        <i class="fa-solid fa-rocket text-sm"></i> Upgrade Sekarang
                    </button>
                </form>
            </div>
        @endforeach
    </div>
    @endif
</div>

@endif

{{-- ── Midtrans Snap JS ────────────────────────────── --}}
@if($snapToken)
<script>
    const isProduction = {{ config('services.midtrans.is_production', false) ? 'true' : 'false' }};
    const snapUrl = isProduction
        ? 'https://app.midtrans.com/snap/snap.js'
        : 'https://app.sandbox.midtrans.com/snap/snap.js';
    const clientKey = '{{ config('services.midtrans.client_key') }}';

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
            onClose: function() {}
        });
    }

    @if(session('open_payment'))
    script.onload = function() { openSnap(); };
    @endif

    const payBtn = document.getElementById('pay-pending-btn');
    if (payBtn) {
        payBtn.addEventListener('click', function() { openSnap(); });
    }
</script>
@endif

{{-- Countdown Script for Premium --}}
@if($active && !$active->is_expired)
<script>
    function updateCountdown() {
        const el = document.getElementById('premium-countdown');
        if (!el) return;
        const expiry = new Date(el.dataset.expiry).getTime();
        const now = new Date().getTime();
        const diff = expiry - now;
        if (diff <= 0) { el.innerHTML = "EXPIRED"; return; }
        const days = Math.floor(diff / (1000 * 60 * 60 * 24));
        const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((diff % (1000 * 60)) / 1000);
        el.innerHTML = `${days}h ${hours}j ${minutes}m ${seconds}d`;
    }
    setInterval(updateCountdown, 1000);
    updateCountdown();
</script>
@endif

@endsection
