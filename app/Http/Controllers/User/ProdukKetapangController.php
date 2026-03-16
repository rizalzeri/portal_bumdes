<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProdukKetahananPangan;
use App\Models\ProdukKetapangOption;
use App\Models\Bumdesa;
use Illuminate\Support\Facades\Storage;

class ProdukKetapangController extends Controller
{
    public function index()
    {
        $bumdes = Bumdesa::where('user_id', auth()->id())->orWhere('id', auth()->user()->bumdes_id)->firstOrFail();
        $produks = ProdukKetahananPangan::where('bumdes_id', $bumdes->id)
            ->orderBy('created_at', 'desc')
            ->get();
        $optionKategori = ProdukKetapangOption::orderBy('name')->get();

        return view('user.ketapang.index', compact('produks', 'bumdes', 'optionKategori'));
    }

    public function store(Request $request)
    {
        $bumdes = Bumdesa::where('user_id', auth()->id())->orWhere('id', auth()->user()->bumdes_id)->firstOrFail();

        $request->validate([
            'name'                    => 'nullable|string|max:255',
            'produk_ketapang_option_id' => 'required|exists:produk_ketapang_options,id',
            'produksi_pertahun'       => 'nullable|string|max:255',
            'description'             => 'nullable|string',
            'price'                   => 'nullable|numeric|min:0',
            'image'                   => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('ketapang', 'public');
        }

        ProdukKetahananPangan::create([
            'bumdes_id'                => $bumdes->id,
            'produk_ketapang_option_id' => $request->produk_ketapang_option_id,
            'name'                     => $request->name ?? 'Produk Ketapang',
            'produksi_pertahun'        => $request->produksi_pertahun,
            'description'              => $request->description,
            'price'                    => $request->price,
            'image'                    => $imagePath,
        ]);

        return redirect()->route('user.ketapang.index')
            ->with('success', 'Produk ketahanan pangan berhasil ditambahkan.');
    }

    public function update(Request $request, ProdukKetahananPangan $ketapang)
    {
        $bumdes = Bumdesa::where('user_id', auth()->id())->orWhere('id', auth()->user()->bumdes_id)->firstOrFail();
        if ($ketapang->bumdes_id !== $bumdes->id) abort(403);

        $request->validate([
            'name'                    => 'nullable|string|max:255',
            'produk_ketapang_option_id' => 'required|exists:produk_ketapang_options,id',
            'produksi_pertahun'       => 'nullable|string|max:255',
            'description'             => 'nullable|string',
            'price'                   => 'nullable|numeric|min:0',
            'image'                   => 'nullable|image|max:2048',
        ]);

        $data = $request->only('name', 'produk_ketapang_option_id', 'produksi_pertahun', 'description', 'price');

        if ($request->hasFile('image')) {
            if ($ketapang->image && Storage::disk('public')->exists($ketapang->image)) {
                Storage::disk('public')->delete($ketapang->image);
            }
            $data['image'] = $request->file('image')->store('ketapang', 'public');
        }

        $ketapang->update($data);

        return redirect()->route('user.ketapang.index')
            ->with('success', 'Data produk berhasil diperbarui.');
    }

    public function destroy(ProdukKetahananPangan $ketapang)
    {
        $bumdes = Bumdesa::where('user_id', auth()->id())->orWhere('id', auth()->user()->bumdes_id)->firstOrFail();
        if ($ketapang->bumdes_id !== $bumdes->id) abort(403);

        if ($ketapang->image && Storage::disk('public')->exists($ketapang->image)) {
            Storage::disk('public')->delete($ketapang->image);
        }
        $ketapang->delete();

        return redirect()->route('user.ketapang.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}
