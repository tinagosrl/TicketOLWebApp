<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTenantActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // If user is a tenant user/admin
        if ($user && $user->tenant_id) {
            // Refresh tenant relation
            $user->load('tenant'); 
            
            if (!$user->tenant->is_active) {
                // Allow access to billing routes
                if ($request->routeIs('billing.*') || $request->is('logout')) {
                    return $next($request);
                }
                
                return redirect()->route('billing.payment');
            }
        }

        return $next($request);
    }
}
