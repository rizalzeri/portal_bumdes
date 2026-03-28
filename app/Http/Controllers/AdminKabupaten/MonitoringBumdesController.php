<?php

namespace App\Http\Controllers\AdminKabupaten;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bumdesa;

class MonitoringBumdesController extends Controller
{
    public function index(Request $request)
    {
        $kabupatenId = auth()->user()->kabupaten_id;
        $tab = $request->get('tab', 'sudah_mengirim');
        $query = Bumdesa::where('kabupaten_id', $kabupatenId)->with(['user']);

        switch ($tab) {
            case 'sudah_mengirim':
                // Already sending documents can mean having reports or transparency standards
                $query->whereHas('laporanKeuangan');
                break;
            case 'belum_mengirim':
                $query->whereDoesntHave('laporanKeuangan');
                break;
            case 'perhatian_khusus':
                // Perhatian khusus can be BUMDesa that are inactive or have missing critical info
                $query->where(function ($q) {
                    $q->whereNull('nomor_sertifikat')
                      ->orWhere('status', 'inactive')
                      ->orWhereDoesntHave('laporanKeuangan');
                });
                break;
        }

        $bumdes = $query->paginate(20)->withQueryString();

        return view('adminkab.monitoring.index', compact('bumdes', 'tab'));
    }
}
