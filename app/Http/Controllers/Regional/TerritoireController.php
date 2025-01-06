<?php

namespace App\Http\Controllers\Regional;

use App\Http\Controllers\Controller;
use App\Models\Agence;
use App\Models\Arrondissement;
use App\Models\Departement;
use App\Models\Quartier;
use App\Models\Region;
use App\Models\Representation;
use App\Models\Village;

class TerritoireController extends Controller
{
    //

    public function index(){

        return view('/Regional/Territoire/index');
    }



    public function getCommunes(){
        $items = Arrondissement::all();
        return view('/Regional/Territoire/communes')->with(compact('items'));
    }

    public function getDepartements(){
        $items = Departement::all();
        return view('/Regional/Terroitoire/departements')->with(compact('items'));
    }

    public function getRegions(){
        $items = Region::all();
        return view('/Regional/Terroitoire/regions')->with(compact('items'));
    }

    public function getRepresentations(){
        $items = Representation::all();
        return view('/Regional/Terroitoire/representations')->with(compact('items'));
    }

    public function getAgences(){
        $items = Agence::all();
        return view('/Regional/Terroitoire/agences')->with(compact('items'));
    }

    public function getQuartiers(){
        $items = Quartier::all();
        return view('/Regional/Terroitoire/quartiers')->with(compact('items'));
    }

    public function getVillages(){
        $items = Village::all();
        return view('/Regional/Terroitoire/villages')->with(compact('items'));
    }
}
