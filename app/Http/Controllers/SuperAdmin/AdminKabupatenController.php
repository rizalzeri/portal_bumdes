<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Province;
use App\Models\Kabupaten;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminKabupatenController extends Controller
{
    public function index()
    {
        $admins = User::where('role', 'admin_kabupaten')->with('kabupaten.province')->get();
        return view('superadmin.admin_kabupaten.index', compact('admins'));
    }

    public function create()
    {
        $provinces = Province::orderBy('name')->get();
        return view('superadmin.admin_kabupaten.create', compact('provinces'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'province_id' => 'required|exists:provinces,id',
            'kabupaten_id' => 'required|exists:kabupatens,id',
            'email' => 'required|email|unique:users,email',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:6',
        ]);

        $kabupaten = Kabupaten::find($request->kabupaten_id);

        User::create([
            'name' => 'Admin ' . $kabupaten->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin_kabupaten',
            'kabupaten_id' => $request->kabupaten_id,
        ]);

        return redirect()->route('superadmin.adminkab.index')->with('success', 'Admin Kabupaten berhasil ditambahkan.');
    }

    public function destroy(User $user)
    {
        if ($user->role !== 'admin_kabupaten') {
            abort(403);
        }
        
        $user->delete();
        return redirect()->route('superadmin.adminkab.index')->with('success', 'Admin Kabupaten berhasil dihapus.');
    }
}
