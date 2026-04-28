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
        $query = Bumdesa::where('kabupaten_id', $kabupatenId)->with(['user']);

        // 0. Kecamatan
        if ($request->filled('kecamatan') && $request->kecamatan !== 'semua') {
            $query->where('kecamatan', $request->kecamatan);
        }

        // 1. Keaktifan BUMDesa
        if ($request->filled('status') && $request->status !== 'semua') {
            if ($request->status === 'aktif') {
                $query->where(fn($q) => $q->where('status', 'active')->orWhere('is_active', true));
            } else {
                $query->where(fn($q) => $q->where('status', '!=', 'active')->where('is_active', false));
            }
        }

        // 2. Berbadan Hukum
        if ($request->filled('badan_hukum') && $request->badan_hukum !== 'semua') {
            if ($request->badan_hukum === 'berbadan_hukum') {
                $query->whereNotNull('nomor_sertifikat')->where('nomor_sertifikat', '!=', '')->where('nomor_sertifikat', '!=', 'proses');
            } elseif ($request->badan_hukum === 'proses') {
                $query->where('nomor_sertifikat', 'proses');
            } elseif ($request->badan_hukum === 'belum') {
                $query->where(fn($q) => $q->whereNull('nomor_sertifikat')->orWhere('nomor_sertifikat', ''));
            }
        }

        // 3. Kategori Unit Usaha
        if ($request->filled('kategori_usaha') && $request->kategori_usaha !== 'semua') {
            $query->whereHas('unitUsaha', fn($q) => $q->where('unit_usaha_option_id', $request->kategori_usaha));
        }

        // 4. Komoditas Pangan
        if ($request->filled('komoditas') && $request->komoditas !== 'semua') {
            $query->whereHas('produkKetahananPangans', fn($q) => $q->where('produk_ketapang_option_id', $request->komoditas));
        }

        // 5. Mitra Kerjasama
        if ($request->filled('mitra') && $request->mitra !== 'semua') {
            $query->whereHas('mitraKerjasama', fn($q) => $q->where('mitra_option_id', $request->mitra));
        }

        // 6. Hasil Pemeringkatan
        if ($request->filled('pemeringkatan') && $request->pemeringkatan !== 'semua') {
            $query->where('klasifikasi', $request->pemeringkatan);
        }

        $tahun = $request->get('tahun', date('Y'));
        
        // 7. Laporan Keuangan/Kinerja Sorting
        $kategori_keuangan = $request->get('kategori_keuangan', 'semua');
        $jenis_keuangan = $request->get('jenis_keuangan', 'semua');

        if ($kategori_keuangan !== 'semua' || $jenis_keuangan !== 'semua') {
            $kinerjaFilter = function ($q) use ($tahun, $jenis_keuangan, $kategori_keuangan) {
                $q->where('year', $tahun);
                
                if ($jenis_keuangan !== 'semua') {
                    if ($jenis_keuangan == 'omset') $q->where('title', 'like', '%Omset%');
                    elseif ($jenis_keuangan == 'laba') $q->where('title', 'like', '%Laba%');
                    elseif ($jenis_keuangan == 'pades') $q->where('title', 'like', '%PAD%');
                    elseif ($jenis_keuangan == 'aset') $q->where('title', 'like', '%Aset%');
                    elseif ($jenis_keuangan == 'dansos') $q->where(function($q2) {
                        $q2->where('title', 'like', '%Dana%')->orWhere('title', 'like', '%Sosial%')->orWhere('title', 'like', '%RTM%');
                    });
                }

                if ($kategori_keuangan !== 'semua') {
                    if ($kategori_keuangan == 'reguler') $q->where('description', 'like', '%Reguler%');
                    elseif ($kategori_keuangan == 'ketahanan_pangan') $q->where('description', 'like', '%Ketahanan%');
                    elseif ($kategori_keuangan == 'dbm') $q->where(function($q3) {
                        $q3->where('description', 'like', '%Bergulir%')->orWhere('description', 'like', '%DBM%');
                    });
                }
            };
            
            $query->whereHas('kinerjaCapaians', $kinerjaFilter);
            $query->withSum(['kinerjaCapaians as sort_value' => $kinerjaFilter], 'value')->orderByDesc('sort_value');
        }

        $bumdes = $query->paginate(20)->withQueryString();

        $kecamatanList = \App\Models\Kecamatan::where('kabupaten_id', $kabupatenId)->orderBy('name')->get();
        $kategoriList = \App\Models\UnitUsahaOption::orderBy('name')->get();
        $komoditasList = \App\Models\ProdukKetapangOption::orderBy('name')->get();
        $mitraList = class_exists('\App\Models\MitraOption') ? \App\Models\MitraOption::orderBy('name')->get() : [];
        $tahunList = \App\Models\LaporanKeuangan::select('tahun')->distinct()->orderByDesc('tahun')->pluck('tahun')->toArray();
        if(empty($tahunList) || !in_array(date('Y'), $tahunList)) {
            array_unshift($tahunList, date('Y'));
        }

        return view('adminkab.analisa_data.index', compact('bumdes', 'kecamatanList', 'kategoriList', 'komoditasList', 'mitraList', 'tahunList', 'tahun', 'kategori_keuangan', 'jenis_keuangan'));
    }
}
