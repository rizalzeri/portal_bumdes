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
            'type' => 'required|in:photo,video',
            'image_url' => 'required_if:type,photo|nullable|image|max:5120',
            'video_url' => 'required_if:type,video|nullable|url',
        ]);

        $imagePath = null;
        if ($request->hasFile('image_url')) {
            $imagePath = $request->file('image_url')->store('gallery', 'public');
        }

        Gallery::create([
            'title' => $request->title,
            'type' => $request->type,
            'image_url' => $imagePath,
            'video_url' => $request->video_url,
        ]);

        return redirect()->route('superadmin.galeri.index')->with('success', 'Media galeri berhasil ditambahkan.');
    }

    public function destroy(Gallery $galeri)
    {
        if ($galeri->image_url && Storage::disk('public')->exists($galeri->image_url)) {
            Storage::disk('public')->delete($galeri->image_url);
        }
        $galeri->delete();

        return redirect()->route('superadmin.galeri.index')->with('success', 'Media berhasil dihapus.');
    }
}
