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
        $bumdes = collect();
        if ($request->has('kabupaten_id')) {
            $selectedKabupaten = Kabupaten::find($request->kabupaten_id);
            if ($selectedKabupaten) {
                $query = Bumdes::where('kabupaten_id', $request->kabupaten_id)->where('is_active', true);
                if ($request->has('q')) {
                    $query->where('name', 'like', '%' . $request->q . '%');
                }
                $bumdes = $query->orderBy('name')->get();
                $selectedProvince = $selectedKabupaten->province;
            }
        } elseif ($request->has('q')) {
            // If they search without selecting Kabupaten, maybe we don't return here but wait for them to select, OR we just let it be. But wait, if they are not in Kabupaten, they are in Province or Country.
            // Let's just adjust if NO kabupaten selected BUT have q... actually, let's just let them continue selecting.
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

        // User requested: "Papan Pengumuman BUMDesa : Cukup tampilkan pengumuman yang dibuat BUMDesa"
        // So we just use the native $bumdes->pengumuman relationship.

        $labels = [];
        $pendapatanData = [];
        $labaData = [];

        // Bangun kinerjaTahunan dari kinerjaCapaians (bukan laporanKeuangans)
        // Struktur: { thn, reguler: {omset,laba,pades,aset}, ketapang: {omset,laba,pades,aset} }
        $kinerjaTahunanRaw = [];
        foreach ($bumdes->kinerjaCapaians as $kinerja) {
            $year = (string) ($kinerja->year ?? date('Y'));
            if (!isset($kinerjaTahunanRaw[$year])) {
                $kinerjaTahunanRaw[$year] = [
                    'thn'      => $year,
                    'reguler'  => ['omset' => 0, 'laba' => 0, 'pades' => 0, 'aset' => 0],
                    'ketapang' => ['omset' => 0, 'laba' => 0, 'pades' => 0, 'aset' => 0],
                ];
            }
            $kategori = strtolower($kinerja->description ?? '');
            $item     = strtolower($kinerja->title ?? '');
            $value    = (float) ($kinerja->value ?? 0);

            $group = str_contains($kategori, 'ketahanan') || str_contains($kategori, 'pangan')
                ? 'ketapang' : 'reguler';

            if (str_contains($item, 'omset'))      $kinerjaTahunanRaw[$year][$group]['omset'] += $value;
            elseif (str_contains($item, 'laba'))   $kinerjaTahunanRaw[$year][$group]['laba']  += $value;
            elseif (str_contains($item, 'pades') || str_contains($item, 'pad'))
                                                    $kinerjaTahunanRaw[$year][$group]['pades'] += $value;
            elseif (str_contains($item, 'aset') || str_contains($item, 'modal'))
                                                    $kinerjaTahunanRaw[$year][$group]['aset']  += $value;
        }

        $kinerjaTahunan = array_values($kinerjaTahunanRaw);
        usort($kinerjaTahunan, fn($a, $b) => (int)$b['thn'] <=> (int)$a['thn']); // sort by year desc

        return view('public.bumdes.show', compact('bumdes', 'labels', 'pendapatanData', 'labaData', 'kinerjaTahunan'));
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
        
        // Cek status premium admin kabupaten via tabel langganans (konsisten dengan sidebar)
        $isPremiumKab = \App\Models\Langganan::where('kabupaten_id', $id)
            ->whereNull('bumdes_id')
            ->where('status', 'active')
            ->where('end_date', '>', now())
            ->exists();

        if (!$isPremiumKab) {
            abort(403, 'Infografis Perkembangan BUMDesa tidak tersedia (Status Admin Kabupaten Offline).');
        }

        $bumdesIds = \App\Models\Bumdesa::where('kabupaten_id', $id)->pluck('id');

        $total_bumdes = count($bumdesIds);
        $active_bumdes = \App\Models\Bumdesa::where('kabupaten_id', $id)->where('is_active', true)->count();
        $inactive_bumdes = $total_bumdes - $active_bumdes;
        $berbadan_hukum = \App\Models\Bumdesa::where('kabupaten_id', $id)->whereNotNull('badan_hukum')->count();

        $units_by_category = \App\Models\UnitUsahaAktif::whereIn('bumdes_id', $bumdesIds)->where('is_active', true)
            ->join('unit_usaha_options', 'unit_usaha_aktifs.unit_usaha_option_id', '=', 'unit_usaha_options.id')
            ->selectRaw('unit_usaha_options.name, count(DISTINCT unit_usaha_aktifs.bumdes_id) as total')
            ->groupBy('unit_usaha_options.name')
            ->orderByDesc('total')
            ->get();

        $ketapang_by_category = \App\Models\ProdukKetahananPangan::whereIn('bumdes_id', $bumdesIds)
            ->join('produk_ketapang_options', 'produk_ketahanan_pangans.produk_ketapang_option_id', '=', 'produk_ketapang_options.id')
            ->selectRaw('produk_ketapang_options.name, count(DISTINCT produk_ketahanan_pangans.bumdes_id) as total')
            ->groupBy('produk_ketapang_options.name')
            ->orderByDesc('total')
            ->get();

        $rawKlasifikasi = ['Maju' => 0, 'Berkembang' => 0, 'Pemula' => 0, 'Perintis' => 0];
        $klasifikasiList = \App\Models\Bumdesa::where('kabupaten_id', $id)->pluck('klasifikasi');
        foreach ($klasifikasiList as $k) {
            $kl  = strtolower($k ?? '');
            if (str_contains($kl, 'maju')) $rawKlasifikasi['Maju']++;
            elseif (str_contains($kl, 'berkembang')) $rawKlasifikasi['Berkembang']++;
            elseif (str_contains($kl, 'pemula') || str_contains($kl, 'dasar')) $rawKlasifikasi['Pemula']++;
            else $rawKlasifikasi['Perintis']++;
        }

        $perkembangan = \App\Models\LaporanKeuangan::whereIn('bumdes_id', $bumdesIds)
            ->selectRaw('COALESCE(tahun, year) as thn, SUM(pendapatan) as omset, SUM(total_aset) as aset, SUM(laba_rugi) as pades')
            ->groupBy('thn')
            ->orderBy('thn', 'desc')
            ->get();

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

        $dalam_pantauan_khusus = \App\Models\Bumdesa::where('kabupaten_id', $id)->whereDoesntHave('laporanKeuangan')->count();

        $mitra_kerjasama = \App\Models\MitraKerjasama::whereIn('bumdes_id', $bumdesIds)
            ->selectRaw('name as mitra, count(DISTINCT bumdes_id) as total')
            ->groupBy('name')
            ->orderByDesc('total')
            ->get();

        $pengumumans = \App\Models\Pengumuman::where('kabupaten_id', $id)->latest()->get();

        return view('public.infografis_kabupaten', compact(
            'kabupaten', 'total_bumdes', 'active_bumdes', 'inactive_bumdes', 'berbadan_hukum',
            'units_by_category', 'ketapang_by_category', 'rawKlasifikasi', 'perkembangan',
            'monitoring', 'dalam_pantauan_khusus', 'mitra_kerjasama', 'pengumumans'
        ));
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
        $items = \App\Models\Pengumuman::where('type', 'portal')
            ->with(['bumdes.kabupaten', 'kabupaten'])
            ->latest()
            ->paginate(12);

        return view('public.content_page', [
            'type'      => 'pengumuman',
            'routeName' => 'public.pengumuman',
            'title'     => 'Pengumuman Platform Portal',
            'desc'      => 'Informasi penting, agenda resmi, dan pengumuman dari Tim Portal Pusat BUMDesa.',
            'items'     => $items,
            'anchor'    => '#pengumuman',
        ]);
    }

    public function pengumumanKabupaten(Request $request)
    {
        $items = \App\Models\Pengumuman::where('type', 'kabupaten')
            ->with(['bumdes.kabupaten', 'kabupaten'])
            ->latest()
            ->paginate(12);

        return view('public.content_page', [
            'type'      => 'pengumuman',
            'routeName' => 'public.pengumuman.kabupaten',
            'title'     => 'Pengumuman Dinas PMD Kabupaten',
            'desc'      => 'Informasi dan edaran resmi dari Pemerintah Kabupaten untuk BUMDesa.',
            'items'     => $items,
            'anchor'    => '#pengumuman',
        ]);
    }

    public function pengumumanBumdes(Request $request)
    {
        $items = \App\Models\Pengumuman::where('type', 'bumdes')
            ->whereHas('bumdes', fn($bq) => $bq->where('is_active', true))
            ->with(['bumdes.kabupaten', 'kabupaten'])
            ->latest()
            ->paginate(12);

        return view('public.content_page', [
            'type'      => 'pengumuman',
            'routeName' => 'public.pengumuman.bumdes',
            'title'     => 'Pengumuman dari BUMDesa',
            'desc'      => 'Informasi, layanan, dan pengumuman operasional langsung dari unit BUMDesa.',
            'items'     => $items,
            'anchor'    => '#pengumuman',
        ]);
    }

    public function about()
    {
        return view('public.about');
    }

    public function services()
    {
        return view('public.services');
    }

    public function faq()
    {
        return view('public.faq');
    }

    public function contact()
    {
        return view('public.contact');
    }

    public function searchBumdes(Request $request)
    {
        $q = $request->q;
        if (!$q || strlen($q) < 2) {
            return response()->json([]);
        }

        $bumdes = Bumdes::where('name', 'like', '%' . $q . '%')
            ->where('is_active', true)
            ->take(8)
            ->get(['name', 'slug', 'desa']);

        $results = $bumdes->map(function ($b) {
            return [
                'name' => 'BUMDesa ' . $b->name . ' Desa ' . ($b->desa ?? ''),
                'url' => route('public.bumdes.profile', $b->slug)
            ];
        });

        return response()->json($results);
    }
}
