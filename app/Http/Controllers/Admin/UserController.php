<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agence;
use App\Models\Banque;
use App\Models\Representation;
use App\Models\Role;
use App\Models\Secteur;
use App\Models\User;
use App\Models\Ville;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $items = User::where('role_id','>',1)->get();
        foreach ($items as $user) {
            if (empty($user->token)) {
                $user->forceFill([
                    'token' => sha1(uniqid((string) $user->id, true)),
                ])->saveQuietly();
            }
        }
        $roles = Role::where('metier',1)->get();
        $representations = Representation::all();
        $agences = Agence::all();
        $secteurs = Secteur::all();
        $banques = Banque::all();
        return view('/Admin/Users/index')->with(compact('items','roles','representations','agences','secteurs','banques'));
    }


    public function getRoles(){
        $items = Role::where('metier',1)->get();
        return view('/Admin/Users/roles')->with(compact('items'));
    }





    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = new User();
        $user->name = request()->name;
        $user->token = sha1(date('Yhmdsi'). auth()->user()->id);
        $user->password = bcrypt(request()->password);
        $user->role_id = request()->role_id;
        $user->phone = request()->phone;
        $user->email = request()->email;
        $user->secteur_id = $request->secteur_id;
        $user->agence_id = request()->agence_id;
        $user->representation_id = request()->representation_id;
        $user->banque_id = request()->banque_id;
        $user->save();
        return back();
    }

    public function  enable($token){
        $user = User::where('token',$token)->first();
        $user->active = 1;
        $user->save();
        return back();
    }

    public function  disable($token){
        $user = User::where('token',$token)->first();
        $user->active = 0;
        $user->save();
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Projet  $projet
     * @return \Illuminate\Http\Response
     */
	public function show($token)
	{

	}


}
