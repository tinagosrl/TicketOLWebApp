<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rules;
use Illuminate\Auth\Events\Registered;

class InvitationController extends Controller
{
    public function accept(string $token): View
    {
        $invitation = Invitation::where('token', $token)
            ->where('expires_at', '>', now())
            ->firstOrFail();

        return view('auth.register-invite', compact('invitation'));
    }

    public function process(Request $request, string $token): RedirectResponse
    {
        $invitation = Invitation::where('token', $token)
            ->where('expires_at', '>', now())
            ->firstOrFail();

        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $invitation->email,
            'password' => Hash::make($request->password),
            'tenant_id' => $invitation->tenant_id,
            'role' => $invitation->role,
        ]);

        $invitation->delete();

        event(new Registered($user));
        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
