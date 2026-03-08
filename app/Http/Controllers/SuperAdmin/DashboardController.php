<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bumdes;
use App\Models\User;
use App\Models\Kabupaten;
use App\Models\InfografisData;
use App\Models\Langganan;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_admin_kabupaten' => User::where('role', 'admin_kabupaten')->count(),
            'total_bumdes_user' => User::where('role', 'user')->count(),
            'total_bumdes_aktif' => Bumdes::where('is_active', true)->count(),
            'total_kabupaten' => Kabupaten::count(),
            'langganan_aktif' => Langganan::where('status', 'active')->count()
        ];
        
        // Part 2: Real registration data for chart (Last 6 months)
        $chartData = collect(range(5, 0))->map(function ($i) {
            $date = now()->subMonths($i);
            $month = $date->format('M');
            $count = Bumdes::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            return [
                'label' => $month,
                'count' => $count
            ];
        });

        $chartLabels = $chartData->pluck('label');
        $chartValues = $chartData->pluck('count');

        // Latest registrations
        $latestBumdes = Bumdes::with('kabupaten.province')->orderBy('created_at', 'desc')->take(5)->get();

        return view('superadmin.dashboard', compact('stats', 'latestBumdes', 'chartLabels', 'chartValues'));
    }
}
