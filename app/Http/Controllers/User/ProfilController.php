<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bumdesa;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    public function index($slug)
    {
        $bumdes = Bumdesa::where('user_id', auth()->id())->orWhere('id', auth()->user()->bumdes_id)->firstOrFail();
        return view('user.profil.index', compact('bumdes'));
    }

    public function update(Request $request, $slug)
    {
        $bumdes = Bumdesa::where('user_id', auth()->id())->orWhere('id', auth()->user()->bumdes_id)->firstOrFail();

        $request->validate([
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'about' => 'nullable|string',
            'visi_misi' => 'nullable|string',
            'sejarah' => 'nullable|string',
            'badan_hukum' => 'nullable|string|max:255',
            'nomor_sertifikat' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'facebook' => 'nullable|url|max:255',
            'instagram' => 'nullable|string|max:255', 
            'address' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'logo' => 'nullable|image|max:2048',
            'musdes_terakhir' => 'nullable|date',
            'laporan_dinas_status' => 'nullable|in:sudah,belum',
            'laporan_dinas_link' => 'nullable|url',
            'audit_internal_terakhir' => 'nullable|date',
            'pemeringkatan' => 'nullable|string',
            'status' => 'nullable|string',
        ]);

        $data = $request->except(['_token', '_method', 'logo']);

        if ($request->hasFile('logo')) {
            if ($bumdes->logo && Storage::disk('public')->exists($bumdes->logo)) {
                Storage::disk('public')->delete($bumdes->logo);
            }
            $data['logo'] = $request->file('logo')->store('bumdes_logos', 'public');
        }

        $bumdes->update($data);

        return redirect()->route('user.profil.index')->with('success', 'Profil BUMDesa berhasil diperbarui.');
    }
}
