<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Bumdesa;
use App\Models\Langganan;
use App\Models\PricingConfig;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LanggananController extends Controller
{
    private function configureMidtrans(): void
    {
        \Midtrans\Config::$serverKey     = config('services.midtrans.server_key');
        \Midtrans\Config::$isProduction  = config('services.midtrans.is_production', false);
        \Midtrans\Config::$isSanitized   = true;
        \Midtrans\Config::$is3ds         = true;

        if (app()->environment('local')) {
            \Midtrans\Config::$curlOptions = [
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_HTTPHEADER     => []
            ];
        }
    }

    /**
     * Show subscription page with plan selector and history
     */
    public function index($slug)
    {
        $bumdes  = Bumdesa::where('user_id', '=', auth()->id())
            ->orWhere('id', '=', auth()->user()->bumdes_id)
            ->firstOrFail();

        $plans   = PricingConfig::active()->get();
        $active  = Langganan::where('bumdes_id', '=', $bumdes->id)
            ->where('status', '=', 'active')
            ->where('end_date', '>', now())
            ->orderBy('end_date', 'desc')
            ->first();

        $pending = Langganan::where('bumdes_id', '=', $bumdes->id)
            ->where('status', '=', 'pending')
            ->latest()
            ->first();

        $riwayat = Langganan::where('bumdes_id', '=', $bumdes->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $snapToken = null;

        // Selalu generate snap token BARU setiap page load.
        // Token Midtrans bisa kadaluarsa, dan order_id lama yang sudah
        // 'expire' di Midtrans tidak bisa dipakai lagi — perlu order_id baru.
        if ($pending) {
            $this->configureMidtrans();

            // Buat order_id baru setiap refresh agar Midtrans tidak menolak
            $orderId     = 'BUMDES-' . $bumdes->id . '-' . time();
            $grossAmount = (int) $pending->amount;

            $params = [
                'transaction_details' => [
                    'order_id'     => $orderId,
                    'gross_amount' => $grossAmount,
                ],
                'customer_details' => [
                    'first_name' => $bumdes->name,
                    'email'      => auth()->user()->email,
                    'phone'      => $bumdes->phone ?? '08000000000',
                ],
                'item_details' => [[
                    'id'       => 'PREMIUM-' . $pending->id,
                    'price'    => $grossAmount,
                    'quantity' => 1,
                    'name'     => $pending->package_name,
                ]],
            ];

            try {
                $snapToken = \Midtrans\Snap::getSnapToken($params);
                // Update order_id dan token baru agar webhook tetap bisa mencocokkan
                $pending->update([
                    'order_id'      => $orderId,
                    'payment_token' => $snapToken,
                ]);
            } catch (\Exception $e) {
                Log::error('Midtrans Snap Error: ' . $e->getMessage());
            }
        }

        return view('user.langganan.index', compact(
            'bumdes', 'plans', 'active', 'pending', 'riwayat', 'snapToken'
        ));
    }

    /**
     * Create a new pending subscription order
     */
    public function store(Request $request, $slug)
    {
        $request->validate([
            'pricing_config_id' => 'required|exists:pricing_configs,id',
        ]);

        $bumdes = Bumdesa::where('user_id', '=', auth()->id())
            ->orWhere('id', '=', auth()->user()->bumdes_id)
            ->firstOrFail();

        // Cancel any existing pending orders first
        Langganan::where('bumdes_id', '=', $bumdes->id)
            ->where('status', '=', 'pending')
            ->update(['status' => 'expired']);

        $plan        = PricingConfig::findOrFail($request->pricing_config_id);
        $totalAmount = $plan->total_price;
        $startDate   = now();
        $endDate     = now()->addMonths($plan->months);
        $orderId     = 'BUMDES-' . $bumdes->id . '-' . time();

        $langganan = Langganan::create([
            'bumdes_id'       => $bumdes->id,
            'package_name'    => $plan->name . ' (' . $plan->months . ' Bulan)',
            'order_id'        => $orderId,
            'amount'          => $totalAmount,
            'duration_months' => $plan->months,
            'status'          => 'pending',
            'start_date'      => $startDate,
            'end_date'        => $endDate,
        ]);

        // Generate Midtrans Snap Token
        $this->configureMidtrans();

        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => (int) $totalAmount,
            ],
            'customer_details' => [
                'first_name' => $bumdes->name,
                'email'      => auth()->user()->email,
                'phone'      => $bumdes->phone ?? '08000000000',
            ],
            'item_details' => [[
                'id'       => 'PREMIUM-' . $langganan->id,
                'price'    => (int) $totalAmount,
                'quantity' => 1,
                'name'     => $langganan->package_name,
            ]],
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            $langganan->update(['payment_token' => $snapToken]);
        } catch (\Exception $e) {
            Log::error('Midtrans Snap Store Error: ' . $e->getMessage());
        }

        return redirect()->route('user.langganan.index', $slug)
            ->with('open_payment', true)
            ->with('success', 'Pesanan berhasil dibuat! Selesaikan pembayaran Anda.');
    }

    /**
     * Midtrans Server-to-Server (S2S) Payment Notification Webhook
     * Called by Midtrans after payment is completed
     */
    public function notification(Request $request)
    {
        $this->configureMidtrans();

        try {
            $notification    = new \Midtrans\Notification();
            $transactionStatus = $notification->transaction_status;
            $fraudStatus       = $notification->fraud_status;
            $orderId           = $notification->order_id;

            $langganan = Langganan::where('order_id', '=', $orderId)->first();

            if (!$langganan) {
                return response()->json(['message' => 'Order not found'], 404);
            }

            if ($transactionStatus === 'capture') {
                if ($fraudStatus === 'accept') {
                    $langganan->update([
                        'status' => 'active',
                        'start_date' => now(),
                        'end_date' => now()->addMonths($langganan->duration_months ?? 1)
                    ]);
                }
            } elseif ($transactionStatus === 'settlement') {
                $langganan->update([
                    'status' => 'active',
                    'start_date' => now(),
                    'end_date' => now()->addMonths($langganan->duration_months ?? 1)
                ]);
            } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
                $langganan->update(['status' => 'expired']);
            } elseif ($transactionStatus === 'pending') {
                $langganan->update(['status' => 'pending']);
            }

            return response()->json(['message' => 'OK']);
        } catch (\Exception $e) {
            Log::error('Midtrans Notification Error: ' . $e->getMessage());
            return response()->json(['message' => 'Error'], 500);
        }
    }

    /**
     * Success redirect after payment (client-side)
     * Note: Real activation is handled by webhook above.
     * This just shows a success message.
     */
    public function successCallback(Request $request, $slug)
    {
        $bumdes = Bumdesa::where('user_id', '=', auth()->id())
            ->orWhere('id', '=', auth()->user()->bumdes_id)
            ->firstOrFail();

        // Check if Midtrans already activated via webhook
        $active = Langganan::where('bumdes_id', '=', $bumdes->id)
            ->where('status', '=', 'active')
            ->where('end_date', '>', now())
            ->first();

        if ($active) {
            return redirect()->route('user.langganan.index', $slug)
                ->with('success', 'Pembayaran berhasil! Paket premium Anda sudah aktif.');
        }

        // Fallback: activate the most recent pending if webhook hasn't fired yet
        $pending = Langganan::where('bumdes_id', '=', $bumdes->id)
            ->where('status', '=', 'pending')
            ->latest()
            ->first();

        if ($pending) {
            $pending->update([
                'status' => 'active',
                'start_date' => now(),
                'end_date' => now()->addMonths($pending->duration_months ?? 1)
            ]);
            return redirect()->route('user.langganan.index', $slug)
                ->with('success', 'Pembayaran berhasil dikonfirmasi! Paket premium Anda telah aktif.');
        }

        return redirect()->route('user.langganan.index', $slug)
            ->with('info', 'Pembayaran Anda sedang diverifikasi. Status akan diperbarui otomatis.');
    }

    /**
     * Cancel a pending subscription order
     */
    public function destroy($slug, Langganan $langganan)
    {
        $bumdes = Bumdesa::where('user_id', '=', auth()->id())
            ->orWhere('id', '=', auth()->user()->bumdes_id)
            ->firstOrFail();

        if ($langganan->bumdes_id !== $bumdes->id || $langganan->status !== 'pending') {
            abort(403);
        }

        $langganan->update(['status' => 'expired']);

        return redirect()->route('user.langganan.index', $slug)
            ->with('success', 'Pesanan berhasil dibatalkan.');
    }
}
