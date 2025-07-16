<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\ExtendedController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class HomeController extends ExtendedController
{
    //
    public function index(){
        $user = auth()->user();
        //dd($user->role_id);
        if($user){
            $ux = User::find($user->id);
            Session::put('user',$ux);
            $role_id = $user->role_id;
           if($role_id == 1){
            return redirect('/admin/dashboard');
           }
           if($role_id == 2){
            return redirect('/agent/dashboard');
           }
           if($role_id == 3){
            return redirect('/rstock/dashboard');
           }
           if($role_id == 4){
            return redirect('/payeur/dashboard');
           }
           return redirect('/login');
        }
    }

    public function profile(){
        $user = User::find(auth()->user()->id);
        return view('Auth.profile',compact('user'));
    }

    public function storeProfile(){
        $user = User::where('token',request()->id)->first();
        if($user){
            $photo = request()->photo;
            if($photo){
                $user->photo_uri = $this->entityImgCreate($photo,'profil',$user->token);
            }
            $user->name = request()->name;
            $user->password = bcrypt(request()->password);
            $user->email = request()->email;
            $user->save();
            Session::flash('success','Mise à jour effectuée avec succès!');
        }
        return back();
    }

    public function logout(){
        Auth::logout();
        return redirect('/login');
    }
}
