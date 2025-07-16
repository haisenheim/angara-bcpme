<?php

namespace App\Http\Controllers\Tenant\Payeur;

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
        $items = Caisse::where('entrepot_id',auth()->user()->entrepot_id)->get();
        //dd($items);
        return view('Tenant/Payeur/Wallets/caisses')->with(compact('items'));
    }


    public function getWallet()
    {
        //
        $items = Wallet::all();
        return view('Tenant/Payeur//wallets')->with(compact('items'));
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
