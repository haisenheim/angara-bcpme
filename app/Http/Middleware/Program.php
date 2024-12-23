<?php

namespace App\Http\Middleware;

use App\Models\Programme;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class Program
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        $program = Programme::find($user->programme_id);
        //dd($program);
        if($user->role_id!=19){
            return redirect('/login');
        }
        $path = request()->getPathInfo();
        $parts = explode('/',$path);
        $active = 1;
        if(in_array('dossiers',$parts) || !in_array('prospects',$parts) || in_array('instruction',$parts)){
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
        if(in_array('territoire',$parts)){
            $active = 701;
        }
        Session::put('active',$active);
        Session::put('program',$program);
        return $next($request);
    }
}
