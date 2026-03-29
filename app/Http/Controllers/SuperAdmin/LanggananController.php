<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Langganan;

class LanggananController extends Controller
{
    public function index()
    {
        $langganans = Langganan::with(['bumdes', 'kabupaten'])->orderBy('created_at', 'desc')->get();
        return view('superadmin.langganan.index', compact('langganans'));
    }

    public function create()
    {
        // View for manually creating subscription or defining subscription packages
        // Actually this index view can handle all with modals if needed.
    }

    public function store(Request $request)
    {
        // Simple manual subscription creation if needed by superadmin
        $request->validate([
            'bumdes_id' => 'required_without:kabupaten_id|nullable|exists:bumdes,id',
            'kabupaten_id' => 'required_without:bumdes_id|nullable|exists:kabupatens,id',
            'package_name' => 'required|string|max:255',
            'status' => 'required|in:active,expired,pending',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        Langganan::create($request->all());

        return redirect()->route('superadmin.langganan.index')->with('success', 'Data langganan berhasil ditambahkan.');
    }

    public function update(Request $request, Langganan $langganan)
    {
        $request->validate([
            'status' => 'required|in:active,expired,pending',
            'end_date' => 'required|date',
        ]);

        $langganan->update([
            'status' => $request->status,
            'end_date' => $request->end_date,
        ]);

        return redirect()->route('superadmin.langganan.index')->with('success', 'Status langganan berhasil diperbarui.');
    }

    public function destroy(Langganan $langganan)
    {
        $langganan->delete();
        return redirect()->route('superadmin.langganan.index')->with('success', 'Data langganan berhasil dihapus.');
    }
}
