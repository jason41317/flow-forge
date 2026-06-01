<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use App\Support\Tenant\TenantManager;
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
        $user = $request->user();

        if (! $user) {
            abort(401);
        }

        $tenant = Tenant::find($user->tenant_id);

        if (! $tenant) {
            abort(403, 'Tenant not found');
        }

        app(TenantManager::class)->set($tenant);

        return $next($request);
    }
}
