<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UnitUsaha;
use App\Models\Bumdesa;

class UnitUsahaController extends Controller
{
    public function index($slug)
    {
        $bumdes = Bumdesa::where('user_id', auth()->id())->orWhere('id', auth()->user()->bumdes_id)->firstOrFail();
        $units = UnitUsaha::where('bumdes_id', $bumdes->id)->orderBy('created_at', 'desc')->get();
        // Option categories can be fetched from UnitUsahaOption if using reference tables, 
        // but simple text input is also fine. Admin created 'unit_usaha' options in datamaster.
        $kategoriOptions = \App\Models\UnitUsahaOption::all();

        return view('user.unit_usaha.index', compact('units', 'bumdes', 'kategoriOptions'));
    }

    public function store(Request $request, $slug)
    {
        $bumdes = Bumdesa::where('user_id', auth()->id())->orWhere('id', auth()->user()->bumdes_id)->firstOrFail();

        $request->validate([
            'name' => 'nullable|string|max:255',
            'sektor' => 'required|string|max:100', // From options
            'deskripsi' => 'nullable|string',
            'tahun_berdiri' => 'nullable|integer',
            'status' => 'required|in:active,inactive',
        ]);

        $option = \App\Models\UnitUsahaOption::firstOrCreate(['name' => $request->sektor]);

        UnitUsaha::create([
            'bumdes_id' => $bumdes->id,
            'unit_usaha_option_id' => $option->id,
            'name' => $request->name,
            'sektor' => $request->sektor,
            'deskripsi' => $request->deskripsi,
            'tahun_berdiri' => $request->tahun_berdiri,
            'status' => $request->status,
        ]);

        return redirect()->route('user.unit_usaha.index')->with('success', 'Unit usaha berhasil ditambahkan.');
    }

    public function update(Request $request, $slug, UnitUsaha $unitUsaha)
    {
        $bumdes = Bumdesa::where('user_id', auth()->id())->orWhere('id', auth()->user()->bumdes_id)->firstOrFail();

        if ($unitUsaha->bumdes_id !== $bumdes->id) {
            abort(403);
        }

        $request->validate([
            'name' => 'nullable|string|max:255',
            'sektor' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'tahun_berdiri' => 'nullable|integer',
            'status' => 'required|in:active,inactive',
        ]);

        $option = \App\Models\UnitUsahaOption::firstOrCreate(['name' => $request->sektor]);

        $unitUsaha->update([
            'unit_usaha_option_id' => $option->id,
            'name' => $request->name,
            'sektor' => $request->sektor,
            'deskripsi' => $request->deskripsi,
            'tahun_berdiri' => $request->tahun_berdiri,
            'status' => $request->status,
        ]);

        return redirect()->route('user.unit_usaha.index')->with('success', 'Data unit usaha berhasil diperbarui.');
    }

    public function destroy($slug, UnitUsaha $unitUsaha)
    {
        $bumdes = Bumdesa::where('user_id', auth()->id())->orWhere('id', auth()->user()->bumdes_id)->firstOrFail();

        if ($unitUsaha->bumdes_id !== $bumdes->id) {
            abort(403);
        }

        $unitUsaha->delete();

        return redirect()->route('user.unit_usaha.index')->with('success', 'Unit usaha dihapus.');
    }
}
