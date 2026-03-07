<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UnitUsahaOption;
use App\Models\ProdukOption;
use App\Models\ProdukKetapangOption;

class DataMasterController extends Controller
{
    public function index()
    {
        $unitUsahaOptions = UnitUsahaOption::all();
        $produkOptions = ProdukOption::all();
        $ketapangOptions = ProdukKetapangOption::all();

        return view('superadmin.datamaster.index', compact('unitUsahaOptions', 'produkOptions', 'ketapangOptions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:unit_usaha,produk,ketapang',
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:100', // For fontawesome icons
        ]);

        if ($request->type === 'unit_usaha') {
            UnitUsahaOption::create($request->only('name', 'icon'));
        } elseif ($request->type === 'produk') {
            ProdukOption::create($request->only('name', 'icon'));
        } else {
            ProdukKetapangOption::create($request->only('name', 'icon'));
        }

        return redirect()->route('superadmin.datamaster.index')->with('success', 'Data Master berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|in:unit_usaha,produk,ketapang',
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:100',
        ]);

        if ($request->type === 'unit_usaha') {
            UnitUsahaOption::findOrFail($id)->update($request->only('name', 'icon'));
        } elseif ($request->type === 'produk') {
            ProdukOption::findOrFail($id)->update($request->only('name', 'icon'));
        } else {
            ProdukKetapangOption::findOrFail($id)->update($request->only('name', 'icon'));
        }

        return redirect()->route('superadmin.datamaster.index')->with('success', 'Data Master berhasil diperbarui.');
    }

    public function destroy(Request $request, $id)
    {
        $request->validate(['type' => 'required|in:unit_usaha,produk,ketapang']);

        if ($request->type === 'unit_usaha') {
            UnitUsahaOption::findOrFail($id)->delete();
        } elseif ($request->type === 'produk') {
            ProdukOption::findOrFail($id)->delete();
        } else {
            ProdukKetapangOption::findOrFail($id)->delete();
        }

        return redirect()->route('superadmin.datamaster.index')->with('success', 'Data Master berhasil dihapus.');
    }

    public function inlineUpdate(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|in:unit_usaha,produk,ketapang',
            'name' => 'required|string|max:255',
        ]);

        try {
            if ($request->type === 'unit_usaha') {
                UnitUsahaOption::findOrFail($id)->update(['name' => $request->name]);
            } elseif ($request->type === 'produk') {
                ProdukOption::findOrFail($id)->update(['name' => $request->name]);
            } else {
                ProdukKetapangOption::findOrFail($id)->update(['name' => $request->name]);
            }
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
