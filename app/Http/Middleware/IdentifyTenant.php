<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Tenant;

class IdentifyTenant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        
        // Simple lookup by domain column
        $tenant = Tenant::where('domain', $host)->first();

        if ($tenant) {
            $request->attributes->add(['tenant' => $tenant]);
            app()->instance('tenant', $tenant);
            return $next($request);
        }

        abort(404, 'Tenant not found.');
    }
}
