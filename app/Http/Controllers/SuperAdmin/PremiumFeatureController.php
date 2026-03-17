<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PremiumFeature;

class PremiumFeatureController extends Controller
{
    public function index()
    {
        $features = PremiumFeature::orderBy('module', 'asc')->orderBy('action', 'asc')->get();
        $modules = PremiumFeature::getModules();
        $actions = PremiumFeature::getActions();
        return view('superadmin.premium_features.index', compact('features', 'modules', 'actions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'module' => 'required|string',
            'action' => 'required|string',
            'is_premium' => 'required|boolean',
            'free_limit' => 'nullable|integer',
            'fallback_action' => 'required|string',
        ]);
        
        // Cek unique
        $exists = PremiumFeature::where('module', '=', $request->module)
            ->where('action', '=', $request->action)
            ->exists();
            
        if($exists) {
            return redirect()->back()->with('error', 'Konfigurasi Fitur ini sudah ada!');
        }

        PremiumFeature::create($request->all());

        return redirect()->back()->with('success', 'Konfigurasi Premium berhasil ditambahkan.');
    }

    public function update(Request $request, PremiumFeature $premiumFeature)
    {
        $request->validate([
            'module' => 'required|string',
            'action' => 'required|string',
            'is_premium' => 'required|boolean',
            'free_limit' => 'nullable|integer',
            'fallback_action' => 'required|string',
        ]);

        $premiumFeature->update($request->all());

        return redirect()->back()->with('success', 'Konfigurasi Premium berhasil diperbarui.');
    }

    public function destroy(PremiumFeature $premiumFeature)
    {
        $premiumFeature->delete();
        return redirect()->back()->with('success', 'Konfigurasi Premium dihapus.');
    }

    public function inlineUpdate(Request $request, $id)
    {
        $request->validate([
            'field' => 'required|string',
            'value' => 'nullable'
        ]);

        $feature = PremiumFeature::findOrFail($id);
        
        if ($request->field == 'is_premium') {
            $feature->is_premium = filter_var($request->value, FILTER_VALIDATE_BOOLEAN);
        } else {
            $feature->{$request->field} = $request->value;
        }

        $feature->save();

        return response()->json(['success' => true]);
    }
}
