<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use App\Models\User;
use App\Notifications\InviteUser;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Notification;

class TeamController extends Controller
{
    public function index(): View
    {
        $tenant = auth()->user()->tenant;
        $members = $tenant->users()->where('id', '!=', auth()->id())->get();
        $invitations = $tenant->invitations;
        $plan = $tenant->currentPlan->plan ?? null;
        
        $maxSubAdmins = $plan ? $plan->max_subadmins : 0;
        $currentCount = $members->count() + $invitations->count();
        $canInvite = $maxSubAdmins > $currentCount;

        return view('tenant.team.index', compact('members', 'invitations', 'maxSubAdmins', 'canInvite'));
    }

    public function store(Request $request): RedirectResponse
    {
        $tenant = auth()->user()->tenant;
        $plan = $tenant->currentPlan->plan ?? null;
        $maxSubAdmins = $plan ? $plan->max_subadmins : 0;
        
        // Count existing subadmins and pending invites
        $currentCount = $tenant->users()->where('role', '!=', 'tenant_admin')->count() 
                      + $tenant->invitations()->count();

        if ($currentCount >= $maxSubAdmins) {
            return back()->with('error', 'Plan limit reached. Upgrade to invite more members.');
        }

        $request->validate([
            'email' => 'required|email|unique:users,email', // Should not be an existing user (for now)
        ]);
        
        // Check if invite already exists
        if ($tenant->invitations()->where('email', $request->email)->exists()) {
             return back()->with('error', 'Invitation already sent to this email.');
        }

        $invitation = Invitation::create([
            'tenant_id' => $tenant->id,
            'email' => $request->email,
            'token' => Str::random(32),
            'role' => 'sub_admin',
            'expires_at' => now()->addDays(7),
        ]);

        Notification::route('mail', $request->email)->notify(new InviteUser($invitation));

        return back()->with('success', 'Invitation sent!');
    }

    public function destroy(Invitation $invitation): RedirectResponse
    {
        // Ensure invitation belongs to tenant
        if ($invitation->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        $invitation->delete();
        return back()->with('success', 'Invitation cancelled.');
    }
}
