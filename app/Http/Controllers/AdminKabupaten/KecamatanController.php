<?php

namespace App\Http\Controllers\AdminKabupaten;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kecamatan;

class KecamatanController extends Controller
{
    public function index()
    {
        $kabupatenId = auth()->user()->kabupaten_id;
        $kecamatans = Kecamatan::where('kabupaten_id', $kabupatenId)->orderBy('name')->get();
        return view('adminkab.kecamatan.index', compact('kecamatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Kecamatan::create([
            'kabupaten_id' => auth()->user()->kabupaten_id,
            'name' => $request->name,
        ]);

        return redirect()->route('adminkab.kecamatan.index')->with('success', 'Kecamatan berhasil didaftarkan.');
    }

    public function destroy($id)
    {
        $kecamatan = Kecamatan::findOrFail($id);
        if ($kecamatan->kabupaten_id !== auth()->user()->kabupaten_id) {
            abort(403);
        }

        try {
            $kecamatan->delete();
            return redirect()->route('adminkab.kecamatan.index')->with('success', 'Kecamatan berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus kecamatan. Data ini mungkin masih digunakan.');
        }
    }
}
