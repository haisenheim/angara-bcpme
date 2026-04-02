<?php

namespace App\Http\Middleware;

use App\Models\Agence;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class Gestionnaire
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        $agence = Agence::find($user->agence_id);
        if($user->role_id!=13){
            return redirect('/login');
        }
        $path = request()->getPathInfo();
        $parts = array_values(array_filter(explode('/', $path)));
        // Codes alignés sur resources/views/Layouts/gestionnaire.blade.php
        $active = 1;
        if (in_array('dossiers', $parts) || in_array('dossier', $parts) || in_array('instruction', $parts) || in_array('folders', $parts)) {
            $active = 201;
        } elseif (in_array('prospects', $parts)) {
            $active = 404;
        } elseif ($this->isProgrammesNavSection($parts)) {
            $active = 405;
        } elseif (in_array('entites', $parts) || in_array('entite', $parts) || in_array('entities', $parts)) {
            $active = 403;
        } elseif (in_array('entreprises', $parts) || in_array('entreprise', $parts)) {
            $active = 401;
        } elseif (in_array('users', $parts)) {
            $active = 6;
        } elseif (in_array('territoire', $parts)) {
            $active = 701;
        }
        Session::put('active',$active);
        Session::put('agence',$agence);
        return $next($request);
    }

    /**
     * Menu « programmes » : index /programmes et routes /programme/… sauf entreprise|entite/programme.
     */
    private function isProgrammesNavSection(array $parts): bool
    {
        if (in_array('programmes', $parts)) {
            return true;
        }
        if (!in_array('programme', $parts)) {
            return false;
        }
        if (in_array('entreprise', $parts) || in_array('entite', $parts) || in_array('entreprises', $parts) || in_array('entites', $parts)) {
            return false;
        }

        return true;
    }
}
