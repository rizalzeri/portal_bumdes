<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gallery;
use Illuminate\Support\Facades\Storage;

class GaleriController extends Controller
{
    public function index()
    {
        // Combined view: Global + BUMDes Gallery
        $galeris = \App\Models\Galeri::with('bumdes')->orderBy('created_at', 'desc')->get();
        return view('superadmin.galeri.index', compact('galeris'));
    }

    public function toggleFeatured(Request $request, $id)
    {
        $galeri = \App\Models\Galeri::findOrFail($id);
        $galeri->update(['is_featured' => !$galeri->is_featured]);
        return redirect()->back()->with('success', 'Status unggulan berhasil diperbarui.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|max:5120',
            'description' => 'nullable|string',
            'event_date' => 'nullable|date',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('galeri', 'public');
        }

        \App\Models\Galeri::create([
            'title' => $request->title,
            'type' => 'portal',
            'image' => $imagePath,
            'description' => $request->description,
            'event_date' => $request->event_date,
        ]);

        return redirect()->route('superadmin.galeri.index')->with('success', 'Media galeri berhasil ditambahkan.');
    }

    public function destroy(\App\Models\Galeri $galeri)
    {
        if ($galeri->image && Storage::disk('public')->exists($galeri->image)) {
            Storage::disk('public')->delete($galeri->image);
        }
        $galeri->delete();

        return redirect()->route('superadmin.galeri.index')->with('success', 'Media berhasil dihapus.');
    }
}
