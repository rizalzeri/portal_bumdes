<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KatalogProduk;
use App\Models\Bumdesa;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    public function index()
    {
        $bumdes = Bumdesa::where('user_id', auth()->id())->firstOrFail();
        $produks = KatalogProduk::where('bumdes_id', $bumdes->id)->orderBy('created_at', 'desc')->get();
        // Option categories
        $kategoriOptions = \App\Models\ProdukOption::all();

        return view('user.produk.index', compact('produks', 'bumdes', 'kategoriOptions'));
    }

    public function store(Request $request)
    {
        $bumdes = Bumdesa::where('user_id', auth()->id())->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric',
            'image' => 'nullable|image|max:2048',
            'link_pembelian' => 'nullable|url',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('produk', 'public');
        }

        KatalogProduk::create([
            'bumdes_id' => $bumdes->id,
            'name' => $request->name,
            'category' => $request->category,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $imagePath,
            'link_pembelian' => $request->link_pembelian,
        ]);

        return redirect()->route('user.produk.index')->with('success', 'Produk/Layanan berhasil ditambahkan ke katalog BUMDesa.');
    }

    public function update(Request $request, KatalogProduk $produk)
    {
        $bumdes = Bumdesa::where('user_id', auth()->id())->firstOrFail();

        if ($produk->bumdes_id !== $bumdes->id) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric',
            'image' => 'nullable|image|max:2048',
            'link_pembelian' => 'nullable|url',
        ]);

        $data = $request->only('name', 'category', 'description', 'price', 'link_pembelian');

        if ($request->hasFile('image')) {
            if ($produk->image && Storage::disk('public')->exists($produk->image)) {
                Storage::disk('public')->delete($produk->image);
            }
            $data['image'] = $request->file('image')->store('produk', 'public');
        }

        $produk->update($data);

        return redirect()->route('user.produk.index')->with('success', 'Data produk berhasil diperbarui.');
    }

    public function destroy(KatalogProduk $produk)
    {
        $bumdes = Bumdesa::where('user_id', auth()->id())->firstOrFail();

        if ($produk->bumdes_id !== $bumdes->id) {
            abort(403);
        }

        if ($produk->image && Storage::disk('public')->exists($produk->image)) {
            Storage::disk('public')->delete($produk->image);
        }
        $produk->delete();

        return redirect()->route('user.produk.index')->with('success', 'Produk berhasil dihapus.');
    }
}
