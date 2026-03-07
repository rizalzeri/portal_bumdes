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
        
        // Show subscriptions specifically for BUMDesa in this Kabupaten
        // Or if the Kabupaten itself has a subscription
        $langganans = Langganan::whereHas('bumdes', function ($q) use ($kabupatenId) {
                $q->where('kabupaten_id', $kabupatenId);
            })
            ->with(['bumdes'])
            ->orderBy('created_at', 'desc')
            ->get();

        $bumdesList = Bumdesa::where('kabupaten_id', $kabupatenId)->orderBy('name')->get();

        return view('adminkab.langganan.index', compact('langganans', 'bumdesList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bumdes_id' => 'required|exists:bumdes,id',
            'package_name' => 'required|string|max:255',
            'duration' => 'required|integer|min:1', // duration in years
        ]);

        // Verifikasi kepemilikan
        $bumdes = Bumdesa::findOrFail($request->bumdes_id);
        if ($bumdes->kabupaten_id !== auth()->user()->kabupaten_id) {
            abort(403);
        }

        // Logic here is simple: Admin Kabupaten marks payment as 'pending' 
        // Or directly pays via Midtrans. As requested, we will create a pending subscription
        // and allow admin kab/bumdes to see it. (Midtrans implementation will be done on User side)

        Langganan::create([
            'bumdes_id' => $request->bumdes_id,
            'package_name' => $request->package_name,
            'status' => 'pending',
            'start_date' => now(),
            'end_date' => now()->addYears($request->duration),
        ]);

        return redirect()->route('adminkab.langganan.index')->with('success', 'Tagihan langganan berhasil dibuat.');
    }
}
