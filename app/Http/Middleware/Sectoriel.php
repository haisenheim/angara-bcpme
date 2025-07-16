<?php

namespace App\Http\Middleware;

use App\Models\Agence;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class Sectoriel
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
        if($user->role_id!=20){
            return redirect('/login');
        }
        $path = request()->getPathInfo();
        $parts = explode('/',$path);
        $active = 1;
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
        if(in_array('territoire',$parts)){
            $active = 701;
        }
        Session::put('active',$active);
        Session::put('agence',$agence);
        return $next($request);
    }
}
