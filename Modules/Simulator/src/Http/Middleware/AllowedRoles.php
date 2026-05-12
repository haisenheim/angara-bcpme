<?php

namespace Modules\Simulator\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Allow access only to roles whitelisted in config('simulator.allowed_roles').
 * Aligned with the existing pattern (see app/Http/Middleware/ReJu.php).
 */
class AllowedRoles
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        if (! $user) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Authentification requise.'], 401)
                : redirect('/login');
        }

        $allowed = array_map('intval', (array) config('simulator.allowed_roles', []));
        if ($allowed === []) {
            return $next($request);
        }

        if (! in_array((int) $user->role_id, $allowed, true)) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Accès refusé au simulateur de crédit.'], 403)
                : abort(403, 'Accès refusé au simulateur de crédit.');
        }

        return $next($request);
    }
}
