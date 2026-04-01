<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Domain;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Remplace InitializeTenancyByDomain (Stancl) : résout la coopérative via la table domains.
 */
class BindTenantFromHost
{
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();

        if (in_array($host, config('structuration.central_domains'), true)) {
            abort(403, 'Accès portail coopérative uniquement depuis un domaine dédié à une coopérative.');
        }

        $domain = Domain::query()->where('domain', $host)->first();
        if (! $domain) {
            abort(404, 'Domaine inconnu ou coopérative introuvable.');
        }

        $tenant = $domain->tenant;
        if (! $tenant) {
            abort(404);
        }

        app()->instance('tenant', $tenant);

        return $next($request);
    }
}
