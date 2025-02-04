<?php

namespace App\Http\Controllers\Cooperative;

use App\Http\Controllers\Controller;
use App\Models\Structuration\Cooperative;
use App\Models\Structuration\Entrepot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

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
        $items = Entrepot::where('cooperative_id',auth()->user()->cooperative_id)->get();
        return view('Cooperative/Entrepots/index')->with(compact('items'));
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
        $cp = Cooperative::find(auth()->user()->cooperative_id);
        $entreprot = Entrepot::create(
            [
                'name'=>$data['name'],
                'latitude'=>$data['latitude'],
                'longitude'=>$data['longitude'],
                'cooperative_id'=>$cp->id,
                'region_id'=>$cp->region_id,
                'departement_id'=>$cp->departement_id,
                'arrondissement_id'=>$cp->arrondissement_id,
                'agence_id'=>$cp->agence_id,
                'representation_id'=>$cp->representation_id,
                'token'=>sha1(time()),
            ]
        );
        Session::flash('success','Enregistrement effectué avec succès!');
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
		$item = Entrepot::where('token',$token)->first();
		return view('/Cooperative/Entrepots/show')->with(compact('item'));
	}


}
