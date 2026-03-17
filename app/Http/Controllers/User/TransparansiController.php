<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Bumdesa;
use App\Models\StandarTransparansi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TransparansiController extends Controller
{
    public function index($slug)
    {
        $bumdes = Bumdesa::where('user_id', auth()->id())->orWhere('id', auth()->user()->bumdes_id)->firstOrFail();
        $dokumen = StandarTransparansi::where('bumdes_id', $bumdes->id)->orderBy('tahun', 'desc')->get();

        return view('user.transparansi.index', compact('dokumen', 'bumdes'));
    }

    public function store(Request $request, $slug)
    {
        $bumdes = Bumdesa::where('user_id', auth()->id())->orWhere('id', auth()->user()->bumdes_id)->firstOrFail();

        $request->validate([
            'musdes_terakhir' => 'nullable|date',
            'audit_internal_terakhir' => 'nullable|date',
            'laporan_dinas_status' => 'nullable|in:sudah,belum',
            'laporan_dinas_link' => 'nullable|url',
        ]);

        $bumdes->update($request->only([
            'musdes_terakhir', 'audit_internal_terakhir', 'laporan_dinas_status', 'laporan_dinas_link'
        ]));

        return redirect()->route('user.transparansi.index')->with('success', 'Data Transparansi berhasil diperbarui.');
    }

    public function destroy($slug, StandarTransparansi $transparansi)
    {
        $bumdes = Bumdesa::where('user_id', auth()->id())->orWhere('id', auth()->user()->bumdes_id)->firstOrFail();

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
