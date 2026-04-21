<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Auditeur
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        $expected = (int) config('angara.role_auditeur', 22);

        if (! $user || (int) $user->role_id !== $expected) {
            return redirect('/login');
        }

        return $next($request);
    }
}
