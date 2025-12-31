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
        // 1. Try to get from route parameter (most reliable if using Route::domain)
        $domain = $request->route('domain');

        // 2. Fallback: Parse host if route param is missing
        if (!$domain) {
            $host = $request->getHost();
            $appHost = parse_url(config('app.url'), PHP_URL_HOST);
            
            if ($appHost && str_ends_with($host, '.' . $appHost)) {
                $domain = substr($host, 0, -strlen('.' . $appHost));
            } else {
                // Fallback for simple cases (e.g. localhost)
                $parts = explode('.', $host);
                if (count($parts) > 1) {
                    $domain = $parts[0];
                } else {
                    $domain = $host;
                }
            }
        }
        
        // Lookup by domain slug
        $tenant = \App\Models\Tenant::where('domain', $domain)->first();

        if ($tenant) {
            $request->attributes->add(['tenant' => $tenant]);
            app()->instance('tenant', $tenant);
            
            // Optional: Forget parameter if controllers don't need it
            // $request->route()->forgetParameter('domain');

            return $next($request);
        }

        abort(404, 'Tenant not found.');
    }
}
