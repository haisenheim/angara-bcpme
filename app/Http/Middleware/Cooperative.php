<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class Cooperative
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        if($user->role_id!=21){
            return redirect('/login');
        }
        $path = request()->getPathInfo();
        $parts = explode('/',$path);
        $active = 1;
        if(in_array('entrees',$parts)){
            $active = 4;
        }
        if(in_array('sorties',$parts)){
            $active = 5;
        }
        if(in_array('entrees',$parts) && in_array('create',$parts)){
            $active = 2;
        }
        if(in_array('sorties',$parts) && in_array('create',$parts)){
            $active = 3;
        }

        if(in_array('appels',$parts)){
            $active = 7;
        }
        if(in_array('requests',$parts)){
            $active = 8;
        }
        if(in_array('appels',$parts) && in_array('create',$parts)){
            $active = 6;
        }

        if(in_array('members',$parts)){
            $active = 9;
        }

        if(in_array('agents',$parts)){
            $active = 10;
        }

        if(in_array('entrepots',$parts)){
            $active = 701;
        }

        if(in_array('villages',$parts)){
            $active = 702;
        }
        
        
        Session::put('active',$active);
        return $next($request);
    }
}
