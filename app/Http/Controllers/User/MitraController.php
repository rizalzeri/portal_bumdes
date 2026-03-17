<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bumdesa;
use App\Models\MitraKerjasama;
use App\Models\MitraOption;
use Illuminate\Support\Facades\Storage;

class MitraController extends Controller
{
    public function index($slug)
    {
        $bumdes = Bumdesa::where('user_id', auth()->id())
            ->orWhere('id', auth()->user()->bumdes_id)
            ->firstOrFail();

        $mitras = MitraKerjasama::where('bumdes_id', $bumdes->id)
            ->with('mitraOption')
            ->orderBy('name')
            ->get();

        $options = MitraOption::orderBy('name')->get();

        return view('user.mitra.index', compact('bumdes', 'mitras', 'options'));
    }

    public function store(Request $request, $slug)
    {
        $bumdes = Bumdesa::where('user_id', auth()->id())
            ->orWhere('id', auth()->user()->bumdes_id)
            ->firstOrFail();

        $request->validate([
            'mitra_option_id' => 'required|exists:mitra_options,id',
            'is_active'       => 'required|in:0,1',
            'description'     => 'nullable|string',
            'logo'            => 'nullable|image|max:2048',
        ]);

        $option = MitraOption::findOrFail($request->mitra_option_id);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('mitra', 'public');
        }

        MitraKerjasama::create([
            'bumdes_id'       => $bumdes->id,
            'mitra_option_id' => $request->mitra_option_id,
            'name'            => $option->name,
            'description'     => $request->description,
            'logo'            => $logoPath,
            'type'            => 'bumdes',
            'is_active'       => (int) $request->is_active,
        ]);

        return redirect()->route('user.mitra.index', ['slug' => $slug])
            ->with('success', 'Mitra kerjasama berhasil ditambahkan.');
    }

    public function update(Request $request, $slug, MitraKerjasama $mitra)
    {
        $bumdes = Bumdesa::where('user_id', auth()->id())
            ->orWhere('id', auth()->user()->bumdes_id)
            ->firstOrFail();

        if ($mitra->bumdes_id !== $bumdes->id) abort(403);

        $request->validate([
            'mitra_option_id' => 'required|exists:mitra_options,id',
            'is_active'       => 'required|in:0,1',
            'description'     => 'nullable|string',
            'logo'            => 'nullable|image|max:2048',
        ]);

        $option = MitraOption::findOrFail($request->mitra_option_id);

        $data = [
            'mitra_option_id' => $request->mitra_option_id,
            'name'            => $option->name,
            'description'     => $request->description,
            'is_active'       => (int) $request->is_active,
        ];

        if ($request->hasFile('logo')) {
            if ($mitra->logo && Storage::disk('public')->exists($mitra->logo)) {
                Storage::disk('public')->delete($mitra->logo);
            }
            $data['logo'] = $request->file('logo')->store('mitra', 'public');
        }

        $mitra->update($data);

        return redirect()->route('user.mitra.index', ['slug' => $slug])
            ->with('success', 'Data mitra berhasil diperbarui.');
    }

    public function destroy($slug, MitraKerjasama $mitra)
    {
        $bumdes = Bumdesa::where('user_id', auth()->id())
            ->orWhere('id', auth()->user()->bumdes_id)
            ->firstOrFail();

        if ($mitra->bumdes_id !== $bumdes->id) abort(403);

        if ($mitra->logo && Storage::disk('public')->exists($mitra->logo)) {
            Storage::disk('public')->delete($mitra->logo);
        }
        $mitra->delete();

        return redirect()->route('user.mitra.index', ['slug' => $slug])
            ->with('success', 'Mitra berhasil dihapus.');
    }
}
