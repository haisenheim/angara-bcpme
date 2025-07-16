<?php

namespace App\Http\Controllers\Tenant\Rstock;

use App\Http\Controllers\Controller;
use App\Models\Arrondissement;
use App\Models\Structuration\Cooperative;
use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class VillageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $coop = tenant();
        $villages = Village::where('arrondissement_id',$coop->arrondissement_id)->get();
        return view('Tenant/Rstock/Villages/index')->with(compact('villages'));
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
        $coop = tenant();
        $arrondissement = Arrondissement::find($coop->arrondissement_id);
        $village = Village::create(
            [
                'name'=>$data['name'],
                'latitude'=>$data['latitude'],
                'longitude'=>$data['longitude'],
                'region_id'=>$arrondissement->region_id,
                'departement_id'=>$arrondissement->departement_id,
                'arrondissement_id'=>$arrondissement->id,
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
	public function show($id)
	{
		//$village = Village::find($id);
		//return view('/Cooperative/Villages/show')->with(compact('village'));
	}


}
