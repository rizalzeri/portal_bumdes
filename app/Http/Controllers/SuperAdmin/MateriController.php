<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TemplateMateri;
use Illuminate\Support\Facades\Storage;

class MateriController extends Controller
{
    public function index()
    {
        $materis = TemplateMateri::orderBy('created_at', 'desc')->get();
        return view('superadmin.materi.index', compact('materis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:materi,template,panduan',
            'file_url' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip|max:51200', // max 50MB
        ]);

        $filePath = $request->file('file_url')->store('materi', 'public');

        TemplateMateri::create([
            'title' => $request->title,
            'type' => $request->type,
            'file_url' => $filePath,
        ]);

        return redirect()->route('superadmin.materi.index')->with('success', 'Dokumen materi/template berhasil diunggah.');
    }

    public function destroy(TemplateMateri $materi)
    {
        if (Storage::disk('public')->exists($materi->file_url)) {
            Storage::disk('public')->delete($materi->file_url);
        }
        $materi->delete();

        return redirect()->route('superadmin.materi.index')->with('success', 'Dokumen berhasil dihapus.');
    }
}
