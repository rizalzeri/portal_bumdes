<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StandarTransparansi;
use App\Models\Bumdesa;
use Illuminate\Support\Facades\Storage;

class TransparansiController extends Controller
{
    public function index()
    {
        $bumdes = Bumdesa::where('user_id', auth()->id())->firstOrFail();
        $dokumen = StandarTransparansi::where('bumdes_id', $bumdes->id)->orderBy('tahun', 'desc')->get();

        return view('user.transparansi.index', compact('dokumen', 'bumdes'));
    }

    public function store(Request $request)
    {
        $bumdes = Bumdesa::where('user_id', auth()->id())->firstOrFail();

        $request->validate([
            'tipe_dokumen' => 'required|string|max:100',
            'tahun' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'file_dokumen' => 'required|file|mimes:pdf|max:10240', // enforce PDF
        ]);

        $exists = StandarTransparansi::where('bumdes_id', $bumdes->id)
            ->where('type', $request->tipe_dokumen)
            ->where('year', $request->tahun)
            ->exists();

        if ($exists) {
            return back()->withInput()->with('error', 'Dokumen tipe ini untuk tahun tersebut sudah ada.');
        }

        $filePath = null;
        if ($request->hasFile('file_dokumen')) {
            $filePath = $request->file('file_dokumen')->store('transparansi', 'public');
        }

        StandarTransparansi::create([
            'bumdes_id'   => $bumdes->id,
            'title'       => $request->tipe_dokumen,      // kolom NOT NULL awal, pakai tipe sbg judul
            'type'        => $request->tipe_dokumen,       // kolom NOT NULL awal
            'year'        => (string) $request->tahun,     // kolom NOT NULL awal
            'tahun'       => $request->tahun,              // kolom nullable tambahan
            'tipe'        => $request->tipe_dokumen,       // kolom nullable tambahan
            'file_url'    => $filePath,                    // kolom NOT NULL awal
        ]);

        return redirect()->route('user.transparansi.index')->with('success', 'Dokumen transparansi berhasil diunggah.');
    }

    public function destroy(StandarTransparansi $transparansi)
    {
        $bumdes = Bumdesa::where('user_id', auth()->id())->firstOrFail();

        if ($transparansi->bumdes_id !== $bumdes->id) {
            abort(403);
        }

        if ($transparansi->file_dokumen && Storage::disk('public')->exists($transparansi->file_dokumen)) {
            Storage::disk('public')->delete($transparansi->file_dokumen);
        }
        $transparansi->delete();

        return redirect()->route('user.transparansi.index')->with('success', 'Dokumen dihapus.');
    }
}
