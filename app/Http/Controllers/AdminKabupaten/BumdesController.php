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
        return view('adminkab.bumdes.create');
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

        return view('adminkab.bumdes.edit', compact('bumde'));
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
            'klasifikasi' => 'nullable|string|in:Dasar,Berkembang,Maju',
            'status' => 'required|in:active,inactive',
        ]);

        $bumde->update($request->only('name', 'desa', 'kecamatan', 'klasifikasi', 'status'));

        return redirect()->route('adminkab.bumdes.index')->with('success', 'Data BUMDesa berhasil diperbarui.');
    }

    public function destroy(Bumdesa $bumde)
    {
        if ($bumde->kabupaten_id !== auth()->user()->kabupaten_id) {
            abort(403);
        }

        // Delete user along with bumdes
        User::find($bumde->user_id)?->delete(); // automatically cascades bumdes due to constraints or handles orphan

        return redirect()->route('adminkab.bumdes.index')->with('success', 'BUMDesa berhasil dihapus.');
    }
}
