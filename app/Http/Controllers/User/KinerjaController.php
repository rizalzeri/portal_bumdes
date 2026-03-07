<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bumdesa;

class KinerjaController extends Controller
{
    public function index()
    {
        $bumdes = Bumdesa::where('user_id', auth()->id())
            ->with(['laporanKeuangan' => function($q) {
                // Get all reports sorted by year and month ascending for chart
                $q->orderBy('tahun', 'asc')->orderBy('bulan', 'asc');
            }])
            ->firstOrFail();

        // Prepare data for chart
        $labels = [];
        $pendapatanData = [];
        $labaData = [];

        foreach($bumdes->laporanKeuangan as $lap) {
            $monthName = config('app.months')[$lap->bulan] ?? mb_substr(date('F', mktime(0, 0, 0, $lap->bulan, 1)), 0, 3);
            $labels[] = $monthName . ' ' . substr($lap->tahun, 2);
            $pendapatanData[] = $lap->pendapatan;
            $labaData[] = $lap->laba_bersih;
        }

        return view('user.kinerja.index', compact('bumdes', 'labels', 'pendapatanData', 'labaData'));
    }
}
