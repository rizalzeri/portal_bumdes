<?php

namespace App\Http\Controllers\AdminKabupaten;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bumdesa;

class MonitoringBumdesController extends Controller
{
    public function index(Request $request)
    {
        $kabupatenId = auth()->user()->kabupaten_id;
        // Tab is now simplified or we can keep old logic? "ditambahkan manual" means we only show manual ones or we can have a tab for manual monitoring
        $tab = $request->get('tab', 'manual');
        $query = Bumdesa::where('kabupaten_id', $kabupatenId)->with(['user']);

        switch ($tab) {
            case 'manual':
                $query->where('is_monitored', true);
                break;
            case 'sudah_mengirim':
                $query->whereHas('laporanKeuangan');
                break;
            case 'belum_mengirim':
                $query->whereDoesntHave('laporanKeuangan');
                break;
            case 'perhatian_khusus':
                $query->where(function ($q) {
                    $q->whereNull('nomor_sertifikat')
                      ->orWhere('status', 'inactive')
                      ->orWhereDoesntHave('laporanKeuangan');
                });
                break;
        }

        $bumdes = $query->paginate(20)->withQueryString();
        
        // List of all BUMDesa to select for adding to manual monitoring
        $allBumdes = Bumdesa::where('kabupaten_id', $kabupatenId)->where('is_monitored', false)->orderBy('name')->get();

        return view('adminkab.monitoring.index', compact('bumdes', 'tab', 'allBumdes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bumdes_id' => 'required|exists:bumdes,id',
            'catatan' => 'nullable|string|max:1000'
        ]);

        $bumdesa = Bumdesa::findOrFail($request->bumdes_id);
        if ($bumdesa->kabupaten_id !== auth()->user()->kabupaten_id) {
            abort(403);
        }

        $bumdesa->is_monitored = true;
        $bumdesa->monitoring_catatan = $request->catatan;
        $bumdesa->save();

        return redirect()->route('adminkab.monitoring.index', ['tab' => 'manual'])->with('success', 'BUMDesa berhasil ditambahkan ke daftar monitoring manual.');
    }

    public function destroy($id)
    {
        $bumdesa = Bumdesa::findOrFail($id);
        if ($bumdesa->kabupaten_id !== auth()->user()->kabupaten_id) {
            abort(403);
        }

        $bumdesa->is_monitored = false;
        $bumdesa->monitoring_catatan = null;
        $bumdesa->save();

        return redirect()->back()->with('success', 'BUMDesa berhasil dihapus dari daftar monitoring manual.');
    }

    public function ingatkan($id)
    {
        $bumdesa = Bumdesa::findOrFail($id);
        if ($bumdesa->kabupaten_id !== auth()->user()->kabupaten_id) {
            abort(403);
        }

        if ($bumdesa->user) {
            $bumdesa->user->notify(new \App\Notifications\PengingatMonitoring('Anda mendapat pengingat dari Admin Kabupaten terkait pelaporan dan kelengkapan data BUMDesa Anda. Harap segera melengkapi laporan terbaru.'));
            return redirect()->back()->with('success', 'Pengingat berhasil dikirim ke notifikasi dashboard BUMDesa ' . $bumdesa->name . '.');
        }

        return redirect()->back()->with('error', 'BUMDesa belum memiliki akun pengguna yang aktif.');
    }
}
