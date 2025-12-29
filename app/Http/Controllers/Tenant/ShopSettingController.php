<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ShopSettingController extends Controller
{
    public function edit()
    {
        $tenant = auth()->user()->tenant;
        return view('tenant.settings.shop', compact('tenant'));
    }

    public function update(Request $request)
    {
        $tenant = auth()->user()->tenant;

        $validated = $request->validate([
            'primary_color' => 'nullable|string|size:7|regex:/^#[0-9a-fA-F]{6}$/',
            'secondary_color' => 'nullable|string|size:7|regex:/^#[0-9a-fA-F]{6}$/',
            'logo' => 'nullable|image|max:2048', // 2MB
            'favicon' => 'nullable|image|max:1024', // 1MB
        ]);

        if ($request->hasFile('logo')) {
            // Delete old logo if exists?
            if ($tenant->logo) {
                 Storage::disk('public')->delete($tenant->logo);
            }
            $path = $request->file('logo')->store('tenants/' . $tenant->id . '/branding', 'public');
            $tenant->logo = $path;
        }

        if ($request->hasFile('favicon')) {
            if ($tenant->favicon) {
                 Storage::disk('public')->delete($tenant->favicon);
            }
            $path = $request->file('favicon')->store('tenants/' . $tenant->id . '/branding', 'public');
            $tenant->favicon = $path;
        }

        $tenant->primary_color = $request->input('primary_color', '#4f46e5');
        $tenant->secondary_color = $request->input('secondary_color', '#1f2937');
        
        $tenant->save();

        return back()->with('success', 'Shop settings updated successfully.');
    }
}
