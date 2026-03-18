<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Galeri;
use App\Models\Bumdesa;
use App\Models\PremiumFeature;
use Illuminate\Support\Facades\Storage;

class GaleriController extends Controller
{
    public function index($slug)
    {
        $bumdes = Bumdesa::where('user_id', auth()->id())->orWhere('id', auth()->user()->bumdes_id)->firstOrFail();
        $galeris = Galeri::where('bumdes_id', '=', $bumdes->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.galeri.index', compact('galeris', 'bumdes'));
    }

    public function store(Request $request, $slug)
    {
        $bumdes = Bumdesa::where('user_id', auth()->id())->orWhere('id', auth()->user()->bumdes_id)->firstOrFail();

        $request->validate([
            'title'       => 'required|string|max:255',
            'image'       => 'required|image|max:4096',
            'description' => 'nullable|string',
            'event_date'  => 'nullable|date',
        ]);

        $module = 'galeri';
        $action = 'create';
        $allowed = PremiumFeature::isAllowed($bumdes, $module, $action);

        if ($allowed !== true) {
            $message = 'Akses Ditolak. Fitur ini hanya tersedia untuk pengguna Premium BUMDesa.';
            
            if ($allowed === 'limit') {
                $feature = PremiumFeature::where('module', '=', $module)->where('action', '=', $action)->first();
                $limit = $feature->free_limit ?? 0;
                $message = "Maaf, Anda telah mencapai batas maksimal ({$limit}) untuk fitur gratis ini. Silakan upgrade ke Premium untuk menambah lebih banyak data.";
            }

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => $message], 403);
            }
            return abort(403, $message . ' Silakan hubungi admin atau upgrade paket Anda.');
        }

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

    public function destroy($slug, Galeri $galeri)
    {
        $bumdes = Bumdesa::where('user_id', auth()->id())->orWhere('id', auth()->user()->bumdes_id)->firstOrFail();
        if ($galeri->bumdes_id !== $bumdes->id) abort(403);

        if ($galeri->image && Storage::disk('public')->exists($galeri->image)) {
            Storage::disk('public')->delete($galeri->image);
        }
        $galeri->delete();

        return redirect()->route('user.galeri.index')
            ->with('success', 'Foto galeri berhasil dihapus.');
    }
}
