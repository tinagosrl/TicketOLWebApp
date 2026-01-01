<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_tenants' => Tenant::count(),
            'active_tenants' => Tenant::where('is_active', true)->count(),
            'pending_tenants' => Tenant::where('is_active', false)->count(),
            'total_users' => User::count(),
        ];

        $recentTenants = Tenant::with('subscription.plan')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentTenants'));
    }
}
