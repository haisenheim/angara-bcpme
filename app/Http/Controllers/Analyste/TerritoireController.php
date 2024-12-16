<?php

namespace App\Http\Controllers\Analyste;

use App\Http\Controllers\Controller;
use App\Http\Resources\LocaliteListResource;
use App\Models\Agence;
use App\Models\Arrondissement;
use App\Models\Departement;
use App\Models\Quartier;
use App\Models\Region;
use App\Models\Representation;
use App\Models\Village;
use Illuminate\Http\Request;

class TerritoireController extends Controller
{
    //

    public function index(){

        return view('/Analyste/Territoire/index');
    }



    public function getCommunes(){
        $items = Arrondissement::all();
        return view('/Analyste/Territoire/communes')->with(compact('items'));
    }

    public function getDepartements(){
        $items = Departement::all();
        return view('/Analyste/Terroitoire/departements')->with(compact('items'));
    }

    public function getRegions(){
        $items = Region::all();
        return view('/Analyste/Terroitoire/regions')->with(compact('items'));
    }

    public function getRepresentations(){
        $items = Representation::all();
        return view('/Analyste/Terroitoire/representations')->with(compact('items'));
    }

    public function getAgences(){
        $items = Agence::all();
        return view('/Analyste/Terroitoire/agences')->with(compact('items'));
    }

    public function getQuartiers(){
        $items = Quartier::all();
        return view('/Analyste/Terroitoire/quartiers')->with(compact('items'));
    }

    public function getVillages(){
        $items = Village::all();
        return view('/Analyste/Terroitoire/villages')->with(compact('items'));
    }
}
