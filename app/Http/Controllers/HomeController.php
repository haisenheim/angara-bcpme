<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    //
    public function index(){
        $user = auth()->user();
       // dd(auth()->user());
        if($user){
            $role_id = $user->role_id;
           if($role_id == 1){
            return redirect('/admin/dashboard');
           }
           if($role_id == 2){
            return redirect('/pca/dashboard');
           }
           if($role_id == 12){
            return redirect('/ca/dashboard');
           }
           if($role_id == 13){
            return redirect('/gestionnaire/dashboard');
           }
           if($role_id == 14){
            return redirect('/analyste/dashboard');
           }




           return redirect('/login');
        }
    }

    public function logout(){
        Auth::logout();
        return redirect('/login');
    }
}
