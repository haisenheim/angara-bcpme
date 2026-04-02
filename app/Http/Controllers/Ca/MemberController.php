<?php

namespace App\Http\Controllers\Ca;

use App\Http\Controllers\ExtendedController;
use App\Models\Niveau;
use App\Models\Structuration\Cooperative;
use App\Models\Structuration\Entree;
use App\Models\Structuration\Exploitant;
use App\Models\Structuration\ExploitantPlateforme;
use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MemberController extends ExtendedController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $items = Exploitant::where('cooperative_id',auth()->user()->cooperative_id)->get();
        $coop = Cooperative::find(auth()->user()->cooperative_id);
        $villages = Village::where('arrondissement_id',$coop->arrondissement_id)->get();
        $niveaux = Niveau::all();
        return view('Ca/Members/index')->with(compact('villages','items','niveaux'));
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
     * Display the specified resource.
     *
     * @param  \App\Models\Projet  $projet
     * @return \Illuminate\Http\Response
     */
	public function show($token)
	{
		$item = Exploitant::where('token',$token)->first();
        $parts = Entree::where('saison_id',$this->_saison->id)->where('exploitant_id',$item->id)->get();
        //$pps = PaiementPart::where('saison_id',$this->_saison->id)->where('exploitant_id',$item->id)->get();
       // $plateformes = Plateforme::all();
		return view('Ca/Members/member')->with(compact('item','parts'));
	}

    


}
