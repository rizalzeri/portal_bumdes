<?php

namespace App\Http\Controllers\AdminKabupaten;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengumuman;
use Illuminate\Support\Str;

class PengumumanController extends Controller
{
    public function index()
    {
        $kabupatenId = auth()->user()->kabupaten_id;
        // See announcements specific to their kabupaten
        $pengumumans = Pengumuman::where('kabupaten_id', $kabupatenId)->orderBy('created_at', 'desc')->get();
        return view('adminkab.pengumuman.index', compact('pengumumans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        Pengumuman::create([
            'kabupaten_id' => auth()->user()->kabupaten_id,
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . uniqid(),
            'content' => $request->content,
            'is_global' => false,
        ]);

        return redirect()->route('adminkab.pengumuman.index')->with('success', 'Pengumuman tingkat kabupaten berhasil diterbitkan.');
    }

    public function update(Request $request, Pengumuman $pengumuman)
    {
        if ($pengumuman->kabupaten_id !== auth()->user()->kabupaten_id) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $pengumuman->update([
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return redirect()->route('adminkab.pengumuman.index')->with('success', 'Pengumuman diperbarui.');
    }

    public function destroy(Pengumuman $pengumuman)
    {
        if ($pengumuman->kabupaten_id !== auth()->user()->kabupaten_id) {
            abort(403);
        }

        $pengumuman->delete();
        return redirect()->route('adminkab.pengumuman.index')->with('success', 'Pengumuman dihapus.');
    }
}
