<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Artikel;
use App\Models\Bumdesa;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArtikelController extends Controller
{
    public function index($slug)
    {
        $bumdes = Bumdesa::where('user_id', auth()->id())->orWhere('id', auth()->user()->bumdes_id)->firstOrFail();
        $artikels = Artikel::where('bumdes_id', $bumdes->id)->orderBy('created_at', 'desc')->get();

        return view('user.artikel.index', compact('artikels', 'bumdes'));
    }

    public function store(Request $request, $slug)
    {
        $bumdes = Bumdesa::where('user_id', auth()->id())->orWhere('id', auth()->user()->bumdes_id)->firstOrFail();

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'nullable|string|max:100',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('artikels', 'public');
        }

        Artikel::create([
            'bumdes_id' => $bumdes->id,
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . uniqid(),
            'content' => $request->content,
            'category' => in_array($request->category, ['artikel', 'opini']) ? $request->category : 'artikel',
            'image' => $imagePath,
            'is_global' => false,
            'views' => 0,
        ]);

        return redirect()->route('user.artikel.index')->with('success', 'Artikel berhasil diterbitkan.');
    }

    public function destroy($slug, Artikel $artikel)
    {
        $bumdes = Bumdesa::where('user_id', auth()->id())->orWhere('id', auth()->user()->bumdes_id)->firstOrFail();

        if ($artikel->bumdes_id !== $bumdes->id) {
            abort(403);
        }

        if ($artikel->image && Storage::disk('public')->exists($artikel->image)) {
            Storage::disk('public')->delete($artikel->image);
        }
        $artikel->delete();

        return redirect()->route('user.artikel.index')->with('success', 'Artikel dihapus.');
    }
}
