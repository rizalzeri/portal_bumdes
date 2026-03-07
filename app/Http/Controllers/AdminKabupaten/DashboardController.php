<?php

namespace App\Http\Controllers\AdminKabupaten;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bumdesa;
use App\Models\Langganan;

class DashboardController extends Controller
{
    public function index()
    {
        $kabupatenId = auth()->user()->kabupaten_id;

        $totalBumdes = Bumdesa::where('kabupaten_id', $kabupatenId)->count();
        $activeBumdes = Bumdesa::where('kabupaten_id', $kabupatenId)->where('status', 'active')->count();

        // Count subscription active vs inactive within this kabupaten
        $subscriptionStats = Langganan::whereHas('bumdes', function ($q) use ($kabupatenId) {
            $q->where('kabupaten_id', $kabupatenId);
        })->selectRaw('status, count(*) as count')->groupBy('status')->pluck('count', 'status')->toArray();

        // Get latest BUMDes registered in this kabupaten
        $latestBumdes = Bumdesa::where('kabupaten_id', $kabupatenId)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('adminkab.dashboard', compact(
            'totalBumdes', 
            'activeBumdes', 
            'subscriptionStats', 
            'latestBumdes'
        ));
    }
}
