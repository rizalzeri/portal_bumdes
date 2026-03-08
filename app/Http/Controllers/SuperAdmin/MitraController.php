<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MitraKerjasama;
use Illuminate\Support\Facades\Storage;

class MitraController extends Controller
{
    public function index()
    {
        // Collective view
        $mitras = MitraKerjasama::with('bumdes')->orderBy('name')->get();
        return view('superadmin.mitra.index', compact('mitras'));
    }

    public function toggleFeatured(Request $request, $id)
    {
        $mitra = MitraKerjasama::findOrFail($id);
        $mitra->update(['is_featured' => !$mitra->is_featured]);
        return redirect()->back()->with('success', 'Status unggulan berhasil diperbarui.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'website' => 'nullable|url',
            'logo' => 'nullable|image|max:2048',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('mitra', 'public');
        }

        MitraKerjasama::create([
            'name' => $request->name,
            'description' => $request->description,
            'website' => $request->website,
            'logo' => $logoPath,
        ]);

        return redirect()->route('superadmin.mitra.index')->with('success', 'Mitra Kerjasama  berhasil ditambahkan.');
    }

    public function update(Request $request, MitraKerjasama $mitra)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'website' => 'nullable|url',
            'logo' => 'nullable|image|max:2048',
        ]);

        $data = $request->only('name', 'description', 'website');

        if ($request->hasFile('logo')) {
            if ($mitra->logo && Storage::disk('public')->exists($mitra->logo)) {
                Storage::disk('public')->delete($mitra->logo);
            }
            $data['logo'] = $request->file('logo')->store('mitra', 'public');
        }

        $mitra->update($data);

        return redirect()->route('superadmin.mitra.index')->with('success', 'Data Mitra Kerjasama berhasil diperbarui.');
    }

    public function destroy(MitraKerjasama $mitra)
    {
        if ($mitra->logo && Storage::disk('public')->exists($mitra->logo)) {
            Storage::disk('public')->delete($mitra->logo);
        }
        $mitra->delete();

        return redirect()->route('superadmin.mitra.index')->with('success', 'Mitra Kerjasama berhasil dihapus.');
    }
}
