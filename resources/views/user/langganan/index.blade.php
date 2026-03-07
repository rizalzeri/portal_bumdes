@extends('layouts.admin')
@section('title', 'Langganan & Pembayaran')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Status Langganan & Tagihan</h2>
    <p class="text-gray-500 text-sm mt-1">Selesaikan tagihan yang tertunda menggunakan metode pembayaran dari Midtrans (Transfer Bank, e-Wallet, dll).</p>
</div>

<!-- Pending Bills Area -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    <div class="lg:col-span-1">
        @if($bill && $bill->status === 'pending')
        <div class="bg-gradient-to-br from-red-500 to-red-700 text-white rounded-xl shadow-lg border p-6 relative overflow-hidden">
            <div class="absolute -right-4 -bottom-4 text-red-900 opacity-20">
                <i class="fa-solid fa-file-invoice-dollar text-9xl"></i>
            </div>
            <h3 class="font-bold text-xl mb-1 relative z-10"><i class="fa-solid fa-triangle-exclamation mr-2"></i> Tagihan Menunggu</h3>
            <p class="text-sm text-red-100 mb-6 relative z-10">Terdapat 1 tagihan yang harus dibayar sebelum paket diaktifkan.</p>
            
            <div class="bg-white/20 p-4 rounded-lg backdrop-blur-sm relative z-10 mb-6">
                <div class="flex justify-between text-sm mb-2">
                    <span class="font-medium text-red-50">Paket</span>
                    <span class="font-bold">{{ $bill->package_name }}</span>
                </div>
                <div class="flex justify-between text-sm mb-2">
                    <span class="font-medium text-red-50">Durasi</span>
                    <span class="font-bold">{{ \Carbon\Carbon::parse($bill->end_date)->diffInYears($bill->start_date) ?: 1 }} Tahun</span>
                </div>
                <div class="flex justify-between text-sm border-t border-white/30 pt-2 mt-2">
                    <span class="font-bold text-red-50">Total Bayar</span>
                    <span class="font-bold text-lg">Rp 1.000.000</span>
                </div>
            </div>

            @if($snapToken)
                <button id="pay-button" class="w-full bg-white text-red-600 font-bold py-3 px-4 rounded-lg shadow hover:bg-gray-100 transition-colors relative z-10 z-index shadow-md">
                    Bayar Sekarang <i class="fa-solid fa-arrow-right ml-1"></i>
                </button>
            @else
                @if(env('MIDTRANS_SERVER_KEY'))
                    <button disabled class="w-full bg-red-400 text-white font-bold py-3 px-4 rounded-lg relative z-10 cursor-not-allowed">
                        <i class="fa-solid fa-spinner fa-spin mr-2"></i> Token Gagal Dibuat
                    </button>
                @else
                    <form action="{{ route('user.langganan.success') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-green-500 text-white font-bold py-3 px-4 rounded-lg hover:bg-green-600 transition-colors relative z-10 tooltip" title="Simulasi Konfirmasi Sukses (Dev Mode)">
                            <i class="fa-solid fa-check mr-2"></i> Konfirmasi Manual (Demo)
                        </button>
                        <p class="text-[10px] mt-2 text-red-100 text-center relative z-10">Konfigurasi Midtrans Key belum ada di .env. Gunakan tombol diatas untuk Bypass.</p>
                    </form>
                @endif
            @endif
        </div>
        @else
        <div class="bg-gradient-to-br from-green-500 to-green-700 text-white rounded-xl shadow-lg border p-6 relative overflow-hidden">
            <div class="absolute -right-4 -bottom-4 text-green-900 opacity-20">
                <i class="fa-solid fa-circle-check text-9xl"></i>
            </div>
            <h3 class="font-bold text-xl mb-1 relative z-10"><i class="fa-regular fa-face-smile mr-2"></i> Semua Lunas</h3>
            <p class="text-sm text-green-100 mb-6 relative z-10">Tidak ada tagihan yang tertunda saat ini. Nikmati akses seluruh ekosistem Portal BUMDesa.</p>
            
            @php $current = $riwayat->where('status', 'active')->first(); @endphp
            @if($current)
            <div class="bg-white/20 p-4 rounded-lg backdrop-blur-sm relative z-10 text-center">
                <span class="block text-xs uppercase font-bold text-green-100 tracking-wider mb-1">Paket Aktif Saat Ini</span>
                <span class="block font-black text-xl mb-2">{{ $current->package_name }}</span>
                <span class="inline-block bg-white text-green-600 text-xs px-3 py-1 rounded-full font-bold">
                    Berlaku s/d {{ \Carbon\Carbon::parse($current->end_date)->format('d M Y') }}
                </span>
            </div>
            @endif
        </div>
        @endif
    </div>

    <!-- History Area -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <h3 class="font-bold text-gray-800 mb-4 border-b pb-2"><i class="fa-solid fa-clock-rotate-left mr-2 text-primary"></i> Riwayat Langganan & Pembayaran</h3>
            <div class="table-responsive w-full overflow-x-auto">
                <table class="datatable w-full whitespace-nowrap text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-4 py-3">Tanggal Tagihan</th>
                            <th class="px-4 py-3">Paket</th>
                            <th class="px-4 py-3">Nominal (Estimasi)</th>
                            <th class="px-4 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayat as $r)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $r->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3 font-bold text-gray-900">{{ $r->package_name }}</td>
                            <td class="px-4 py-3 text-right font-medium">
                                {{ $r->package_name === 'Paket Dasar (Gratis)' ? 'Bebas Biaya' : 'Rp 1.000.000' }}
                            </td>
                            <td class="px-4 py-3">
                                @if($r->status === 'active')
                                    <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded border border-green-200">Lunas / Aktif</span>
                                @elseif($r->status === 'pending')
                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded border border-yellow-200">Menunggu</span>
                                @else
                                    <span class="bg-gray-100 text-gray-800 text-xs font-semibold px-2.5 py-0.5 rounded border border-gray-200">Expired</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-400">Belum ada riwayat langganan tercatat.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@if($snapToken)
    <!-- Midtrans Snap Script -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function(){
            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result){
                    // Lakukan proses submit jika success
                    let form = document.createElement("form");
                    form.method = "POST";
                    form.action = "{{ route('user.langganan.success') }}";
                    
                    let csrf = document.createElement("input");
                    csrf.type = "hidden";
                    csrf.name = "_token";
                    csrf.value = "{{ csrf_token() }}";
                    form.appendChild(csrf);

                    document.body.appendChild(form);
                    form.submit();
                },
                onPending: function(result){
                    alert("Menunggu pembayaran Anda diselesaikan!"); 
                },
                onError: function(result){
                    alert("Pembayaran gagal!"); 
                },
                onClose: function(){
                    alert('Anda menutup jendela pembayaran tanpa menyelesaikannya');
                }
            });
        };
    </script>
@endif
@endsection
