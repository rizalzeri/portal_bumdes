<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengurus;
use App\Models\Bumdesa;
use Illuminate\Support\Facades\Storage;

class PersonaliaController extends Controller
{
    public function index()
    {
        $bumdes = Bumdesa::where('user_id', auth()->id())->firstOrFail();
        $pengurus = Pengurus::where('bumdes_id', $bumdes->id)->orderBy('role')->get();

        return view('user.personalia.index', compact('pengurus', 'bumdes'));
    }

    public function store(Request $request)
    {
        $bumdes = Bumdesa::where('user_id', auth()->id())->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
            'photo' => 'nullable|image|max:2048',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('pengurus', 'public');
        }

        Pengurus::create([
            'bumdes_id' => $bumdes->id,
            'name' => $request->name,
            'role' => $request->role,
            'position' => $request->role,
            'phone' => $request->phone,
            'photo' => $photoPath,
        ]);

        return redirect()->route('user.personalia.index')->with('success', 'Data pengurus berhasil ditambahkan.');
    }

    public function update(Request $request, Pengurus $personalia)
    {
        $bumdes = Bumdesa::where('user_id', auth()->id())->firstOrFail();

        if ($personalia->bumdes_id !== $bumdes->id) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
            'photo' => 'nullable|image|max:2048',
        ]);

        $data = $request->only('name', 'role', 'phone');
        $data['position'] = $request->role;

        if ($request->hasFile('photo')) {
            if ($personalia->photo && Storage::disk('public')->exists($personalia->photo)) {
                Storage::disk('public')->delete($personalia->photo);
            }
            $data['photo'] = $request->file('photo')->store('pengurus', 'public');
        }

        $personalia->update($data);

        return redirect()->route('user.personalia.index')->with('success', 'Data pengurus berhasil diperbarui.');
    }

    public function destroy(Pengurus $personalia)
    {
        $bumdes = Bumdesa::where('user_id', auth()->id())->firstOrFail();
        if ($personalia->bumdes_id !== $bumdes->id) {
            abort(403);
        }

        if ($personalia->photo && Storage::disk('public')->exists($personalia->photo)) {
            Storage::disk('public')->delete($personalia->photo);
        }
        $personalia->delete();

        return redirect()->route('user.personalia.index')->with('success', 'Data pengurus dihapus.');
    }
}
