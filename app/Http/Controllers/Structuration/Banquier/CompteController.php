<?php

namespace App\Http\Controllers\Structuration\Banquier;

use App\Http\Controllers\ExtendedController;
use App\Models\Structuration\BanqueCooperative;
use App\Models\Structuration\Request as StructurationRequest;
use Illuminate\Http\Request;

class CompteController extends ExtendedController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $comptes = BanqueCooperative::where('banque_id',auth()->user()->banque_id)->get();
        return view('Structuration/Banquier/Comptes/index')->with(compact('comptes'));
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

    }




    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Projet  $projet
     * @return \Illuminate\Http\Response
     */
	public function show($token)
	{
		$item = BanqueCooperative::where('token',$token)->first();
        $tenant = $item->tenant;
        $requests = $tenant->run(function()use($item){
            return StructurationRequest::orderBy('created_at','desc')->where('source_id',$item->id)->whereNull('cancelled_at')->get();
        });
        tenancy()->initialize($tenant);
        return view('Structuration/Banquier/Comptes/show')->with(compact('item', 'requests'));
	}
}
