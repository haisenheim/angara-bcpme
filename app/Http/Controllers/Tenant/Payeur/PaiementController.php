<?php

namespace App\Http\Controllers\Tenant\Payeur;

use App\Http\Controllers\Controller;
use App\Http\Resources\PaiementResource;
use App\Models\Structuration\Paiement;
use App\User;
use Illuminate\Http\Request;

class PaiementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
       // $items = Paiement::orderBy('created_at','DESC')->where('user_id',auth()->user()->id)->get();
        return view('Tenant/Payeur/Paiements/index');
    }

    public function fetchAll(){

        $items = Paiement::orderBy('created_at','DESC')->where('user_id',auth()->user()->id)->get();
       // dd($items);
        $items = PaiementResource::collection($items);
        //dd($items);
        return response()->json($items);
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

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Projet  $projet
     * @return \Illuminate\Http\Response
     */
	public function show($id)
	{

	}


}
