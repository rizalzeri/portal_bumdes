<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengumuman;
use App\Models\Bumdesa;
use Illuminate\Support\Str;

class PengumumanController extends Controller
{
    public function index($slug)
    {
        $bumdes = Bumdesa::where('user_id', auth()->id())->orWhere('id', auth()->user()->bumdes_id)->firstOrFail();
        $pengumumans = Pengumuman::where('bumdes_id', $bumdes->id)->orderBy('created_at', 'desc')->get();

        return view('user.pengumuman.index', compact('pengumumans', 'bumdes'));
    }

    public function store(Request $request, $slug)
    {
        $bumdes = Bumdesa::where('user_id', auth()->id())->orWhere('id', auth()->user()->bumdes_id)->firstOrFail();

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

         Pengumuman::create([
            'bumdes_id' => $bumdes->id,
            'kabupaten_id' => $bumdes->kabupaten_id,
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . uniqid(),
            'content' => $request->content,
            'type' => 'bumdes',
            'is_global' => false,
        ]);

        return redirect()->route('user.pengumuman.index', $slug)->with('success', 'Pengumuman berhasil diterbitkan.');
    }

    public function destroy($slug, Pengumuman $pengumuman)
    {
        $bumdes = Bumdesa::where('user_id', auth()->id())->orWhere('id', auth()->user()->bumdes_id)->firstOrFail();

        if ($pengumuman->bumdes_id !== $bumdes->id) {
            abort(403);
        }

        $pengumuman->delete();

        return redirect()->route('user.pengumuman.index', $slug)->with('success', 'Pengumuman dihapus.');
    }
}
