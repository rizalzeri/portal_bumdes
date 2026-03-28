<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UnitUsahaOption;
use App\Models\ProdukOption;
use App\Models\ProdukKetapangOption;
use App\Models\MitraOption;

class DataMasterController extends Controller
{
    public function index()
    {
        $unitUsahaOptions = UnitUsahaOption::all();
        $produkOptions = ProdukOption::all();
        $ketapangOptions = ProdukKetapangOption::all();
        $mitraOptions = MitraOption::all();

        return view('superadmin.datamaster.index', compact('unitUsahaOptions', 'produkOptions', 'ketapangOptions', 'mitraOptions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:unit_usaha,produk,ketapang,mitra',
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only('name');
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('master-data', 'public');
        }

        if ($request->type === 'unit_usaha') {
            UnitUsahaOption::create($data);
        } elseif ($request->type === 'produk') {
            ProdukOption::create($data);
        } elseif ($request->type === 'mitra') {
            MitraOption::create($data);
        } else {
            ProdukKetapangOption::create($data);
        }

        return redirect()->route('superadmin.datamaster.index')->with('success', 'Data Master berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|in:unit_usaha,produk,ketapang,mitra',
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only('name');
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('master-data', 'public');
        }

        if ($request->type === 'unit_usaha') {
            UnitUsahaOption::findOrFail($id)->update($data);
        } elseif ($request->type === 'produk') {
            ProdukOption::findOrFail($id)->update($data);
        } elseif ($request->type === 'mitra') {
            MitraOption::findOrFail($id)->update($data);
        } else {
            ProdukKetapangOption::findOrFail($id)->update($data);
        }

        return redirect()->route('superadmin.datamaster.index')->with('success', 'Data Master berhasil diperbarui.');
    }

    public function destroy(Request $request, $id)
    {
        $request->validate(['type' => 'required|in:unit_usaha,produk,ketapang,mitra']);

        if ($request->type === 'unit_usaha') {
            UnitUsahaOption::findOrFail($id)->delete();
        } elseif ($request->type === 'produk') {
            ProdukOption::findOrFail($id)->delete();
        } elseif ($request->type === 'mitra') {
            MitraOption::findOrFail($id)->delete();
        } else {
            ProdukKetapangOption::findOrFail($id)->delete();
        }

        return redirect()->route('superadmin.datamaster.index')->with('success', 'Data Master berhasil dihapus.');
    }

    public function inlineUpdate(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|in:unit_usaha,produk,ketapang,mitra',
            'name' => 'required|string|max:255',
        ]);

        try {
            if ($request->type === 'unit_usaha') {
                UnitUsahaOption::findOrFail($id)->update(['name' => $request->name]);
            } elseif ($request->type === 'produk') {
                ProdukOption::findOrFail($id)->update(['name' => $request->name]);
            } elseif ($request->type === 'mitra') {
                MitraOption::findOrFail($id)->update(['name' => $request->name]);
            } else {
                ProdukKetapangOption::findOrFail($id)->update(['name' => $request->name]);
            }
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
