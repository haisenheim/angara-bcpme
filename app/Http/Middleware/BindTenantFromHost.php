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

        // Domaine « central » (instruction, etc.) : pas de coopérative liée au Host.
        // Les routes communes (ex. / → login) sont dans routes/web.php ; ici on laisse passer
        // sans lier de tenant (comme PreventAccessFromCentralDomains côté Stancl).
        if (in_array($host, config('structuration.central_domains'), true)) {
            return $next($request);
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
