<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bumdesa;

class DashboardController extends Controller
{
    public function index()
    {
        $bumdes = Bumdesa::where('user_id', auth()->id())
            ->withCount(['unitUsaha', 'katalogProduk', 'artikel', 'laporanKeuangan'])
            ->firstOrFail();

        $latestReports = $bumdes->laporanKeuangan()->orderBy('tahun', 'desc')->orderBy('bulan', 'desc')->take(3)->get();
        $subscription = $bumdes->langganans()->latest()->first();

        return view('user.dashboard', compact('bumdes', 'latestReports', 'subscription'));
    }
}
