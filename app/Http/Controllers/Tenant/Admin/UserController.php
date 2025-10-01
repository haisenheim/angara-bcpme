<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Http\Controllers\Controller;
use App\Models\Structuration\Caisse;
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
        $entrepots = Entrepot::where('tenant_id',tenant()->id)->get();
        $caisses = Caisse::where('tenant_id',tenant()->id)->get();
        return view('Tenant/Admin/Users/index')->with(compact('items','roles','entrepots','caisses'));
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
        if(request()->caisse_id){
            $caisse = Caisse::find(request()->caisse_id);
            $caisse->caissier_id = $user->id;
            $caisse->save();
        }
        return back();
    }

    public function  enable($id){
        $user = User::find($id);
        $user->active = 1;
        $user->save();
        return back();
    }

    public function  disable($id){
        $user = User::find($id);
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
