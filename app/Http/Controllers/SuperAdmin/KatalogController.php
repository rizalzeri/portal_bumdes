<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KatalogProduk;
use Illuminate\Support\Facades\Storage;

class KatalogController extends Controller
{
    public function index()
    {
        // Global catalog maintained by Superadmin
        $katalogs = KatalogProduk::whereNull('bumdes_id')->orderBy('created_at', 'desc')->get();
        return view('superadmin.katalog.index', compact('katalogs'));
    }

    public function store(Request $request)
    {
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
            $imagePath = $request->file('image')->store('katalog', 'public');
        }

        KatalogProduk::create([
            'name' => $request->name,
            'category' => $request->category,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $imagePath,
            'link_pembelian' => $request->link_pembelian,
        ]);

        return redirect()->route('superadmin.katalog.index')->with('success', 'Produk berhasil ditambahkan ke katalog.');
    }

    public function update(Request $request, KatalogProduk $katalog)
    {
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
            if ($katalog->image && Storage::disk('public')->exists($katalog->image)) {
                Storage::disk('public')->delete($katalog->image);
            }
            $data['image'] = $request->file('image')->store('katalog', 'public');
        }

        $katalog->update($data);

        return redirect()->route('superadmin.katalog.index')->with('success', 'Data katalog berhasil diperbarui.');
    }

    public function destroy(KatalogProduk $katalog)
    {
        if ($katalog->image && Storage::disk('public')->exists($katalog->image)) {
            Storage::disk('public')->delete($katalog->image);
        }
        $katalog->delete();

        return redirect()->route('superadmin.katalog.index')->with('success', 'Produk berhasil dihapus dari katalog.');
    }
}
