<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Province;
use App\Models\Kabupaten;
use App\Models\Bumdes;
use App\Models\InfografisData;

class PublicController extends Controller
{
    public function index()
    {
        $latestBumdes = Bumdes::with(['kabupaten.province'])
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
            
        return view('public.home', compact('latestBumdes'));
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
            'personils'
        ])->where('slug', $slug)->firstOrFail();

        return view('public.bumdes.show', compact('bumdes'));
    }

    public function infografis()
    {
        $nationalData = InfografisData::whereNull('province_id')->whereNull('kabupaten_id')->first();
        $provincialData = InfografisData::whereNotNull('province_id')->whereNull('kabupaten_id')->with('province')->get();
        
        // Let's pass all infografis data to be rendered by chart.js
        return view('public.infografis', compact('nationalData', 'provincialData'));
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
        $data = $this->fetchLocations($request);
        $items = [];
        if ($data['selectedKabupaten']) {
            $items = \App\Models\StandarTransparansi::whereHas('bumdes', function($q) use ($data) {
                $q->where('kabupaten_id', $data['selectedKabupaten']->id)->where('is_active', true);
            })->with('bumdes')->latest()->take(3)->get();
        }

        return view('public.content_page', array_merge($data, [
            'type' => 'materi',
            'routeName' => 'public.materi',
            'title' => 'Materi & Template',
            'desc' => 'Kumpulan materi edukasi dan template administrasi untuk BUMDesa.',
            'items' => $items,
            'anchor' => '#materi-template'
        ]));
    }

    public function papanInformasi(Request $request)
    {
        $data = $this->fetchLocations($request);
        $items = [];
        if ($data['selectedKabupaten']) {
            $items = \App\Models\Artikel::whereHas('bumdes', function($q) use ($data) {
                $q->where('kabupaten_id', $data['selectedKabupaten']->id)->where('is_active', true);
            })->with('bumdes')->latest()->take(3)->get();
        }

        return view('public.content_page', array_merge($data, [
            'type' => 'kabar',
            'routeName' => 'public.informasi',
            'title' => 'Kabar & Opini',
            'desc' => 'Berita terbaru, artikel pilihan, opini ahli, dan pengumuman resmi.',
            'items' => $items,
            'anchor' => '#kabar-opini'
        ]));
    }

    public function katalog(Request $request)
    {
        $data = $this->fetchLocations($request);
        $items = [];
        if ($data['selectedKabupaten']) {
            $items = \App\Models\ProdukBumdes::whereHas('bumdes', function($q) use ($data) {
                $q->where('kabupaten_id', $data['selectedKabupaten']->id)->where('is_active', true);
            })->with(['bumdes', 'produkOption'])->latest()->take(3)->get();
        }

        return view('public.content_page', array_merge($data, [
            'type' => 'katalog',
            'routeName' => 'public.katalog',
            'title' => 'Katalog Produk BUMDesa',
            'desc' => 'Eksplorasi ribuan produk unggulan BUMDesa dari seluruh Indonesia.',
            'items' => $items,
            'anchor' => '#produk-desa'
        ]));
    }

    public function mitra(Request $request)
    {
        $data = $this->fetchLocations($request);
        $items = [];
        if ($data['selectedKabupaten']) {
            $items = \App\Models\MitraKerjasama::whereHas('bumdes', function($q) use ($data) {
                $q->where('kabupaten_id', $data['selectedKabupaten']->id)->where('is_active', true);
            })->with('bumdes')->latest()->take(3)->get();
        }

        return view('public.content_page', array_merge($data, [
            'type' => 'mitra',
            'routeName' => 'public.mitra',
            'title' => 'Mitra Kerjasama',
            'desc' => 'Daftar mitra strategis yang bekerja sama dengan Portal BUMDesa.',
            'items' => $items,
            'anchor' => '#mitra'
        ]));
    }

    public function galeri(Request $request)
    {
        $data = $this->fetchLocations($request);
        $items = [];
        if ($data['selectedKabupaten']) {
            $items = \App\Models\Galeri::whereHas('bumdes', function($q) use ($data) {
                $q->where('kabupaten_id', $data['selectedKabupaten']->id)->where('is_active', true);
            })->with('bumdes')->latest()->take(3)->get();
        }

        return view('public.content_page', array_merge($data, [
            'type' => 'galeri',
            'routeName' => 'public.galeri',
            'title' => 'Galeri Kegiatan BUMDesa',
            'desc' => 'Dokumentasi foto dan video dari berbagai kegiatan BUMDesa.',
            'items' => $items,
            'anchor' => '#galeri-kegiatan'
        ]));
    }
}
