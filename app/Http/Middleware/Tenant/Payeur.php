<?php

namespace App\Http\Middleware\Tenant;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class Payeur
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        if($user->role_id!=4){
            return redirect(route('login'));
        }
        $path = request()->getPathInfo();
        $parts = explode('/',$path);
        $active = 1;


        if(in_array('entrees',$parts)){
            $active = 2;
        }
        if(in_array('sorties',$parts)){
            $active = 3;
        }
        if(in_array('previsions',$parts)){
            $active = 4;
        }
        if(in_array('entrepots',$parts)){
            $active = 5;
        }
        if(in_array('membres',$parts)){
            $active = 6;
        }
        if(in_array('agents',$parts)){
            $active = 7;
        }
        Session::put('active',$active);
        return $next($request);
    }
}
