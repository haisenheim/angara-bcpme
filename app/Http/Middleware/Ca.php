<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class Ca
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        $path = request()->getPathInfo();
        $chefAgence = (int) config('angara.role_chef_agence', 15);
        $dg = (int) config('angara.role_dg', 4);
        $dga = (int) config('angara.role_dga', 5);
        $allowedForInstructionDossiers = [$chefAgence, $dg, $dga];
        if (str_contains($path, 'workflow/instruction-dossiers')) {
            if (! $user || ! in_array((int) $user->role_id, $allowedForInstructionDossiers, true)) {
                return redirect('/login');
            }
        } elseif (! $user || (int) $user->role_id !== $chefAgence) {
            return redirect('/login');
        }
        $parts = explode('/', $path);
        $active = 1;
        if (in_array('dossiers', $parts) || in_array('instruction', $parts)) {
            $active = 201;
        }
        if (in_array('programmes', $parts)) {
            $active = 3;
        }
        if (in_array('entreprises', $parts) || in_array('entreprise', $parts)) {
            $active = 4;
        }
        if (in_array('prospects', $parts)) {
            $active = 5;
        }
        if (in_array('users', $parts)) {
            $active = 6;
        }
        if (in_array('territoire', $parts)) {
            $active = 701;
        }
        if (in_array('workflow', $parts)) {
            if (in_array('instruction-dossiers', $parts)) {
                $active = 408;
            } elseif (in_array('instructions', $parts)) {
                $active = 407;
            }
        }
        Session::put('active', $active);

        return $next($request);
    }
}
