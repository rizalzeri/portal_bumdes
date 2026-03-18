<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\PricingConfig;
use Illuminate\Http\Request;

class PricingConfigController extends Controller
{
    public function index()
    {
        $configs = PricingConfig::orderBy('months')->get();
        return view('superadmin.pricing_config.index', compact('configs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'                 => 'required|string|max:100',
            'months'               => 'required|integer|min:1|max:120',
            'base_price_per_month' => 'required|numeric|min:0',
            'description'          => 'nullable|string|max:255',
            'is_active'            => 'nullable|boolean',
        ]);

        PricingConfig::create([
            'name'                 => $request->name,
            'months'               => $request->months,
            'base_price_per_month' => $request->base_price_per_month,
            'description'          => $request->description,
            'is_active'            => $request->boolean('is_active', true),
        ]);

        return redirect()->route('superadmin.pricing-config.index')
            ->with('success', 'Paket harga berhasil ditambahkan.');
    }

    public function update(Request $request, PricingConfig $pricingConfig)
    {
        $request->validate([
            'name'                 => 'required|string|max:100',
            'months'               => 'required|integer|min:1|max:120',
            'base_price_per_month' => 'required|numeric|min:0',
            'description'          => 'nullable|string|max:255',
            'is_active'            => 'nullable|boolean',
        ]);

        $pricingConfig->update([
            'name'                 => $request->name,
            'months'               => $request->months,
            'base_price_per_month' => $request->base_price_per_month,
            'description'          => $request->description,
            'is_active'            => $request->boolean('is_active', true),
        ]);

        return redirect()->route('superadmin.pricing-config.index')
            ->with('success', 'Paket harga berhasil diperbarui.');
    }

    public function destroy(PricingConfig $pricingConfig)
    {
        $pricingConfig->delete();
        return redirect()->route('superadmin.pricing-config.index')
            ->with('success', 'Paket harga berhasil dihapus.');
    }
}
