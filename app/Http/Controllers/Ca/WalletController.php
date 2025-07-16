<?php

namespace App\Http\Controllers\Ca;

use App\Http\Controllers\ExtendedController;
use App\Models\Structuration\Cooperative;
use App\Models\Structuration\Wallet;
use App\Models\Structuration\Operateur;
use Illuminate\Http\Request;

class WalletController extends ExtendedController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $cooperatives = Cooperative::where('agence_id',auth()->user()->agence_id)->get();
        $operateurs = Operateur::where('active',1)->get();
        $ids = $cooperatives->pluck('id');
        //dd($ids);
        $items = Wallet::whereIn('cooperative_id',$ids)->get();
        return view('Ca.Wallets.index')->with(compact('cooperatives','items','operateurs'));
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
        $data = $request->all();
        $data['user_id'] = auth()->user()->id;
        $data['token'] = sha1(time());
        Wallet::create($data);
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
		$item = Cooperative::where('token',$token)->first();
		return view('Ca.Cooperatives.show')->with(compact('item'));
	}

}
