<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kabupaten;
use App\Models\Province;

class KabupatenController extends Controller
{
    public function index()
    {
        $kabupatens = Kabupaten::with('province')->get();
        $provinces = Province::orderBy('name')->get();
        return view('superadmin.kabupaten.index', compact('kabupatens', 'provinces'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'province_id' => 'required|exists:provinces,id',
            'name' => 'required|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        Kabupaten::create($request->all());

        return redirect()->route('superadmin.kabupaten.index')->with('success', 'Data kabupaten berhasil ditambahkan.');
    }

    public function update(Request $request, Kabupaten $kabupaten)
    {
        $request->validate([
            'province_id' => 'required|exists:provinces,id',
            'name' => 'required|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $kabupaten->update($request->all());

        return redirect()->route('superadmin.kabupaten.index')->with('success', 'Data kabupaten berhasil diperbarui.');
    }

    public function destroy(Kabupaten $kabupaten)
    {
        $kabupaten->delete();
        return redirect()->route('superadmin.kabupaten.index')->with('success', 'Data kabupaten berhasil dihapus.');
    }
}
