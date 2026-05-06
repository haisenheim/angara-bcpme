<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        if($user->role_id!=1){
            return redirect('/login');
        }
        $path = request()->getPathInfo();
        $parts = explode('/',$path);
        $active = 1;
        if(in_array('dossiers',$parts) || in_array('instruction',$parts)){
            $active = 201;
        }
        if(in_array('programmes',$parts)){
            $active = 3;
        }
        if(in_array('entreprises',$parts)|| in_array('entreprise',$parts)){
            $active = 4;
        }
        if(in_array('prospects',$parts)){
            $active = 5;
        }
        if(in_array('users',$parts)){
            $active = 6;
        }
        if(in_array('delegation-pouvoirs',$parts)){
            $active = 804;
        }
        if(in_array('fichiers-types',$parts)){
            $active = 807;
        }
        if(in_array('territoire',$parts)){
            $active = 701;
        }
        if(in_array('agences',$parts)){
            $active = 702;
        }
        if(in_array('organismes',$parts)){
            $active = 801;
        }
        if(in_array('banques',$parts)){
            $active = 802;
        }
        Session::put('active',$active);
        return $next($request);
    }
}
