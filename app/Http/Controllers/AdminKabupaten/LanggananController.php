<?php

namespace App\Http\Controllers\AdminKabupaten;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Langganan;
use App\Models\Kabupaten;
use App\Models\PricingConfig;
use Illuminate\Support\Facades\Log;

class LanggananController extends Controller
{
    private function configureMidtrans(): void
    {
        \Midtrans\Config::$serverKey    = config('services.midtrans.server_key');
        \Midtrans\Config::$isProduction = config('services.midtrans.is_production', false);
        \Midtrans\Config::$isSanitized  = true;
        \Midtrans\Config::$is3ds        = true;

        if (app()->environment('local')) {
            \Midtrans\Config::$curlOptions = [
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_HTTPHEADER     => [],
            ];
        }
    }

    public function index()
    {
        $kabupatenId = auth()->user()->kabupaten_id;
        $kabupaten   = Kabupaten::findOrFail($kabupatenId);

        $langganans = Langganan::where('kabupaten_id', $kabupatenId)
            ->whereNull('bumdes_id')
            ->orderBy('created_at', 'desc')
            ->get();

        $pricingConfigs = PricingConfig::where('is_active', true)
            ->where('type', 'kabupaten')
            ->get();

        // Cek langganan aktif untuk countdown
        $active = Langganan::where('kabupaten_id', $kabupatenId)
            ->whereNull('bumdes_id')
            ->where('status', 'active')
            ->where('end_date', '>', now())
            ->orderBy('end_date', 'desc')
            ->first();

        // Cek apakah ada tagihan pending — kalau ada, generate snap token
        $pending = Langganan::where('kabupaten_id', $kabupatenId)
            ->whereNull('bumdes_id')
            ->where('status', 'pending')
            ->latest()
            ->first();

        $snapToken = null;
        if ($pending) {
            $this->configureMidtrans();

            // Selalu buat order_id BARU setiap page load.
            // order_id lama yang sudah expire di Midtrans tidak bisa dipakai lagi.
            $orderId     = 'KAB-' . $kabupatenId . '-' . time();
            $grossAmount = (int) $pending->amount;

            $params = [
                'transaction_details' => [
                    'order_id'     => $orderId,
                    'gross_amount' => $grossAmount,
                ],
                'customer_details' => [
                    'first_name' => $kabupaten->name,
                    'email'      => auth()->user()->email,
                    'phone'      => '08000000000',
                ],
                'item_details' => [[
                    'id'       => 'KAB-PREMIUM-' . $pending->id,
                    'price'    => $grossAmount,
                    'quantity' => 1,
                    'name'     => $pending->package_name,
                ]],
            ];

            try {
                $snapToken = \Midtrans\Snap::getSnapToken($params);
                // Simpan order_id baru dan token baru agar webhook bisa mencocokkan
                $pending->update([
                    'order_id'      => $orderId,
                    'payment_token' => $snapToken,
                ]);
            } catch (\Exception $e) {
                Log::error('Midtrans KAB Snap Error: ' . $e->getMessage());
            }
        }

        return view('adminkab.langganan.index', compact(
            'langganans', 'pricingConfigs', 'kabupaten', 'pending', 'snapToken', 'active'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pricing_config_id' => 'required|exists:pricing_configs,id',
        ]);

        $kabupatenId = auth()->user()->kabupaten_id;
        $kabupaten   = Kabupaten::findOrFail($kabupatenId);
        $config      = PricingConfig::findOrFail($request->pricing_config_id);

        if ($config->type !== 'kabupaten') {
            abort(403, 'Paket ini bukan untuk Kabupaten.');
        }

        // Batalkan pending sebelumnya
        Langganan::where('kabupaten_id', $kabupatenId)
            ->whereNull('bumdes_id')
            ->where('status', 'pending')
            ->update(['status' => 'expired']);

        $orderId   = 'KAB-' . $kabupatenId . '-' . time();
        $langganan = Langganan::create([
            'kabupaten_id'     => $kabupatenId,
            'pricing_config_id' => $config->id,
            'package_name'     => $config->name . ' (' . $config->months . ' Bulan)',
            'order_id'         => $orderId,
            'amount'           => $config->total_price,
            'duration_months'  => $config->months,
            'status'           => 'pending',
            'start_date'       => now(),
            'end_date'         => now()->addMonths($config->months),
            'payment_method'   => 'midtrans',
        ]);

        // Generate Midtrans Snap Token
        $this->configureMidtrans();

        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => (int) $config->total_price,
            ],
            'customer_details' => [
                'first_name' => $kabupaten->name,
                'email'      => auth()->user()->email,
                'phone'      => '08000000000',
            ],
            'item_details' => [[
                'id'       => 'KAB-PREMIUM-' . $langganan->id,
                'price'    => (int) $config->total_price,
                'quantity' => 1,
                'name'     => $langganan->package_name,
            ]],
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            $langganan->update(['payment_token' => $snapToken]);
        } catch (\Exception $e) {
            Log::error('Midtrans KAB Snap Store Error: ' . $e->getMessage());
        }

        return redirect()->route('adminkab.langganan.index')
            ->with('open_payment', true)
            ->with('success', 'Tagihan berhasil dibuat! Selesaikan pembayaran Anda.');
    }

    /**
     * Midtrans Notification Handler untuk Kabupaten
     * (Shared webhook di routes sudah handle via ULangganan::notification,
     *  tapi kita bisa handle di sini juga jika diperlukan)
     */
    public function successCallback()
    {
        $kabupatenId = auth()->user()->kabupaten_id;

        // Cari pending terakhir (atau yang sudah terupdate jadi active via webhook)
        $pending = Langganan::where('kabupaten_id', $kabupatenId)
            ->whereNull('bumdes_id')
            ->latest()
            ->first();

        if (!$pending) {
            return redirect()->route('adminkab.langganan.index');
        }

        if ($pending->status === 'active') {
            return redirect()->route('adminkab.langganan.index')
                ->with('success', 'Pembayaran berhasil! Paket premium Kabupaten Anda sudah aktif.');
        }

        if ($pending->status === 'pending') {
            // Cek ada langganan aktif sebelumnya, perpanjang dari sana
            $currentActive = Langganan::where('kabupaten_id', $kabupatenId)
                ->whereNull('bumdes_id')
                ->where('status', 'active')
                ->where('end_date', '>', now())
                ->where('id', '!=', $pending->id)
                ->orderBy('end_date', 'desc')
                ->first();
            $startFrom = $currentActive ? \Carbon\Carbon::parse($currentActive->end_date) : now();
            if ($currentActive) { $currentActive->update(['status' => 'expired']); }
            // Hapus pending lainnya
            Langganan::where('kabupaten_id', $kabupatenId)
                ->whereNull('bumdes_id')
                ->where('status', 'pending')
                ->where('id', '!=', $pending->id)
                ->update(['status' => 'expired']);
            $pending->update([
                'status'     => 'active',
                'start_date' => $startFrom,
                'end_date'   => $startFrom->copy()->addMonths($pending->duration_months ?? 1),
            ]);
            return redirect()->route('adminkab.langganan.index')
                ->with('success', 'Pembayaran berhasil dikonfirmasi!');
        }

        return redirect()->route('adminkab.langganan.index')
            ->with('info', 'Status langganan: ' . ucfirst($pending->status));
    }

    /**
     * Cancel a pending subscription order
     */
    public function destroy(Langganan $langganan)
    {
        $kabupatenId = auth()->user()->kabupaten_id;

        if ($langganan->kabupaten_id !== $kabupatenId || $langganan->status !== 'pending') {
            abort(403);
        }

        $langganan->update(['status' => 'expired']);

        return redirect()->route('adminkab.langganan.index')
            ->with('success', 'Pesanan berhasil dibatalkan.');
    }
}
