<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RespExp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        $expected = (int) config('angara.role_responsable_exploitation', 6);
        if (! $user || (int) $user->role_id !== $expected) {
            return redirect('/login');
        }

        return $next($request);
    }
}
