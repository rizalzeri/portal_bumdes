<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bumdesa;
use App\Models\KinerjaCapaian;

class KinerjaController extends Controller
{
    public function index($slug)
    {
        $bumdes = Bumdesa::where('user_id', auth()->id())
            ->orWhere('id', auth()->user()->bumdes_id)
            ->firstOrFail();

        $kinerja = KinerjaCapaian::where('bumdes_id', $bumdes->id)
            ->orderBy('year', 'desc')
            ->get();

        return view('user.kinerja.index', compact('bumdes', 'kinerja'));
    }

    public function store(Request $request, $slug)
    {
        $bumdes = Bumdesa::where('user_id', auth()->id())
            ->orWhere('id', auth()->user()->bumdes_id)
            ->firstOrFail();

        $request->validate([
            'kategori' => 'required|in:Reguler,Ketahanan Pangan',
            'item'     => 'required|string',
            'year'     => 'required|integer|min:2000|max:2100',
            'nominal'  => 'required|numeric',
        ]);

        KinerjaCapaian::create([
            'bumdes_id'   => $bumdes->id,
            'title'       => $request->item,
            'description' => $request->kategori,
            'year'        => $request->year,
            'value'       => $request->nominal,
        ]);

        return redirect()->route('user.kinerja.index', ['slug' => $slug])
            ->with('success', 'Data Kinerja berhasil ditambahkan!');
    }

    // Update digunakan khusus untuk update pemeringkatan BUMDesa
    public function update(Request $request, $slug, $kinerja)
    {
        $bumdes = Bumdesa::where('user_id', auth()->id())
            ->orWhere('id', auth()->user()->bumdes_id)
            ->firstOrFail();

        $request->validate([
            'pemeringkatan' => 'required|in:Maju,Berkembang,Perintis,Pemula',
            'pemeringkatan_tahun' => 'required|integer|min:2000|max:'.(date('Y')+1)
        ]);

        $bumdes->update([
            'pemeringkatan' => $request->pemeringkatan,
            'pemeringkatan_tahun' => $request->pemeringkatan_tahun
        ]);

        return redirect()->route('user.kinerja.index', ['slug' => $slug])
            ->with('success', 'Hasil Pemeringkatan berhasil diperbarui!');
    }

    public function destroy($slug, $kinerja)
    {
        $bumdes = Bumdesa::where('user_id', auth()->id())
            ->orWhere('id', auth()->user()->bumdes_id)
            ->firstOrFail();

        $data = KinerjaCapaian::where('bumdes_id', $bumdes->id)->findOrFail($kinerja);
        $data->delete();

        return redirect()->route('user.kinerja.index', ['slug' => $slug])
            ->with('success', 'Data Kinerja berhasil dihapus!');
    }
}
