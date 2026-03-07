<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Galeri;
use App\Models\Bumdesa;
use Illuminate\Support\Facades\Storage;

class GaleriController extends Controller
{
    public function index()
    {
        $bumdes = Bumdesa::where('user_id', auth()->id())->firstOrFail();
        $galeris = Galeri::where('bumdes_id', $bumdes->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.galeri.index', compact('galeris', 'bumdes'));
    }

    public function store(Request $request)
    {
        $bumdes = Bumdesa::where('user_id', auth()->id())->firstOrFail();

        $request->validate([
            'title'       => 'required|string|max:255',
            'image'       => 'required|image|max:4096',
            'description' => 'nullable|string',
            'event_date'  => 'nullable|date',
        ]);

        $imagePath = $request->file('image')->store('galeri', 'public');

        Galeri::create([
            'bumdes_id'   => $bumdes->id,
            'title'       => $request->title,
            'image'       => $imagePath,
            'description' => $request->description,
            'event_date'  => $request->event_date,
            'type'        => 'bumdes',
        ]);

        return redirect()->route('user.galeri.index')
            ->with('success', 'Foto galeri berhasil diunggah.');
    }

    public function destroy(Galeri $galeri)
    {
        $bumdes = Bumdesa::where('user_id', auth()->id())->firstOrFail();
        if ($galeri->bumdes_id !== $bumdes->id) abort(403);

        if ($galeri->image && Storage::disk('public')->exists($galeri->image)) {
            Storage::disk('public')->delete($galeri->image);
        }
        $galeri->delete();

        return redirect()->route('user.galeri.index')
            ->with('success', 'Foto galeri berhasil dihapus.');
    }
}
