<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class BrandingController extends Controller
{
    public function edit(): View
    {
        $settings = SystemSetting::pluck('value', 'key')->all();
        return view('admin.branding.edit', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'system_name' => 'nullable|string|max:255',
            'system_logo' => 'nullable|image|max:2048', // 2MB Max
            'system_favicon' => 'nullable|image|max:1024',
        ]);

        if ($request->has('system_name')) {
            SystemSetting::updateOrCreate(['key' => 'system_name'], ['value' => $request->system_name]);
        }

        if ($request->hasFile('system_logo')) {
            $path = $request->file('system_logo')->store('branding', 'public');
            SystemSetting::updateOrCreate(['key' => 'system_logo'], ['value' => $path]);
        }

        if ($request->hasFile('system_favicon')) {
            $path = $request->file('system_favicon')->store('branding', 'public');
            SystemSetting::updateOrCreate(['key' => 'system_favicon'], ['value' => $path]);
        }

        return redirect()->back()->with('success', 'Branding updated successfully.');
    }
}
