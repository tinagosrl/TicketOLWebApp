<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\GlobalSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandingController extends Controller
{
    public function edit()
    {
        $settings = GlobalSetting::where('group', 'branding')->pluck('value', 'key');
        return view('admin.branding.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $messages = [
            'logo.image' => 'Il logo deve essere un\'immagine valida.',
            'logo.max' => 'La dimensione del logo non può superare i 2MB.',
            'favicon.mimes' => 'La favicon deve essere un file di tipo: png, jpg, jpeg, ico.',
            'favicon.max' => 'La dimensione della favicon non può superare 1MB.',
            'primary_color.max' => 'Il colore primario non è valido.',
            'app_name.max' => 'Il nome dell\'applicazione è troppo lungo.',
        ];

        $request->validate([
            'logo' => 'nullable|image|max:2048', // 2MB Max
            'favicon' => 'nullable|mimes:png,jpg,jpeg,ico|max:1024',
            'primary_color' => 'nullable|string|max:7',
            'app_name' => 'nullable|string|max:255',
        ], $messages);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('branding', 'public');
            GlobalSetting::set('logo_path', Storage::url($path), 'branding');
        }

        if ($request->hasFile('favicon')) {
            $path = $request->file('favicon')->store('branding', 'public');
            GlobalSetting::set('favicon_path', Storage::url($path), 'branding');
        }

        if ($request->primary_color) {
            GlobalSetting::set('primary_color', $request->primary_color, 'branding');
        }
        
        if ($request->app_name) {
             GlobalSetting::set('app_name', $request->app_name, 'branding');
        }

        return redirect()->back()->with('success', 'Branding aggiornato con successo.');
    }
}
