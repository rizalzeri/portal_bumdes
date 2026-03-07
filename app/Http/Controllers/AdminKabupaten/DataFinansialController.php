<?php

namespace App\Http\Controllers\AdminKabupaten;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bumdesa;
use App\Models\LaporanKeuangan;

class DataFinansialController extends Controller
{
    public function index()
    {
        $kabupatenId = auth()->user()->kabupaten_id;

        // Get all financial reports from BUMDes under this Kabupaten
        // We will fetch all active BUMDes in this kabupaten and their latest reports
        $bumdesList = Bumdesa::where('kabupaten_id', $kabupatenId)
            ->with(['laporanKeuangan' => function($q) {
                // Get the latest reports
                $q->orderBy('tahun', 'desc')->orderBy('bulan', 'desc');
            }])
            ->get();

        return view('adminkab.finansial.index', compact('bumdesList'));
    }

    public function show(Bumdesa $bumde)
    {
        // View details of a specific BUMDes financial reports
        if ($bumde->kabupaten_id !== auth()->user()->kabupaten_id) {
            abort(403);
        }

        $laporans = $bumde->laporanKeuangan()->orderBy('tahun', 'desc')->orderBy('bulan', 'desc')->get();
        return view('adminkab.finansial.show', compact('bumde', 'laporans'));
    }
}
