<?php

namespace App\Http\Controllers\Tenant\Payeur;

use App\Http\Controllers\Controller;
use App\Models\Structuration\Entrepot;
use App\Models\Structuration\Role;
use App\Models\Structuration\User;
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
        $items = User::all();
        $roles = Role::all();
        $entrepots = Entrepot::all();
        return view('Tenant/Payeur/Users/index')->with(compact('items','roles','entrepots'));
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
        $user->entrepot_id = request()->entrepot_id;
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
