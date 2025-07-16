<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Http\Controllers\ExtendedController;
use App\Models\Structuration\Caisse;
use App\Models\Structuration\Wallet;

class CaisseController extends ExtendedController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $items = Caisse::all();
        return view('Tenant/Admin/Wallets/caisses')->with(compact('items'));
    }


    public function getWallet()
    {
        //
        $items = Wallet::all();
        return view('Tenant/Admin//wallets')->with(compact('items'));
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
