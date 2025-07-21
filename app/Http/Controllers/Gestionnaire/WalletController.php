<?php

namespace App\Http\Controllers\Gestionnaire;

use App\Http\Controllers\ExtendedController;
use App\Models\Structuration\Cooperative;
use App\Models\Structuration\Wallet;
use App\Models\Structuration\Operateur;
use App\Models\Tenant;
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
        $cooperatives = Cooperative::where('user_id',auth()->user()->id)->get();
        $operateurs = Operateur::where('active',1)->get();
        $ids = $cooperatives->pluck('id');
        //dd($ids);
        $items = Wallet::whereIn('cooperative_id',$ids)->get();
        return view('/Gestionnaire/Wallets/index')->with(compact('cooperatives','items','operateurs'));
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

    public function enable(Request $request)
    {
        $tenant = Tenant::where('token',$request->tenant_id)->first();
        $tenant->run(function() use ($request) {
            $wallet = Wallet::where('token',$request->wallet_id)->first();
            $wallet->active = 1;
            $wallet->save();
        });
        tenancy()->initialize($tenant);
        return back()->with('success','Le wallet a été activé avec succès');
    }

    public function disable(Request $request)
    {
        $tenant = Tenant::where('token',$request->tenant_id)->first();
        $tenant->run(function() use ($request) {
            $wallet = Wallet::where('token',$request->wallet_id)->first();
            $wallet->active = 0;
            $wallet->save();
        });
        tenancy()->initialize($tenant);
        return back()->with('success','Le wallet a été désactivé avec succès');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data['name'] = $request->name;
        $data['montant'] = $request->montant;
        $data['type_id'] = $request->operateur_id;
        $data['entrepot_id'] = $request->entrepot_id;
        $data['api_key'] = $request->api_key;
        $data['api_secret'] = $request->api_secret;
        //$data['user_id'] = auth()->user()->id;

        $data['token'] = sha1(time());
        $tenant = Tenant::find($request->cooperative_id);
        $tenant->run(function()use($data){
            Wallet::create($data);
        });
        tenancy()->initialize($tenant);
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
		return view('Gestionnaire/Cooperatives/show')->with(compact('item'));
	}


}
