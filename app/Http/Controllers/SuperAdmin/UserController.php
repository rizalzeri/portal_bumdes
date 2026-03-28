<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Kabupaten;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('kabupaten')->get();
        $kabupatens = Kabupaten::orderBy('name')->get();
        return view('superadmin.user.index', compact('users', 'kabupatens'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => ['required', Rule::in(['superadmin', 'admin_kabupaten', 'user'])],
            'kabupaten_id' => 'required_if:role,admin_kabupaten|required_if:role,user|nullable|exists:kabupatens,id',
            'phone' => 'nullable|string|max:20',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'kabupaten_id' => in_array($request->role, ['admin_kabupaten', 'user']) ? $request->kabupaten_id : null,
            'phone' => $request->phone,
        ]);

        return redirect()->route('superadmin.user.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in(['superadmin', 'admin_kabupaten', 'user'])],
            'kabupaten_id' => 'required_if:role,admin_kabupaten|required_if:role,user|nullable|exists:kabupatens,id',
            'phone' => 'nullable|string|max:20',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'kabupaten_id' => in_array($request->role, ['admin_kabupaten', 'user']) ? $request->kabupaten_id : null,
            'phone' => $request->phone,
        ];

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('superadmin.user.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }
        
        try {
            $user->delete();
            return redirect()->route('superadmin.user.index')->with('success', 'Pengguna berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('superadmin.user.index')->with('error', 'Pengguna tidak dapat dihapus karena memiliki data terkait (BUMDes, dll) yang masih aktif.');
        }
    }
}
