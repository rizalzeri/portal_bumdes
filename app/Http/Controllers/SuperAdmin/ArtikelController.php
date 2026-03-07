<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Artikel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ArtikelController extends Controller
{
    public function index()
    {
        // Global articles (no bumdes)
        $artikels = Artikel::whereNull('bumdes_id')->orderBy('created_at', 'desc')->get();
        return view('superadmin.artikel.index', compact('artikels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('artikels', 'public');
        }

        Artikel::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . uniqid(),
            'category' => $request->category,
            'content' => $request->content,
            'author' => 'Super Admin',
            'image' => $imagePath,
            'is_published' => true,
        ]);

        return redirect()->route('superadmin.artikel.index')->with('success', 'Artikel global berhasil diterbitkan.');
    }

    public function update(Request $request, Artikel $artikel)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = [
            'title' => $request->title,
            'category' => $request->category,
            'content' => $request->content,
        ];

        if ($request->hasFile('image')) {
            if ($artikel->image && Storage::disk('public')->exists($artikel->image)) {
                Storage::disk('public')->delete($artikel->image);
            }
            $data['image'] = $request->file('image')->store('artikels', 'public');
        }

        $artikel->update($data);

        return redirect()->route('superadmin.artikel.index')->with('success', 'Artikel berhasil diperbarui.');
    }

    public function destroy(Artikel $artikel)
    {
        if ($artikel->image && Storage::disk('public')->exists($artikel->image)) {
            Storage::disk('public')->delete($artikel->image);
        }
        $artikel->delete();

        return redirect()->route('superadmin.artikel.index')->with('success', 'Artikel berhasil dihapus.');
    }
}
