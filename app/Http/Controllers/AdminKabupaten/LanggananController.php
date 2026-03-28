<?php

namespace App\Http\Controllers\AdminKabupaten;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Langganan;
use App\Models\Bumdesa;

class LanggananController extends Controller
{
    public function index()
    {
        $kabupatenId = auth()->user()->kabupaten_id;
        
        // Kabupaten manages its OWN subscription
        $langganans = Langganan::where('kabupaten_id', $kabupatenId)
            ->whereNull('bumdes_id')
            ->orderBy('created_at', 'desc')
            ->get();

        $pricingConfigs = \App\Models\PricingConfig::where('is_active', true)->where('type', 'kabupaten')->get();

        return view('adminkab.langganan.index', compact('langganans', 'pricingConfigs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pricing_config_id' => 'required|exists:pricing_configs,id',
            'payment_method' => 'required|string',
        ]);

        $kabupatenId = auth()->user()->kabupaten_id;
        $config = \App\Models\PricingConfig::findOrFail($request->pricing_config_id);

        if ($config->type !== 'kabupaten') {
            abort(403, 'Paket ini bukan untuk Kabupaten.');
        }

        // Logic here is simple: Admin Kabupaten marks payment as 'pending' 
        // Midtrans implementation will handle the rest.
        \App\Models\Langganan::create([
            'kabupaten_id' => $kabupatenId,
            'pricing_config_id' => $config->id,
            'package_name' => $config->name,
            'amount' => $config->total_price,
            'status' => 'pending',
            'start_date' => now(),
            'end_date' => now()->addMonths($config->months),
            'payment_method' => $request->payment_method,
            'order_id' => 'KAB-' . uniqid() . '-' . time(),
        ]);

        return redirect()->route('adminkab.langganan.index')->with('success', 'Tagihan langganan berhasil dibuat.');
    }
}
