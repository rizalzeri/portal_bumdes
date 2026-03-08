<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Kabupaten;
use App\Models\Province;

class KabupatenController extends Controller
{
    public function index()
    {
        $kabupatens = Kabupaten::with('province')->get();
        // Use unique() to prevent duplicate province names in dropdown
        $provinces = Province::orderBy('name')->get()->unique('name');
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

        $data = $request->only(['province_id', 'name', 'latitude', 'longitude']);
        $data['slug'] = Str::slug($request->name) . '-' . uniqid();

        Kabupaten::create($data);

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

        $data = $request->only(['province_id', 'name', 'latitude', 'longitude']);
        // Only regenerate slug if the name changed
        if ($kabupaten->name !== $request->name) {
            $data['slug'] = Str::slug($request->name) . '-' . uniqid();
        }

        $kabupaten->update($data);

        return redirect()->route('superadmin.kabupaten.index')->with('success', 'Data kabupaten berhasil diperbarui.');
    }

    public function destroy(Kabupaten $kabupaten)
    {
        $kabupaten->delete();
        return redirect()->route('superadmin.kabupaten.index')->with('success', 'Data kabupaten berhasil dihapus.');
    }
}
