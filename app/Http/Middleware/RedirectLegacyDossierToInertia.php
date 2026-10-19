<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * Cohabitation Blade / Inertia : les actions de workflow existantes redirigent vers la fiche
 * dossier Blade (`{espace}/dossiers/{token}`). Quand l'action est déclenchée depuis une page
 * Inertia (en-tête X-Inertia), on redirige vers la fiche Inertia du même espace.
 * Les requêtes Blade classiques ne sont pas concernées.
 */
class RedirectLegacyDossierToInertia
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $request->header('X-Inertia') || ! $response instanceof RedirectResponse) {
            return $response;
        }

        $target = $response->getTargetUrl();
        $path = parse_url($target, PHP_URL_PATH) ?: '';

        if (preg_match('#^/([a-z-]+)/dossiers/([A-Za-z0-9-]+)/?$#', $path, $m)
            && Route::has("{$m[1]}.v2.dossiers.show")) {
            $query = parse_url($target, PHP_URL_QUERY);
            $response->setTargetUrl(url("/{$m[1]}/v2/dossiers/{$m[2]}").($query ? "?$query" : ''));
        }

        return $response;
    }
}
