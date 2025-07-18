<?php

namespace App\Http\Controllers;

use App\Models\Agence;
use App\Models\Representation;
use App\Models\Secteur;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class HomeController extends ExtendedController
{
    //
    public function index(){
        $user = auth()->user();
        //auth()->logout();
       //return redirect('/login');
      // dd(auth()->user());
        if($user){
            $ux = User::find($user->id);
            Session::put('user',$ux);
            $role_id = $user->role_id;
           if($role_id == 1){
            return redirect('/admin/dashboard');
           }
           if($role_id == 2){
            return redirect('/pca/dashboard');
           }
           if($role_id == 3){
                return redirect('/adm/dashboard');
            }
           if($role_id == 4){
                return redirect('/dg/dashboard');
            }
           if($role_id == 5){
                return redirect('/dga/dashboard');
            }
           if($role_id == 6){
                return redirect('/respexp/dashboard');
            }
           if($role_id == 7){
                return redirect('/respaud/dashboard');
            }
           if($role_id == 8){
                return redirect('/respci/dashboard');
            }
           if($role_id == 9){
                return redirect('/reri/dashboard');
            }
           if($role_id == 10){
                return redirect('/reju/dashboard');
            }
           if($role_id == 11){
                $region = Representation::find($user->representation_id);
                Session::put('region',$region);
                return redirect('/regional/dashboard');
           }
           if($role_id == 12){
            $agence = Agence::find($user->agence_id);
            Session::put('agence',$agence);
            return redirect('/ca/dashboard');
           }
           if($role_id == 13){
            $agence = Agence::find($user->agence_id);
            Session::put('agence',$agence);
            return redirect('/gestionnaire/dashboard');
           }
           if($role_id == 14){
            $agence = Agence::find($user->agence_id);
            Session::put('agence',$agence);
            return redirect('/analyste/dashboard');
           }

           if($role_id == 19){
            return redirect('/program/dashboard');
           }

           if($role_id == 20){
            $secteur = Secteur::find($user->secteur_id);
            Session::put('secteur',$secteur);
            //dd($secteur);
            return redirect('/sectoriel/dashboard');
           }

           if($role_id == 21){
            return redirect('/structuration/gestionnaire/dashboard');
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
