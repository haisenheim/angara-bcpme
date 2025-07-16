<?php

namespace App\Http\Controllers\Gestionnaire;

use App\Http\Controllers\ExtendedController;
use App\Models\Secteur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SecteurController extends ExtendedController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $items = Secteur::where('agence_id',auth()->user()->agence_id)->get();
        return view('Gestionnaire/Secteurs/index')->with(compact('items'));
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
		$item = Secteur::where('token',$token)->first();
		return view('Gestionnaire/Secteurs/show')->with(compact('item'));
	}




}
