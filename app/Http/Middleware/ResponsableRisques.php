<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResponsableRisques
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        $expected = (int) (config('angara.role_responsable_risques') ?? 12);

        if (! $user || (int) $user->role_id !== $expected) {
            return redirect('/login');
        }

        return $next($request);
    }
}
