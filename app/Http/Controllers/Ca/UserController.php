<?php

namespace App\Http\Controllers\Ca;

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
        $items = User::where('agence_id',auth()->user()->agence_id)->get();
        return view('/Ca/Users/index')->with(compact('items'));
    }


    public function getRoles(){
        $items = Role::where('metier',1)->get();
        return view('/Ca/Users/roles')->with(compact('items'));
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
