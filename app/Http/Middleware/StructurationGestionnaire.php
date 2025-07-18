<?php

namespace App\Http\Middleware;

use App\Models\Agence;
use App\Models\Banque;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class StructurationGestionnaire
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        $agence = Banque::find($user->banque_id);
        if($user->role_id!=21){
            return redirect('/login');
        }
        $path = request()->getPathInfo();
        $parts = explode('/',$path);
        $active = 1;
        if(in_array('comptes',$parts)){
            $active = 2;
        }
        if(in_array('requests',$parts)){
            $active = 3;
        }
        Session::put('active',$active);
        Session::put('banque',$agence);
        return $next($request);
    }
}
