<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PremiumFeature;

class PremiumFeatureController extends Controller
{
    public function index()
    {
        $features = PremiumFeature::orderBy('category', 'asc')->orderBy('name', 'asc')->get();
        return view('superadmin.premium_features.index', compact('features'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required|string|unique:premium_features,key',
            'name' => 'required|string',
            'category' => 'required|string',
            'description' => 'nullable|string',
            'is_premium' => 'required|boolean',
            'free_limit' => 'nullable|integer',
            'fallback_action' => 'required|string',
        ]);

        PremiumFeature::create($request->all());

        return redirect()->back()->with('success', 'Fitur Premium berhasil ditambahkan.');
    }

    public function update(Request $request, PremiumFeature $premiumFeature)
    {
        $request->validate([
            'key' => 'required|string|unique:premium_features,key,' . $premiumFeature->id,
            'name' => 'required|string',
            'category' => 'required|string',
            'description' => 'nullable|string',
            'is_premium' => 'required|boolean',
            'free_limit' => 'nullable|integer',
            'fallback_action' => 'required|string',
        ]);

        $premiumFeature->update($request->all());

        return redirect()->back()->with('success', 'Fitur Premium berhasil diperbarui.');
    }

    public function destroy(PremiumFeature $premiumFeature)
    {
        $premiumFeature->delete();
        return redirect()->back()->with('success', 'Fitur Premium dihapus.');
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
