<?php

namespace App\Http\Middleware;

use App\Models\Agence;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class ChefFiliere
{
    /**
     * Espace chef de filière : profil dédié + session agence (aligné chef d'agence / gestionnaire).
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        $expected = (int) config('angara.role_chef_filiere', 24);
        if (! $user || (int) $user->role_id !== $expected) {
            return redirect('/login');
        }

        $agence = Agence::find($user->agence_id);
        Session::put('agence', $agence);
        Session::put('user', User::find($user->id));

        return $next($request);
    }
}
