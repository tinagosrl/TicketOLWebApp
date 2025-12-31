<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImpersonationController extends Controller
{
    public function leave()
    {
        if (!session()->has('impersonator_id')) {
            return redirect()->route('dashboard');
        }

        
        $originalUserId = session('impersonator_id');
        $impersonatedId = auth()->id();

        \App\Models\ImpersonationLog::create([
            'impersonator_id' => $originalUserId,
            'impersonated_id' => $impersonatedId,
            'action' => 'leave',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    
        
        // Login back as the original user
        Auth::loginUsingId($originalUserId);
        
        // Clear session
        session()->forget('impersonator_id');
        
        return redirect()->route('admin.tenants.index')->with('success', 'Bentornato, SuperAdmin!');
    }
}
