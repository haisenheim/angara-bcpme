<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * À retirer après diagnostic : arrête la requête sur POST /login avant le reste du pipeline web.
 */
class DebugDumpLoginFormSubmit
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('login') && $request->isMethod('POST')) {
            dd([
                'path' => $request->path(),
                'method' => $request->method(),
                'input' => $request->all(),
                'headers' => $request->headers->all(),
            ]);
        }

        return $next($request);
    }
}
