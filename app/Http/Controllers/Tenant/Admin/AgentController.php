<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Http\Controllers\ExtendedController;
use App\Models\Structuration\Agent;
use App\Models\Structuration\Cooperative;
use App\Models\Structuration\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AgentController extends ExtendedController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $items = User::where('role_id',2)->get();
        return view('Tenant/Admin/Agents/index')->with(compact('items'));
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
        $data = $request->except('photo');
        $user = new User();
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->token = sha1(time().rand(1,100));
        $user->role_id = 2;
        if($request->photo){
            $user->photo_uri = $this->entityImgCreate($request->photo,'users',$user->token);
        }
        $user->save();
        Session::flash('success','Nouvel agent de terrain créé avec succès!');
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
		$item = User::where('token',$token)->first();
       // dd($item);
		return view('Tenant/Admin/Agents/show')->with(compact('item'));
	}


}
