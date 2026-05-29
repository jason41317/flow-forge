<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTenant
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $slug = $request->header('X-Tenant');

        if (! $slug) {
            abort(403, 'Tenant missing');
        }

        $tenant = Tenant::where('slug', $slug)->first();

        if (! $tenant) {
            abort(403, 'Invalid tenant');
        }

        app()->instance('tenant', $tenant);

        return $next($request);
    }
}
