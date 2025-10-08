<?php

namespace App\Http\Controllers\Tenant\Agent;

use App\Http\Controllers\Controller;
use App\Models\Structuration\Entrepot;
use Illuminate\Http\Request;

class EntrepotController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $items = Entrepot::where('tenant_id',tenant()->id)->get();
        return view('Tenant/Agent/Entrepots/index')->with(compact('items'));
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
    {}

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Projet  $projet
     * @return \Illuminate\Http\Response
     */
	public function show($token)
	{
		$item = Entrepot::where('token',$token)->first();
		return view('/Tenant/Agent/Entrepots/show')->with(compact('item'));
	}


}
