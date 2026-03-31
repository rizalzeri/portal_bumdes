<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bumdesa;

class DashboardController extends Controller
{
    public function index()
    {
        $bumdes = \App\Models\Bumdesa::where('user_id', auth()->id())
            ->orWhere('id', auth()->user()->bumdes_id)
            ->withCount(['unitUsaha', 'katalogProduk', 'artikel', 'laporanKeuangan'])
            ->firstOrFail();

        $latestReports = $bumdes->laporanKeuangan()->orderBy('tahun', 'desc')->orderBy('bulan', 'desc')->take(3)->get();
        $subscription = $bumdes->langganans()->latest()->first();

        return view('user.dashboard', compact('bumdes', 'latestReports', 'subscription'));
    }

    public function readNotifications()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return redirect()->back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
    }
}
