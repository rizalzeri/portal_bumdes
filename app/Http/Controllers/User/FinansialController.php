<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Bumdesa;
use App\Models\LaporanKeuangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FinansialController extends Controller
{
    public function index()
    {
        $bumdes = Bumdesa::where('user_id', auth()->id())->firstOrFail();
        // Get all reports sorted by year and month
        $laporans = LaporanKeuangan::where('bumdes_id', $bumdes->id)
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->get();

        return view('user.finansial.index', compact('laporans', 'bumdes'));
    }

    public function store(Request $request)
    {
        $bumdes = Bumdesa::where('user_id', auth()->id())->firstOrFail();

        $request->validate([
            'bulan' => 'required|integer|between:1,12',
            'tahun' => 'required|integer|min:2000|max:'.(date('Y') + 1),
            'pendapatan' => 'required|numeric',
            'pengeluaran' => 'required|numeric',
            'aset' => 'required|numeric',
            'file_laporan' => 'nullable|file|mimes:pdf,xls,xlsx,doc,docx|max:10240', // max 10MB
        ]);

        // Check if report already exists for this month & year
        $exists = LaporanKeuangan::where('bumdes_id', $bumdes->id)
            ->where('bulan', $request->bulan)
            ->where('tahun', $request->tahun)
            ->exists();

        if ($exists) {
            return back()->withInput()->with('error', 'Laporan untuk bulan dan tahun tersebut sudah ada. Silakan edit laporan yang sudah ada.');
        }

        $filePath = null;
        if ($request->hasFile('file_laporan')) {
            $filePath = $request->file('file_laporan')->store('laporan_keuangan', 'public');
        }

        LaporanKeuangan::create([
            'bumdes_id' => $bumdes->id,
            'year' => (string) $request->tahun,  // kolom NOT NULL dari migrasi awal
            'tahun' => $request->tahun,           // kolom nullable dari migrasi tambahan
            'bulan' => $request->bulan,
            'pendapatan' => $request->pendapatan,
            'pengeluaran' => $request->pengeluaran,
            'laba_rugi' => $request->pendapatan - $request->pengeluaran,
            'total_aset' => $request->aset,
            'file_url' => $filePath,
        ]);

        return redirect()->route('user.finansial.index')->with('success', 'Laporan keuangan berhasil disimpan.');
    }

    public function update(Request $request, LaporanKeuangan $finansial)
    {
        $bumdes = Bumdesa::where('user_id', auth()->id())->firstOrFail();

        if ($finansial->bumdes_id !== $bumdes->id) {
            abort(403);
        }

        $request->validate([
            'pendapatan' => 'required|numeric',
            'pengeluaran' => 'required|numeric',
            'aset' => 'required|numeric',
            'file_laporan' => 'nullable|file|mimes:pdf,xls,xlsx,doc,docx|max:10240',
        ]);

        $data = [
            'pendapatan' => $request->pendapatan,
            'pengeluaran' => $request->pengeluaran,
            'laba_bersih' => $request->pendapatan - $request->pengeluaran,
            'aset' => $request->aset,
        ];

        if ($request->hasFile('file_laporan')) {
            if ($finansial->file_laporan && Storage::disk('public')->exists($finansial->file_laporan)) {
                Storage::disk('public')->delete($finansial->file_laporan);
            }
            $data['file_laporan'] = $request->file('file_laporan')->store('laporan_keuangan', 'public');
        }

        $finansial->update($data);

        return redirect()->route('user.finansial.index')->with('success', 'Laporan keuangan berhasil diperbarui.');
    }

    public function destroy(LaporanKeuangan $finansial)
    {
        $bumdes = Bumdesa::where('user_id', auth()->id())->firstOrFail();

        if ($finansial->bumdes_id !== $bumdes->id) {
            abort(403);
        }

        if ($finansial->file_laporan && Storage::disk('public')->exists($finansial->file_laporan)) {
            Storage::disk('public')->delete($finansial->file_laporan);
        }
        $finansial->delete();

        return redirect()->route('user.finansial.index')->with('success', 'Laporan keuangan berhasil dihapus.');
    }
}
