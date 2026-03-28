<?php

namespace App\Http\Controllers\AdminKabupaten;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bumdesa;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class BumdesController extends Controller
{
    public function index()
    {
        $kabupatenId = auth()->user()->kabupaten_id;
        $bumdes = Bumdesa::where('kabupaten_id', $kabupatenId)->with('user')->get();
        return view('adminkab.bumdes.index', compact('bumdes'));
    }

    public function create()
    {
        $kecamatans = \App\Models\Kecamatan::where('kabupaten_id', auth()->user()->kabupaten_id)->orderBy('name')->get();
        return view('adminkab.bumdes.create', compact('kecamatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'desa' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $kabupatenId = auth()->user()->kabupaten_id;

        // Create User account to manage this BUMDes
        $user = User::create([
            'name' => 'Pengurus ' . $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'kabupaten_id' => $kabupatenId,
        ]);

        // Create the BUMDes Profile
        Bumdesa::create([
            'user_id' => $user->id,
            'kabupaten_id' => $kabupatenId,
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . uniqid(),
            'desa' => $request->desa,
            'kecamatan' => $request->kecamatan,
            'status' => 'active',
        ]);

        return redirect()->route('adminkab.bumdes.index')->with('success', 'BUMDesa dan akun pengurus berhasil didaftarkan.');
    }

    public function edit(Bumdesa $bumde)
    {
        // Parameter automatically resolved by Implicit Binding
        // But need to ensure it belongs to this kabupaten
        if ($bumde->kabupaten_id !== auth()->user()->kabupaten_id) {
            abort(403);
        }

        $kecamatans = \App\Models\Kecamatan::where('kabupaten_id', auth()->user()->kabupaten_id)->orderBy('name')->get();

        return view('adminkab.bumdes.edit', compact('bumde', 'kecamatans'));
    }

    public function update(Request $request, Bumdesa $bumde)
    {
        if ($bumde->kabupaten_id !== auth()->user()->kabupaten_id) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'desa' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $bumde->update($request->only('name', 'desa', 'kecamatan', 'status'));

        return redirect()->route('adminkab.bumdes.index')->with('success', 'Data BUMDesa berhasil diperbarui.');
    }

    public function destroy(Bumdesa $bumde)
    {
        if ($bumde->kabupaten_id !== auth()->user()->kabupaten_id) {
            abort(403);
        }

        try {
            $userId = $bumde->user_id;
            $bumde->delete();
            
            if ($userId) {
                User::find($userId)?->delete();
            }

            return redirect()->route('adminkab.bumdes.index')->with('success', 'BUMDesa beserta akun pengurusnya berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus! BUMDesa ini masih memiliki data aktif (laporan, unit usaha, dll) di sistem.');
        }
    }

    public function toggleStatus($id)
    {
        $bumde = Bumdesa::findOrFail($id);
        
        if ($bumde->kabupaten_id !== auth()->user()->kabupaten_id) {
            abort(403);
        }

        $bumde->is_active = !$bumde->is_active;
        $bumde->status = $bumde->is_active ? 'active' : 'inactive';
        $bumde->save();

        // Also update the associated user subscription status if needed
        $user = User::where('bumdes_id', $bumde->id)->orWhere('id', $bumde->user_id)->first();
        if ($user) {
            if ($bumde->is_active && $user->subscription_status === 'inactive') {
                $user->subscription_status = 'active_gratis';
                $user->save();
            } else if (!$bumde->is_active) {
                $user->subscription_status = 'inactive';
                $user->save();
            }
        }

        return redirect()->back()->with('success', 'Status BUMDesa berhasil diubah.');
    }
}
