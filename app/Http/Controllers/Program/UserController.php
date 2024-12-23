<?php

namespace App\Http\Controllers\Program;

use App\Http\Controllers\Controller;
use App\Models\Agence;
use App\Models\Representation;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

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
        $roles = Role::where('metier',1)->get();
        $representations = Representation::all();
        $agences = Agence::all();
        return view('/Program/Users/index')->with(compact('items','roles','representations','agences'));
    }


    public function getRoles(){
        $items = Role::where('metier',1)->get();
        return view('/Program/Users/roles')->with(compact('items'));
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
        $user->agence_id = request()->agence_id;
        $user->representation_id = request()->representation_id;
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
