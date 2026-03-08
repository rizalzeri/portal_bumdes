<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengumuman;
use Illuminate\Support\Str;

class PengumumanController extends Controller
{
    public function index()
    {
        // Superadmin sees all: Global + BUMDes Announcements
        $pengumumans = Pengumuman::with(['bumdes', 'kabupaten'])->orderBy('created_at', 'desc')->get();
        return view('superadmin.pengumuman.index', compact('pengumumans'));
    }

    public function toggleFeatured(Request $request, $id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        $pengumuman->update(['is_featured' => !$pengumuman->is_featured]);
        return redirect()->back()->with('success', 'Status unggulan berhasil diperbarui.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        Pengumuman::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . uniqid(),
            'content' => $request->content,
            'type' => 'portal',
            'is_global' => true,
        ]);

        return redirect()->route('superadmin.pengumuman.index')->with('success', 'Pengumuman global berhasil ditambahkan.');
    }

    public function update(Request $request, Pengumuman $pengumuman)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $pengumuman->update([
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return redirect()->route('superadmin.pengumuman.index')->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy(Pengumuman $pengumuman)
    {
        $pengumuman->delete();
        return redirect()->route('superadmin.pengumuman.index')->with('success', 'Pengumuman berhasil dihapus.');
    }
}
