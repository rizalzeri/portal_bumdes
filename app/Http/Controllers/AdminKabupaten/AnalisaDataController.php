<?php

namespace App\Http\Controllers\AdminKabupaten;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bumdesa;

class AnalisaDataController extends Controller
{
    public function index(Request $request)
    {
        $kabupatenId = auth()->user()->kabupaten_id;
        $tab = $request->get('tab', 'aktif');
        $query = Bumdesa::where('kabupaten_id', $kabupatenId)->with(['user']);

        switch ($tab) {
            case 'aktif':
                $query->where(function($q) {
                    $q->where('status', 'active')->orWhere('is_active', true);
                });
                break;
            case 'tidak_aktif':
                $query->where(function($q) {
                    $q->where('status', '!=', 'active')->where('is_active', false);
                });
                break;
            case 'berbadan_hukum':
                $query->whereNotNull('nomor_sertifikat')->where('nomor_sertifikat', '!=', '');
                break;
            case 'belum_berbadan_hukum':
                $query->where(function($q) {
                    $q->whereNull('nomor_sertifikat')->orWhere('nomor_sertifikat', '');
                });
                break;
            case 'kategori_usaha':
                $query->whereHas('unitUsaha');
                if ($request->filled('filter_val')) {
                    $query->whereHas('unitUsaha', fn($q) => $q->where('unit_usaha_option_id', $request->filter_val));
                }
                break;
            case 'komoditas_pangan':
                $query->whereHas('produkKetahananPangans');
                if ($request->filled('filter_val')) {
                    $query->whereHas('produkKetahananPangans', fn($q) => $q->where('produk_ketapang_option_id', $request->filter_val));
                }
                break;
            case 'pemeringkatan':
                $query->whereNotNull('klasifikasi')->where('klasifikasi', '!=', '');
                if ($request->filled('filter_val')) {
                    $query->where('klasifikasi', $request->filter_val);
                }
                break;
            case 'omset_regular':
                // Join or sort by Laporan Keuangan Omset/Laba etc.
                // Simplified: Must have laporan keuangan
                $query->whereHas('laporanKeuangan');
                $query->withSum('laporanKeuangan', 'pendapatan');
                $query->withSum('laporanKeuangan', 'laba_rugi');
                $query->orderBy('laporan_keuangan_sum_pendapatan', 'desc');
                break;
            case 'omset_ketapang':
                // Must have ketapang and laporan keuangan
                $query->whereHas('produkKetahananPangans')->whereHas('laporanKeuangan');
                $query->withSum('laporanKeuangan', 'pendapatan');
                $query->withSum('laporanKeuangan', 'laba_rugi');
                $query->orderBy('laporan_keuangan_sum_pendapatan', 'desc');
                break;
            case 'mitra':
                $query->whereHas('mitraKerjasama');
                break;
            default:
                break;
        }

        $bumdes = $query->paginate(20)->withQueryString();

        $kategoriList = \App\Models\UnitUsahaOption::all();
        $komoditasList = \App\Models\ProdukKetapangOption::all();

        return view('adminkab.analisa_data.index', compact('bumdes', 'tab', 'kategoriList', 'komoditasList'));
    }
}
