<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Langganan;
use App\Models\Bumdesa;

class LanggananController extends Controller
{
    public function index()
    {
        $bumdes = Bumdesa::where('user_id', auth()->id())->firstOrFail();
        
        // Cek tagihan yang pending
        $bill = Langganan::where('bumdes_id', $bumdes->id)
            ->where('status', 'pending')
            ->first();
            
        // History pembayaran
        $riwayat = Langganan::where('bumdes_id', $bumdes->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $snapToken = null;

        // Ensure Midtrans configuration is set up properly before requesting snap token
        if ($bill) {
            \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            $orderId = 'BILL-' . $bill->id . '-' . time();
            
            // Assume 1 million rp per year for "Paket Premium BUMDes", or 0 if "Paket Dasar"
            // Midtrans requires minimum Rp. 10.000
            $grossAmount = ($bill->package_name === 'Paket Dasar (Gratis)') ? 0 : 1000000 * (\Carbon\Carbon::parse($bill->end_date)->diffInYears($bill->start_date) ?: 1);

            $params = array(
                'transaction_details' => array(
                    'order_id' => $orderId,
                    'gross_amount' => $grossAmount,
                ),
                'customer_details' => array(
                    'first_name' => $bumdes->name,
                    'email' => auth()->user()->email,
                    'phone' => $bumdes->phone ?? '080000000000',
                ),
                'item_details' => array(
                    array(
                        'id' => 'ITEM1',
                        'price' => $grossAmount,
                        'quantity' => 1,
                        'name' => $bill->package_name
                    )
                )
            );

            // only request if amount > 0 and Midtrans is configured properly
            if ($grossAmount > 0 && env('MIDTRANS_SERVER_KEY')) {
                try {
                    $snapToken = \Midtrans\Snap::getSnapToken($params);
                } catch (\Exception $e) {
                    // Fail silent, standard template var null
                    \Log::error("Midtrans Snap Error: " . $e->getMessage());
                }
            } else if ($grossAmount == 0) {
                 // Langsung auto-aktif bebas biaya
                 $bill->update(['status' => 'active']);
                 return redirect()->route('user.langganan.index')->with('success', 'Paket langganan gratis langsung diaktifkan!');
            }
        }

        return view('user.langganan.index', compact('bumdes', 'bill', 'riwayat', 'snapToken'));
    }

    public function successCallback(Request $request)
    {
        // For simple redirect logic after Midtrans modal is closed (success payment)
        // Usually, the validation is happening via Midtrans Webhook (S2S notification).
        // For demonstration, we will just update status to active manually for User Demo:
        
        $bumdes = Bumdesa::where('user_id', auth()->id())->firstOrFail();
        
        $bill = Langganan::where('bumdes_id', $bumdes->id)
            ->where('status', 'pending')
            ->first();

        if ($bill) {
            $bill->update([
                'status' => 'active'
            ]);
            return redirect()->route('user.langganan.index')->with('success', 'Pembayaran berhasil dikonfirmasi! Paket premium Anda telah aktif.');
        }

        return redirect()->route('user.langganan.index');
    }
}
