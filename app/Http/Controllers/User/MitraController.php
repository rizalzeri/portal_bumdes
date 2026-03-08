<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bumdesa;
use App\Models\MitraKerjasama;
use Illuminate\Support\Facades\Storage;

class MitraController extends Controller
{
    public function index($slug)
    {
        $bumdes = Bumdesa::where('user_id', auth()->id())->orWhere('id', auth()->user()->bumdes_id)->firstOrFail();
        $mitras = MitraKerjasama::where('bumdes_id', $bumdes->id)
            ->orderBy('name')
            ->get();

        return view('user.mitra.index', compact('bumdes', 'mitras'));
    }

    public function store(Request $request, $slug)
    {
        $bumdes = Bumdesa::where('user_id', auth()->id())->orWhere('id', auth()->user()->bumdes_id)->firstOrFail();

        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo'        => 'nullable|image|max:2048',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('mitra', 'public');
        }

        MitraKerjasama::create([
            'bumdes_id'   => $bumdes->id,
            'name'        => $request->name,
            'description' => $request->description,
            'logo'        => $logoPath,
            'type'        => 'bumdes',
        ]);

        return redirect()->route('user.mitra.index')
            ->with('success', 'Mitra kerjasama berhasil ditambahkan.');
    }

    public function update(Request $request, $slug, MitraKerjasama $mitra)
    {
        $bumdes = Bumdesa::where('user_id', auth()->id())->orWhere('id', auth()->user()->bumdes_id)->firstOrFail();
        if ($mitra->bumdes_id !== $bumdes->id) abort(403);

        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo'        => 'nullable|image|max:2048',
        ]);

        $data = $request->only('name', 'description');

        if ($request->hasFile('logo')) {
            if ($mitra->logo && Storage::disk('public')->exists($mitra->logo)) {
                Storage::disk('public')->delete($mitra->logo);
            }
            $data['logo'] = $request->file('logo')->store('mitra', 'public');
        }

        $mitra->update($data);

        return redirect()->route('user.mitra.index')
            ->with('success', 'Data mitra berhasil diperbarui.');
    }

    public function destroy($slug, MitraKerjasama $mitra)
    {
        $bumdes = Bumdesa::where('user_id', auth()->id())->orWhere('id', auth()->user()->bumdes_id)->firstOrFail();
        if ($mitra->bumdes_id !== $bumdes->id) abort(403);

        if ($mitra->logo && Storage::disk('public')->exists($mitra->logo)) {
            Storage::disk('public')->delete($mitra->logo);
        }
        $mitra->delete();

        return redirect()->route('user.mitra.index')
            ->with('success', 'Mitra berhasil dihapus.');
    }
}
