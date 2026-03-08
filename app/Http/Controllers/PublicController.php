<?php

namespace App\Http\Controllers;

use App\Models\Bumdes;
use App\Models\Kabupaten;
use App\Models\Province;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index()
    {
        // 3 BUMDes terbaru untuk section "Spill BUMDes Terbaru"
        $latestBumdes = Bumdes::with(['kabupaten.province'])
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        // Preview items per menu
        $previewBumdes = Bumdes::with('kabupaten')
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        $previewKatalog = \App\Models\KatalogProduk::with('bumdes')
            ->where(fn($q) => $q->whereNull('bumdes_id')->orWhereHas('bumdes', fn($bq) => $bq->where('is_active', true)))
            ->where('is_featured', true)
            ->latest()
            ->take(4)
            ->get();

        $previewArtikel = \App\Models\Artikel::with('bumdes')
            ->where(fn($q) => $q->whereNull('bumdes_id')->orWhereHas('bumdes', fn($bq) => $bq->where('is_active', true)))
            ->where('is_featured', true)
            ->latest()
            ->take(4)
            ->get();

        $previewMitra = \App\Models\MitraKerjasama::with('bumdes')
            ->where(fn($q) => $q->whereNull('bumdes_id')->orWhereHas('bumdes', fn($bq) => $bq->where('is_active', true)))
            ->where('is_featured', true)
            ->latest()
            ->take(4)
            ->get();

        $previewGaleri = \App\Models\Galeri::with('bumdes')
            ->where(fn($q) => $q->whereNull('bumdes_id')->orWhereHas('bumdes', fn($bq) => $bq->where('is_active', true)))
            ->where('is_featured', true)
            ->latest()
            ->take(4)
            ->get();
            
        $previewMateri = \App\Models\StandarTransparansi::with('bumdes')
            ->where(fn($q) => $q->whereNull('bumdes_id')->orWhereHas('bumdes', fn($bq) => $bq->where('is_active', true)))
            ->where('is_featured', true)
            ->latest()
            ->take(4)
            ->get();

        $previewPengumuman = \App\Models\Pengumuman::with('bumdes')
            ->where(fn($q) => $q->whereNull('bumdes_id')->orWhereHas('bumdes', fn($bq) => $bq->where('is_active', true)))
            ->where('is_featured', true)
            ->latest()
            ->take(4)
            ->get();

        return view('public.home', compact(
            'latestBumdes',
            'previewBumdes',
            'previewKatalog',
            'previewArtikel',
            'previewMitra',
            'previewGaleri',
            'previewMateri',
            'previewPengumuman'
        ));
    }

    public function kunjungiBumdes(Request $request)
    {
        // Step 1: All provinces
        $provinces = Province::orderBy('name')->get()->unique('name');

        // Step 2 & 3: If province selected
        $selectedProvince = null;
        $kabupatens = [];
        if ($request->has('province_id')) {
            $selectedProvince = Province::find($request->province_id);
            $kabupatens = Kabupaten::where('province_id', $request->province_id)->orderBy('name')->get();
        }

        // Step 4: If kabupaten selected
        $selectedKabupaten = null;
        $bumdes = [];
        if ($request->has('kabupaten_id')) {
            $selectedKabupaten = Kabupaten::find($request->kabupaten_id);
            $bumdes = Bumdes::where('kabupaten_id', $request->kabupaten_id)->where('is_active', true)->orderBy('name')->get();
            // Need to reload province to match kabupaten if user jumped straight to kabupaten
            if ($selectedKabupaten) {
                $selectedProvince = $selectedKabupaten->province;
            }
        }

        return view('public.bumdes.index', compact('provinces', 'selectedProvince', 'kabupatens', 'selectedKabupaten', 'bumdes'));
    }

    public function bumdesProfile($slug)
    {
        $bumdes = Bumdes::with([
            'unitUsahaAktifs.unitUsahaOption',
            'produkBumdes.produkOption',
            'produkKetahananPangans.produkKetapangOption',
            'mitraKerjasamas',
            'kinerjaCapaians',
            'transparansis',
            'pengumuman',
            'artikels',
            'galeris',
            'katalogProduks',
            'personils',
            'laporanKeuangans' => function($q) {
                $q->orderBy('tahun', 'asc')->orderBy('bulan', 'asc');
            }
        ])->where('slug', $slug)->firstOrFail();

        // Prepare data for chart
        $labels = [];
        $pendapatanData = [];
        $labaData = [];

        foreach($bumdes->laporanKeuangans as $lap) {
            $monthName = config('app.months')[$lap->bulan] ?? mb_substr(date('F', mktime(0, 0, 0, max(1, $lap->bulan), 1)), 0, 3);
            $year = $lap->tahun ?? $lap->year ?? date('Y');
            $labels[] = $monthName . ' ' . substr($year, -2);
            $pendapatanData[] = $lap->pendapatan;
            $labaData[] = $lap->laba_rugi ?? $lap->laba_bersih ?? 0;
        }

        return view('public.bumdes.show', compact('bumdes', 'labels', 'pendapatanData', 'labaData'));
    }

    public function infografis()
    {
        // Real National Data
        $totalBumdes = Bumdes::count();
        $bumdesAktif = Bumdes::where('is_active', true)->count();
        $totalPendapatan = \App\Models\LaporanKeuangan::sum('pendapatan');
        $totalUnitUsaha = \App\Models\UnitUsahaAktif::count();

        $nationalData = (object) [
            'total_bumdes' => $totalBumdes,
            'bumdes_aktif' => $bumdesAktif,
            'total_pendapatan' => $totalPendapatan,
            'total_unit_usaha' => $totalUnitUsaha,
        ];

        // Provincal Top 4 Data
        $provincialDataRaw = \Illuminate\Support\Facades\DB::table('bumdes')
            ->join('kabupatens', 'bumdes.kabupaten_id', '=', 'kabupatens.id')
            ->join('provinces', 'kabupatens.province_id', '=', 'provinces.id')
            ->select('provinces.name as label', \Illuminate\Support\Facades\DB::raw('count(bumdes.id) as total'))
            ->groupBy('provinces.id', 'provinces.name')
            ->orderByDesc('total')
            ->take(4)
            ->get();

        $provLabels = $provincialDataRaw->pluck('label')->toArray();
        $provData = $provincialDataRaw->pluck('total')->toArray();

        // Trend Aset by Year
        $trendRaw = \Illuminate\Support\Facades\DB::table('laporan_keuangans')
            ->selectRaw('COALESCE(tahun, year) as thn, SUM(COALESCE(total_aset, 0)) as total_all_aset')
            ->groupBy('thn')
            ->orderBy('thn', 'desc')
            ->take(4)
            ->get()
            ->reverse();

        $trendLabels = $trendRaw->pluck('thn')->toArray();
        $trendData = $trendRaw->map(fn ($item) => floor($item->total_all_aset / 1000000000))->toArray(); // in Miliar

        return view('public.infografis', compact('nationalData', 'provLabels', 'provData', 'trendLabels', 'trendData'));
    }

    private function fetchLocations(Request $request)
    {
        $provinces = Province::orderBy('name')->get()->unique('name');

        $selectedProvince = null;
        $kabupatens = [];
        if ($request->has('province_id')) {
            $selectedProvince = Province::find($request->province_id);
            $kabupatens = Kabupaten::where('province_id', $request->province_id)->orderBy('name')->get();
        }

        $selectedKabupaten = null;
        if ($request->has('kabupaten_id')) {
            $selectedKabupaten = Kabupaten::find($request->kabupaten_id);
            if ($selectedKabupaten) {
                $selectedProvince = $selectedKabupaten->province;
            }
        }

        return compact('provinces', 'selectedProvince', 'kabupatens', 'selectedKabupaten');
    }

    public function materi(Request $request)
    {
        $items = \App\Models\StandarTransparansi::where(fn($q) => $q->whereNull('bumdes_id')->orWhereHas('bumdes', fn($bq) => $bq->where('is_active', true)))
            ->with(['bumdes.kabupaten'])
            ->latest()
            ->paginate(12);

        return view('public.content_page', [
            'type' => 'materi',
            'routeName' => 'public.materi',
            'title' => 'Materi & Template',
            'desc' => 'Kumpulan materi edukasi dan template administrasi untuk BUMDesa di seluruh Indonesia.',
            'items' => $items,
            'anchor' => '#materi-template',
        ]);
    }

    public function papanInformasi(Request $request)
    {
        $items = \App\Models\Artikel::where(fn($q) => $q->whereNull('bumdes_id')->orWhereHas('bumdes', fn($bq) => $bq->where('is_active', true)))
            ->with(['bumdes.kabupaten'])
            ->latest()
            ->paginate(12);

        return view('public.content_page', [
            'type' => 'kabar',
            'routeName' => 'public.informasi',
            'title' => 'Kabar & Opini',
            'desc' => 'Berita terbaru, artikel pilihan, opini ahli perkembangan desa.',
            'items' => $items,
            'anchor' => '#kabar-opini',
        ]);
    }

    public function katalog(Request $request)
    {
        $items = \App\Models\KatalogProduk::where(fn($q) => $q->whereNull('bumdes_id')->orWhereHas('bumdes', fn($bq) => $bq->where('is_active', true)))
            ->with(['bumdes.kabupaten'])
            ->latest()
            ->paginate(12);

        return view('public.content_page', [
            'type' => 'katalog',
            'routeName' => 'public.katalog',
            'title' => 'Katalog Produk BUMDesa',
            'desc' => 'Eksplorasi ribuan produk unggulan BUMDesa dari seluruh Indonesia.',
            'items' => $items,
            'anchor' => '#produk-desa',
        ]);
    }

    public function mitra(Request $request)
    {
        $items = \App\Models\MitraKerjasama::where(fn($q) => $q->whereNull('bumdes_id')->orWhereHas('bumdes', fn($bq) => $bq->where('is_active', true)))
            ->with(['bumdes.kabupaten'])
            ->latest()
            ->paginate(12);

        return view('public.content_page', [
            'type' => 'mitra',
            'routeName' => 'public.mitra',
            'title' => 'Mitra Kerjasama',
            'desc' => 'Daftar mitra strategis yang bekerja sama dengan Portal BUMDesa.',
            'items' => $items,
            'anchor' => '#mitra',
        ]);
    }

    public function galeri(Request $request)
    {
        $items = \App\Models\Galeri::where(fn($q) => $q->whereNull('bumdes_id')->orWhereHas('bumdes', fn($bq) => $bq->where('is_active', true)))
            ->with(['bumdes.kabupaten'])
            ->latest()
            ->paginate(12);

        return view('public.content_page', [
            'type' => 'galeri',
            'routeName' => 'public.galeri',
            'title' => 'Galeri Kegiatan BUMDesa',
            'desc' => 'Dokumentasi foto dan video dari berbagai kegiatan BUMDesa di seluruh Indonesia.',
            'items' => $items,
            'anchor' => '#galeri-kegiatan',
        ]);
    }

    public function pengumuman(Request $request)
    {
        $items = \App\Models\Pengumuman::where(fn($q) => $q->whereNull('bumdes_id')->orWhereHas('bumdes', fn($bq) => $bq->where('is_active', true)))
            ->with(['bumdes.kabupaten'])
            ->latest()
            ->paginate(12);

        return view('public.content_page', [
            'type' => 'pengumuman',
            'routeName' => 'public.pengumuman',
            'title' => 'Pengumuman Portal',
            'desc' => 'Informasi penting, agenda kegiatan, dan berita resmi terkini.',
            'items' => $items,
            'anchor' => '#pengumuman',
        ]);
    }
}
