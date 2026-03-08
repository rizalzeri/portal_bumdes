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

        $previewKabupaten = Kabupaten::where('is_featured', true)
            ->withCount(['bumdes' => fn($q) => $q->where('is_active', true)])
            ->latest()
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

        $previewPengumumanPortal = \App\Models\Pengumuman::with(['bumdes', 'kabupaten'])
            ->where('type', 'portal')
            ->where('is_featured', true)
            ->latest()->take(4)->get();

        $previewPengumumanKabupaten = \App\Models\Pengumuman::with(['bumdes', 'kabupaten'])
            ->where('type', 'kabupaten')
            ->where('is_featured', true)
            ->latest()->take(4)->get();

        $previewPengumumanBumdes = \App\Models\Pengumuman::with(['bumdes', 'kabupaten'])
            ->where('type', 'bumdes')
            ->whereHas('bumdes', fn($bq) => $bq->where('is_active', true))
            ->where('is_featured', true)
            ->latest()->take(4)->get();

        return view('public.home', compact(
            'latestBumdes',
            'previewBumdes',
            'previewKabupaten',
            'previewKatalog',
            'previewArtikel',
            'previewMitra',
            'previewGaleri',
            'previewMateri',
            'previewPengumumanPortal',
            'previewPengumumanKabupaten',
            'previewPengumumanBumdes'
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

        // Fetch multiple types of announcements for this BUMDes page
        // 1. Own BUMDes announcements
        // 2. Kabupaten announcements for this BUMDes area
        // 3. Global portal announcements
        $mergedPengumuman = \App\Models\Pengumuman::with(['bumdes', 'kabupaten'])
            ->where(function ($q) use ($bumdes) {
                $q->where('bumdes_id', $bumdes->id)
                    ->orWhere(function ($sq) use ($bumdes) {
                        $sq->where('type', 'kabupaten')->where('kabupaten_id', $bumdes->kabupaten_id);
                    })
                    ->orWhere('type', 'portal');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Replace the default relationship-based pengumuman with the merged list
        $bumdes->setRelation('pengumuman', $mergedPengumuman);

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
        $kabupatens = Kabupaten::with('province')
            ->withCount(['bumdes' => fn($q) => $q->where('is_active', true)])
            ->orderBy('name')
            ->get();

        return view('public.infografis', compact('kabupatens'));
    }

    public function infografisKabupaten($id)
    {
        $kabupaten = \App\Models\Kabupaten::with('province')->findOrFail($id);
        $bumdesIds = \App\Models\Bumdesa::where('kabupaten_id', $id)->pluck('id');

        // Part 1: Detailed Profile stats
        $stats = (object) [
            // Counts for Cards
            'active_bumdes' => \App\Models\Bumdesa::where('kabupaten_id', $id)->where('is_active', true)->count(),
            'active_units' => \App\Models\UnitUsahaAktif::whereIn('bumdes_id', $bumdesIds)->where('is_active', true)->count(),
            'ketapang_prods' => \App\Models\ProdukKetahananPangan::whereIn('bumdes_id', $bumdesIds)->count(),
            'total_mitra' => \App\Models\MitraKerjasama::whereIn('bumdes_id', $bumdesIds)->count(),
            
            // Detail names
            'active_bumdes_names' => \App\Models\Bumdesa::where('kabupaten_id', $id)->where('is_active', true)->pluck('name')->unique(),
            'unit_names_list' => \App\Models\UnitUsahaAktif::whereIn('bumdes_id', $bumdesIds)->where('is_active', true)->pluck('name')->unique()->take(20),
            'ketapang_names' => \App\Models\ProdukKetahananPangan::whereIn('bumdes_id', $bumdesIds)->pluck('name')->unique()->take(20),
            'mitra_names' => \App\Models\MitraKerjasama::whereIn('bumdes_id', $bumdesIds)->pluck('name')->unique()->take(20),

            // Chart Data
            'bumdes_by_kecamatan' => \App\Models\Bumdesa::where('kabupaten_id', $id)->where('is_active', true)
                ->selectRaw('kecamatan, count(*) as total')->groupBy('kecamatan')->get(),
            
            'units_by_category' => \App\Models\UnitUsahaAktif::whereIn('bumdes_id', $bumdesIds)->where('is_active', true)
                ->join('unit_usaha_options', 'unit_usaha_aktifs.unit_usaha_option_id', '=', 'unit_usaha_options.id')
                ->selectRaw('unit_usaha_options.name, count(*) as total')
                ->groupBy('unit_usaha_options.name')->get(),
            
            'ketapang_by_category' => \App\Models\ProdukKetahananPangan::whereIn('bumdes_id', $bumdesIds)
                ->join('produk_ketapang_options', 'produk_ketahanan_pangans.produk_ketapang_option_id', '=', 'produk_ketapang_options.id')
                ->selectRaw('produk_ketapang_options.name, count(*) as total')
                ->groupBy('produk_ketapang_options.name')->get(),
        ];

        // Part 2: Perkembangan (Aggregate)
        $perkembangan = \App\Models\LaporanKeuangan::whereIn('bumdes_id', $bumdesIds)
            ->selectRaw('COALESCE(tahun, year) as thn, SUM(pendapatan) as omset, SUM(total_aset) as aset, SUM(laba_rugi) as pades')
            ->groupBy('thn')
            ->orderBy('thn', 'desc')
            ->get();

        // Part 3: Monitoring per Kecamatan
        $monitoring = \App\Models\Bumdesa::where('kabupaten_id', $id)
            ->selectRaw('kecamatan, count(*) as total')
            ->whereNotNull('kecamatan')
            ->groupBy('kecamatan')
            ->get()
            ->map(function($item) use ($id) {
                $item->sudah_mengirim = \App\Models\Bumdesa::where('kabupaten_id', $id)
                    ->where('kecamatan', $item->kecamatan)
                    ->whereHas('laporanKeuangan', function($q) {
                        $q->where('tahun', date('Y'))->orWhere('year', date('Y'));
                    })->count();
                $item->belum_mengirim = $item->total - $item->sudah_mengirim;
                return $item;
            });

        // Part 4: Pengumuman
        $pengumumans = \App\Models\Pengumuman::where('kabupaten_id', $id)
            ->latest()
            ->get();

        return view('public.infografis_kabupaten', compact('kabupaten', 'stats', 'perkembangan', 'monitoring', 'pengumumans'));
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
            ->with(['bumdes.kabupaten', 'kabupaten'])
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
